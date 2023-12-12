<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\pendapatan_jasa;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use DB;

class DischargePasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-discharge-pasien');
    }

    public function index()
    {
        return view("discharge_pasien/index");
    }

    public function rekapitulasi(Request $request)
    {
        $data = DB::table('pendapatan_jasa')
        ->selectRaw('pendapatan_jasa.id, no_kunjungan, tanggal, pelanggan.nama, 
        (SUM(pendapatan_jasa.total_tagihan) + SUM(DISTINCT(penjualan.total_tagihan))) AS total_tagihan')
        ->leftJoin('pelanggan', 'pelanggan.id', 'pendapatan_jasa.id_pelanggan') 
        ->leftJoin('visit', 'pendapatan_jasa.no_kunjungan', 'visit.id')
        ->leftJoin('penjualan_resep', 'penjualan_resep.id_visit','visit.id')
        ->leftJoin('penjualan', 'penjualan_resep.id_penjualan', 'penjualan.id')
        ->where('penjualan.jenis_pasien','RI')
        ->where('pendapatan_jasa.jenis', 'RI')
        ->where('visit.flag_discharge', 'N')
        ->where('pelanggan.nama', $request->pasien)
        ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai])
        ->groupBy('visit.id')
        ->get();

        return view ('discharge_pasien/rekapitulasi', compact('data'));
    }

    public function discharge(Request $request)
    {
        $sekarang = Carbon::now();
        $id_user = Auth::user()->id;

        try {
                
            DB::beginTransaction();

            pendapatan_jasa::whereIn('id', $request->id_pendapatan_jasa)->update([
                'waktu_pulang'=>$sekarang,
                'discharge'=>$request->discharge,
                'user_update'=>$id_user,
            ]);

            $ulang = pendapatan_jasa::where('waktu_pulang', $sekarang)->where('discharge', '<>', 'Y')->update(['waktu_pulang'=>'0000-00-00']);
            $update_visit = "UPDATE visit 
            SET flag_discharge='Y' WHERE visit.id IN (SELECT no_kunjungan FROM pendapatan_jasa WHERE waktu_pulang='$sekarang' AND discharge='Y')";
         
                
            DB::commit();
            message(true, 'Berhasil update discharge', '');
            return redirect('discharge_pasien/index');

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
