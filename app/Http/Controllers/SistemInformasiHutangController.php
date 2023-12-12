<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Perkiraan;
use App\pembelian;
use App\Models\InstansiRelasi;
use App\Models\TarifPajak;
use App\jenis_instansi;
use App\JenisPembelian;
use App\Models\PeriodeKeuangan;
use App\BukuBesarPembantuHutang;
use App\Models\TerminPembayaran;
use Illuminate\Http\Request;
use DB;
use Auth;

class SistemInformasiHutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-sistem-informasi-hutang');
    }

    public function index ()
    {
        $InstansiRelasi = DB::table('instansi_relasi')
        ->selectRaw('instansi_relasi.id, instansi_relasi.nama as pemasok,  saldo_hutang,  perkiraan.nama as rekening_kontrol')
        ->leftJoin('perkiraan', 'perkiraan.id', 'instansi_relasi.id_perkiraan')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id',  'instansi_relasi.id_termin')
        ->leftJoin('tarif_pajak', 'tarif_pajak.id', 'instansi_relasi.id_tarif_pajak')
        ->simplePaginate(30);

        return view ('sistem-informasi-hutang/index', compact('InstansiRelasi'));
    }

    public function tambahSaldo (Request $request)
    {
        $faktur = pembelian::selectRaw("concat('PU-', substr(no_faktur, 4) +1) as no_faktur")->orderByDesc('id')->firstOrFail();
        $periodeKeuangan = PeriodeKeuangan::where('status_aktif', 'Y')->first();
        $InstansiRelasi = InstansiRelasi::selectRaw('instansi_relasi.nama, instansi_relasi.id, id_perkiraan, jumlah_hari')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id', 'instansi_relasi.id_termin')
        ->where('instansi_relasi.id', $request->id)
        ->firstOrFail();

        return view('sistem-informasi-hutang/tambah-saldo', compact('InstansiRelasi','faktur','periodeKeuangan'));
    }

    public function detailHutang (Request $request)
    {
        $instansiRelasi = InstansiRelasi::where('id', $request->id)->firstOrFail();
        $detailHutang = DB::table('pembelian')
        ->selectRaw('pembelian.keterangan, pembelian.jatuh_tempo, pembelian.waktu AS tanggal, DATEDIFF(CURDATE(), 
        pembelian.waktu) AS umur_piutang, jumlah_tagihan AS hutang')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->orderBy('pembelian.id', 'asc')
        ->where('instansi_relasi.id', $request->id)
        ->where('status_bayar', 2)
        ->get();

        return view ('sistem-informasi-hutang/detail-hutang', compact('instansiRelasi','detailHutang'));
    }

    public function mutasiHutang (Request $request)
    {
        $id = $request->id;
        $instansi = InstansiRelasi::where('id', $id)->firstOrFail();
        $Mutasi = DB::table('buku_besar_pembantu_hutang')
        ->selectRaw("buku_besar_pembantu_hutang.id, buku_besar_pembantu_hutang.tanggal, buku_besar_pembantu_hutang.keterangan, 
        perkiraan.nama, buku_besar_pembantu_hutang.debet, buku_besar_pembantu_hutang.kredit, 
        (SELECT SUM(b.kredit) - SUM(b.debet) FROM buku_besar_pembantu_hutang b 
        WHERE b.id <= buku_besar_pembantu_hutang.id AND b.id_instansi_relasi='$id') AS saldo")
        ->leftJoin('perkiraan', 'perkiraan.id', 'buku_besar_pembantu_hutang.id_perkiraan')
        ->where('id_instansi_relasi', $id)
        ->get();

        return view ('sistem-informasi-hutang/mutasi-hutang', compact('Mutasi', 'instansi'));
    }

    public function SimpanSaldoHutang (Request $request)
    {
        $id_user = Auth::user()->id;
        $tanggal = date('y-m-d');
        $saldo_hutangg = str_replace('.','', $request->saldo_hutang);
        $saldo_hutang = preg_replace('/[^0-9]/','',$saldo_hutangg);

        DB::beginTransaction();

        try {

            $request->validate([
                'saldo_hutang'=>'required',
                'jatuh_tempo'=>'required',
                'tanggal_hutang'=>'required',
                'tanggal_jatuh_tempo'=>'required',
            ]);

            $instansiRelasi = InstansiRelasi::where('id', $request->id_instansi_relasi)->update([
            'saldo_hutang'=>$saldo_hutang,
            'jatuh_tempo'=>$request->tanggal_jatuh_tempo,
            'tanggal_hutang'=>$request->tanggal_hutang]);

            $act = new pembelian;
            $act->keterangan = 1;
            $act->no_faktur = $request->no_faktur;
            $act->id_perkiraan = $request->id_perkiraan;
            $act->waktu = $request->tanggal_hutang;
            $act->id_instansi_relasi = $request->id_instansi_relasi;
            $act->ppn = 0;
            $act->diskon = 0;
            $act->materai = 0;
            $act->jatuh_tempo = $request->tanggal_jatuh_tempo;
            $act->jumlah_nominal = $saldo_hutang;
            $act->jumlah_tagihan = $saldo_hutang;
            $act->user_input = $id_user;
            $act->save();

            $BukuBesarPembantuHutang = new BukuBesarPembantuHutang;
            $BukuBesarPembantuHutang->tanggal = $tanggal;
            $BukuBesarPembantuHutang->id_instansi_relasi = $request->id_instansi_relasi;
            $BukuBesarPembantuHutang->id_periode = $request->id_periode;
            $BukuBesarPembantuHutang->id_perkiraan = $request->id_perkiraan;
            $BukuBesarPembantuHutang->keterangan = 'Saldo Awal';
            $BukuBesarPembantuHutang->debet = 0;
            $BukuBesarPembantuHutang->kredit = $saldo_hutang;
            $BukuBesarPembantuHutang->user_input = $id_user;
            $BukuBesarPembantuHutang->save();
            
            $perkiraan = Perkiraan::where('id', $request->id_perkiraan)->update(['id_kategori' => 1, 'kredit'=> $saldo_hutang]);

            DB::commit();
            message($act, 'Tambah Saldo Berhasil', 'Tambah Saldo Gagal');
            return redirect('sistem-informasi-hutang');
        } 
        catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Tambah Saldo Gagal');
            return redirect('sistem-informasi-hutang');
        }
    }
}
