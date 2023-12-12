<?php

namespace App\Http\Controllers;
use DB;
use App\Models\AktivaTetap;
use App\Models\KelompokAktiva;
use App\Penyusutan;
use Illuminate\Http\Request;

class PenyusutanAktivaTetapController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-penyusutan-aktiva-tetap');
    }

    public function index ()
    {
        $bulan = null;
        $tahun = date('Y');

        $id =null;
        $aktivaTetap = DB::select("SELECT A.id, A.nama AS nama_aktiva , B.id AS id_penyusutan, B.tanggal AS tangal_penyusutan,
        COALESCE(B.hp, A.harga_perolehan) AS hp,
        COALESCE(B.periode, A.umur_ekonomis) AS periode,
        COALESCE(B.residu, A.nilai_residu) AS residu, COALESCE(B.tanggal_beli, A.tanggal_pembelian) AS tanggal_beli,


        CASE
        WHEN A.id IN (SELECT id_aktiva_tetap FROM penyusutan WHERE MONTH(tanggal_penyusutan) = '$bulan' AND YEAR(tanggal_penyusutan)='$tahun') THEN 'Ada'
        ELSE 'Tidak Ada' END AS status

        FROM

        (SELECT id , nama, harga_perolehan, umur_ekonomis, nilai_residu, tanggal_pembelian FROM aktiva_tetap WHERE
        CASE
        WHEN '$id' IS NULL THEN id IN(SELECT id FROM aktiva_tetap)
        when '$id' = '' then id IN(SELECT id FROM aktiva_tetap)
        ELSE id='$id' END) A

        LEFT JOIN

        (SELECT penyusutan.id , id_aktiva_tetap AS id_aktiva ,aktiva_tetap.harga_perolehan AS hp, tanggal_penyusutan AS tanggal,
        aktiva_tetap.umur_ekonomis AS periode, aktiva_tetap.nilai_residu AS residu, aktiva_tetap.tanggal_pembelian AS tanggal_beli
        FROM penyusutan
        LEFT JOIN aktiva_tetap ON aktiva_tetap.id = penyusutan.id_aktiva_tetap
        WHERE MONTH(tanggal_penyusutan) = '$bulan' AND YEAR(tanggal_penyusutan)='$tahun' ) B ON B.id_aktiva = A.id");

        $aktiva = AktivaTetap::get(['id', 'nama']);

        return view('penyusutan-aktiva-tetap/index', compact('aktiva', 'aktivaTetap', 'tahun', 'bulan'));
    }

    public function rekap (Request $request)
    {

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $id = $request->id_aktiva_tetap;
        $aktiva = AktivaTetap::select('id', 'nama')->get();
        $penyusutan = Penyusutan::where('id_aktiva_tetap', $request->id_aktiva_tetap)->first();

        $aktivaTetap = DB::select("SELECT A.id, A.nama AS nama_aktiva , B.id AS id_penyusutan, B.tanggal AS tangal_penyusutan,
        COALESCE(B.hp, A.harga_perolehan) AS hp,
        COALESCE(B.periode, A.umur_ekonomis) AS periode,
        COALESCE(B.residu, A.nilai_residu) AS residu, COALESCE(B.tanggal_beli, A.tanggal_pembelian) AS tanggal_beli,


        CASE
        WHEN A.id IN (SELECT id_aktiva_tetap FROM penyusutan WHERE MONTH(tanggal_penyusutan) = '$bulan' AND YEAR(tanggal_penyusutan)='$tahun') THEN 'Ada'
        ELSE 'Tidak Ada' END AS status

        FROM

        (SELECT id , nama, harga_perolehan, umur_ekonomis, nilai_residu, tanggal_pembelian FROM aktiva_tetap WHERE
        CASE
        WHEN '$id' IS NULL THEN id IN(SELECT id FROM aktiva_tetap)
        when '$id' = '' then id IN(SELECT id FROM aktiva_tetap)
        ELSE id='$id' END) A

        LEFT JOIN

        (SELECT penyusutan.id , id_aktiva_tetap AS id_aktiva ,aktiva_tetap.harga_perolehan AS hp, tanggal_penyusutan AS tanggal,
        aktiva_tetap.umur_ekonomis AS periode, aktiva_tetap.nilai_residu AS residu, aktiva_tetap.tanggal_pembelian AS tanggal_beli
        FROM penyusutan
        LEFT JOIN aktiva_tetap ON aktiva_tetap.id = penyusutan.id_aktiva_tetap
        WHERE MONTH(tanggal_penyusutan) = '$bulan' AND YEAR(tanggal_penyusutan)='$tahun' ) B ON B.id_aktiva = A.id");

        return view ('penyusutan-aktiva-tetap/index', compact('aktiva', 'bulan', 'penyusutan', 'aktivaTetap', 'tahun'));
    }

    public function penyusutan (Request $request)
    {
        $penyusutan = Penyusutan::where('id_aktiva_tetap', $request->id_aktiva_tetap)->whereMonth('tanggal_penyusutan', $request->bulan)->first();

        if (isset($penyusutan)){
            message(false, '', 'Perhatian! Aktiva ini telah disusutkan pada bulan ini, terima kasih');
            return redirect('penyusutan-aktiva-tetap/index');
        }

        $urutan_penyusutan = Penyusutan::selectRaw('id_aktiva_tetap, urutan_penyusutan + 1 AS urutan_penyusutan, nilai_buku')
        ->where('id_aktiva_tetap', $request->id_aktiva_tetap)
        ->orderByDesc('id')
        ->firstOrFail();

        $aktiva = AktivaTetap::selectRaw('kelompok_aktiva.nama AS kelompok_aktiva, aktiva_tetap.nama AS aktiva_tetap, tanggal_pembelian,
        umur_ekonomis AS ekonomis_umur, CAST(umur_ekonomis/12 AS SIGNED) AS umur_ekonomis, aktiva_tetap.harga_perolehan, nilai_residu,
        CAST((aktiva_tetap.harga_perolehan - nilai_residu) / umur_ekonomis AS SIGNED) AS tarif_penyusutan')
        ->join('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->where('aktiva_tetap.id', $request->id_aktiva_tetap)
        ->first();

        //dd($urutan_penyusutan->nilai_buku);

        $nilai_buku = $urutan_penyusutan->nilai_buku - $aktiva->tarif_penyusutan;
        $query = DB::table('penyusutan')
        ->selectRaw('YEAR(tanggal_penyusutan) AS tahun, urutan_penyusutan AS bulan, nominal AS penyusutan, nilai_buku AS nilai_buku')
        ->join('aktiva_tetap', 'aktiva_tetap.id', 'penyusutan.id_aktiva_tetap')
        ->where('penyusutan.id_aktiva_tetap', $request->id_aktiva_tetap);

        $laporan = DB::table('aktiva_tetap')
        ->selectRaw('YEAR(tanggal_pembelian) AS tahun, 0 AS bulan, 0 AS penyusutan, harga_perolehan AS nilai_buku')
        ->where('id', $request->id_aktiva_tetap)
        ->unionAll($query)
        ->get();

        return view ('penyusutan-aktiva-tetap/penyusutan', compact('laporan', 'aktiva', 'nilai_buku', 'urutan_penyusutan'));
    }

    public function penyusutanAktiva ($id,$bulan)
    {
        $bulan = (int)$bulan;
        $id = (int)$id;

        $tahun = date('Y');
        $penyusutan = Penyusutan::where('id_aktiva_tetap', $id)->get();
        $pengurang = Penyusutan::selectRaw('sum(nominal) as nominal')->where('id_aktiva_tetap', $id)->first() ?? AktivaTetap::selectRaw('sum(tarif) as nominal')->where('id',$id)->first();

        $aktiva = AktivaTetap::select('aktiva_tetap.id', 'kelompok_aktiva.nama AS kelompok_aktiva', 'aktiva_tetap.nama AS aktiva_tetap')
        ->selectRaw('umur_ekonomis AS ekonomis_umur, CAST((umur_ekonomis/12) AS SIGNED) AS umur_ekonomis ,aktiva_tetap.harga_perolehan')
        ->selectRaw('tanggal_pembelian, penyusutan.nominal')
        ->selectRaw('nilai_residu, CAST( ((aktiva_tetap.harga_perolehan - nilai_residu) / umur_ekonomis) AS SIGNED ) AS tarif_penyusutan')
        ->join('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->join('penyusutan', 'penyusutan.id_aktiva_tetap', 'aktiva_tetap.id')
        ->where('aktiva_tetap.id', $id)
        //->whereMonth('tanggal_penyusutan', $bulan)
        //->whereYear('tanggal_penyusutan', $tahun)
        ->orderByDesc('id')
        ->first();

        //dd($aktiva);

        $harga_perolehan = (isset($aktiva)) ? $aktiva->harga_perolehan : 0;
        $nominal = (isset($aktiva)) ? $aktiva->nominal :0;
        $tarif_penyusutan = (isset($aktiva)) ? $aktiva->tarif_penyusutan : 0;
        //dd($tarif_penyusutan);

        $nilai_buku = $harga_perolehan - $pengurang->nominal - $nominal;

        return view ('penyusutan-aktiva-tetap/penyusutan-aktiva', compact('aktiva', 'penyusutan', 'id','nilai_buku', 'bulan', 'tahun', 'harga_perolehan', 'tarif_penyusutan'));
    }

    public function simpan (Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = $request->validate([
                'tarif'=>'required',
                'id_aktiva_tetap'=>'required',
                'bulan'=>'required',
                'tahun'=>'required',
                'urutan_penyusutan'=>'required',
                'nilai_buku'=>'required',
            ]);

            $tanggal = 01-$request->bulan-$request->tahun;
            $tanggal_akhir = date("Y-m-t", strtotime($tanggal));
            //dd($tanggal_akhir);

            AktivaTetap::where('id', $request->id_aktiva_tetap)->increment('penyusutan_berjalan', $request->tarif);

            $act = new Penyusutan;
            $act->id_aktiva_tetap = $request->id_aktiva_tetap;
            $act->tanggal_penyusutan = $tanggal_akhir;
            $act->urutan_penyusutan = $request->urutan_penyusutan;
            $act->nominal = $request->tarif;
            $act->nilai_buku = $request->nilai_buku;
            $act->user_input = $request->user_input;
            $act->save();
        }
        catch (\Illuminate\Database\QueryException $e)
        {
           DB::rollback();
           message(false, '', 'Penyusutan Aktiva Tetap gagal disimpan');
           return redirect ('penyusutan-aktiva-tetap/index');
        }
        DB::commit();
        message($act, 'Penyusutan Aktiva Tetap Berhasil disimpan', 'Penyusutan Aktiva Tetap Berhasil disimpan');
        return redirect ('penyusutan-aktiva-tetap/index');
    }
}
