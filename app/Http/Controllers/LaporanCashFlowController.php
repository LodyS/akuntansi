<?php

namespace App\Http\Controllers;
use DB;
use App\Models\KasBank;
use Illuminate\Http\Request;

class LaporanCashFlowController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-cash-flow');
    }

    public function index()
    {
        $kasBank = KasBank::get(['id', 'nama']);

        return view('laporan-cash-flow/index', compact('kasBank'));
    }

    public function laporan (Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bank = $request->id_bank;

        $kasBank = KasBank::get(['id', 'nama']);

        $a = DB::table('jurnal')
        ->selectRaw('jenis_transaksi.kode AS kode, transaksi_jurnal.nama AS arus_kas, perkiraan.nama AS coa')
        ->selectRaw('SUM(detail_jurnal.debet) - SUM(detail_jurnal.kredit) AS saldo')
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('kas_bank', 'kas_bank.id_perkiraan', 'perkiraan.id')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'jurnal.id_transaksi_jurnal')
        ->leftJoin('jenis_transaksi', 'jenis_transaksi.id_transaksi_jurnal', 'transaksi_jurnal.id')
        ->whereMonth('jurnal.tanggal_posting', $bulan)
        ->whereYear('jurnal.tanggal_posting', $tahun)
        ->when($bank, function($query, $bank){
            return $query->where('kas_bank.id', $bank);
        })
        ->groupBy('jenis_transaksi.kode', 'transaksi_jurnal.nama', 'perkiraan.nama')
        ->orderBy('jenis_transaksi.kode');

        $b = DB::table('transaksi_jurnal')
        ->select('kode', 'jenis_transaksi.nama as arus_kas')
        ->join('jenis_transaksi', 'jenis_transaksi.id_transaksi_jurnal', 'transaksi_jurnal.id');

        $c = DB::table('arus_kas_rumus')
        ->selectRaw('jenis_transaksi.kode AS kode, transaksi_jurnal.nama, SUM(detail_jurnal.debet) - SUM(detail_jurnal.kredit) AS total')
        ->join('transaksi_jurnal', 'transaksi_jurnal.id', 'arus_kas_rumus.id_rumus_arus_kas')
        ->join('jenis_transaksi', 'jenis_transaksi.id_transaksi_jurnal', 'transaksi_jurnal.id')
        ->join('jurnal','jurnal.id_transaksi_jurnal', 'arus_kas_rumus.id_transaksi_jurnal')
        ->join('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->join('kas_bank', 'kas_bank.id_perkiraan', 'perkiraan.id')
        ->whereMonth('jurnal.tanggal_posting', $bulan)
        ->whereYear('jurnal.tanggal_posting', $tahun)
        ->when($bank, function($query, $bank){
            return $query->where('kas_bank.id', $bank);
        })
        ->groupBy('transaksi_jurnal.nama');

        $data = DB::table(DB::raw("({$a->toSql()}) as a"))
        ->mergeBindings($a)
        ->selectRaw('b.kode, b.arus_kas, a.coa, IFNULL(a.saldo,0) AS saldo , IFNULL(a.saldo,0) AS total')
        ->rightJoinSub($b, 'b', function($join){
            $join->on('b.kode', 'a.kode');
        })
        ->leftJoinSub($c, 'c', function($join){
            $join->on('c.kode', 'b.kode');
        })
        ->get(); ## Merge query dari variable A-C

        return view ('laporan-cash-flow/index', compact('data', 'kasBank', 'bulan', 'tahun'));
    }
}
