<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Arr;
use App\Payroll;
use Auth;
use Illuminate\Http\Request;

class LaporanPayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-payroll');
    }

    public function index ()
    {
        $sekarang = date('Y-m-d');
        $unit = DB::table('unit')->select('id', 'nama')->get();
        $data = DB::table('payroll')
        ->selectRaw('payroll.id, payroll.pemilik_rekening, payroll.total_tagihan, payroll.pajak, biaya_adm_bank, payroll.no_rekening,
        tanggal_transaksi, unit.nama as unit')
        ->leftJoin('unit', 'unit.id', 'payroll.id_unit')
        ->whereDate('tanggal_transaksi', $sekarang)
        ->get();

        $hitung = DB::table('payroll')->selectRaw('count(id) as total')->first();

        return view('laporan-payroll/index', compact('unit', 'data', 'hitung'));
    }

    public function laporan (Request $request)
    {
        $unit = DB::table('unit')->select('id', 'nama')->get();
        $tanggal_posting = $request->tanggal_posting;
        $id_unit = $request->id_unit;
        $jenis_data = $request->jenis_data;

        $data = DB::table('payroll')
        ->selectRaw('payroll.id, payroll.pemilik_rekening, payroll.total_tagihan, payroll.pajak, biaya_adm_bank, payroll.no_rekening,
        tanggal_transaksi, unit.nama as unit')
        ->leftJoin('unit', 'unit.id', 'payroll.id_unit')
        ->where(function($query) use($id_unit, $jenis_data, $tanggal_posting){
            if (isset($id_unit)){
                $query->where('id_unit', $id_unit)
                ->where('flag_jurnal', $jenis_data)
                ->whereDate('tanggal_transaksi', $tanggal_posting);
            } else {
                $query->where('flag_jurnal', $jenis_data)
                ->whereDate('tanggal_transaksi', $tanggal_posting);
            }
        })
        ->get();

        $hitung = DB::table('payroll')
        ->selectRaw('count(id) as total')
        ->where('id_unit', $request->id_unit)
        ->where('flag_jurnal', $request->jenis_data)
        ->whereDate('tanggal_transaksi', $tanggal_posting)
        ->first();

        return view('laporan-payroll/index', compact('data', 'tanggal_posting', 'id_unit', 'hitung', 'unit'));
    }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'id_payroll'=>'required',
        ]);

        $jumlah = count($request->id_payroll);
        for ($i=0; $i<$jumlah; $i++)
        {
            $update = DB::table('payroll')->where('id', $request->id_payroll[$i])->update(['flag_verif'=>$request->flag_verif[$i],]);
        }

        message(true, 'Berhasil verifikasi', 'Gagal verfikasi');
        return redirect('laporan-payroll/index');
    }

    public function detail (Request $request)
    {
        $payroll = Payroll::selectRaw('payroll.id, payroll.kode_referal, payroll.pemilik_rekening, payroll.total_tagihan,
        payroll.pajak, biaya_adm_bank, payroll.no_rekening, tanggal_transaksi, unit.nama as unit')
        ->leftJoin('unit', 'unit.id', 'payroll.id_unit')
        ->where('payroll.id', $request->id)
        ->firstOrFail();

        $payrollDetail = DB::table('detail_payroll')
        ->selectRaw('komponen, nominal')
        ->where('kode_referal', $payroll->kode_referal)
        ->paginate(20);

        return view('laporan-payroll/detail', compact('payroll', 'payrollDetail'));
    }
}
