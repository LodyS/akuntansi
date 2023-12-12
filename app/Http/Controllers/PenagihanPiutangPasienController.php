<?php

namespace App\Http\Controllers;
use App\Models\ProdukAsuransi;
use App\visit;
use App\Models\Pelanggan;
use App\pendapatan_jasa;
use App\detail_pendapatan_jasa;
use App\pendapatan_jasa_langganan;
use App\pembayaran;
use App\kelas;
use App\Models\Unit;
use App\Tagihan;
use App\Models\Tarif;
use DB;
use Illuminate\Http\Request;

class PenagihanPiutangPasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-penagihan-piutang-pasien');
    }

    public function index ()
    {
        $produkAsuransi = ProdukAsuransi::select('id', 'nama')->get();
        $pasien = Visit::select('id_pelanggan', 'pelanggan.nama')
        ->join('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
        ->groupBy('id_pelanggan')
        ->get();

        return view ('penagihan-piutang-pasien/index', compact('produkAsuransi', 'pasien'));
    }

    public function rekapitulasi (Request $request)
    {
        if ($request->tipe_pasien == null)
        {
            message(false, '', 'Silahkan isi semua parameter');
            return redirect('penagihan-piutang-pasien/index');
        }

        $tagihan = DB::table('tagihan')
        ->selectRaw('DISTINCT(tagihan.id) AS id,  tagihan.tanggal, pelanggan.nama, tagihan.no_kunjungan, tagihan.piutang')
        ->leftJoin('visit', 'visit.id', 'tagihan.no_kunjungan')
        ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.no_kunjungan', 'visit.id')
        ->leftJoin('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
        ->where('tagihan.status_tagihan', 'N')
        ->where('pendapatan_jasa.tipe_pasien', $request->tipe_pasien)
        ->where('pendapatan_jasa.jenis', $request->tipe_kunjungan_pasien)
        ->where('tagihan.id_pelanggan', $request->id_pasien)
        ->where('tagihan.tanggal', $request->tanggal_penagihan)
        ->simplePaginate(25);

        return view ('penagihan-piutang-pasien/rekapitulasi', compact('tagihan'));
    }

    public function edit (Request $request)
    {
        $data = DB::table('tagihan')
        ->selectRaw('tagihan.id, no_kunjungan, pelanggan.nama as pelanggan')
        ->leftJoin('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
        ->where('tagihan.id', $request->id)
        ->first();

        echo json_encode($data);
    }

    public function update (Request $request)
    {
        $request->validate([
            'status_tagihan'=>'required',
        ]);

        $act = DB::table('tagihan')->where('id', $request->id)->update(['status_tagihan' =>$request->status_tagihan]);

        if ($act) {
            message($act, 'Berhasil update tagihan', 'Gagal simpan');
            return redirect('penagihan-piutang-pasien/index');
        } else {
            return redirect('penagihan-piutang-pasien/index')->with('danger', 'Gagal update');
        }
    }
}
