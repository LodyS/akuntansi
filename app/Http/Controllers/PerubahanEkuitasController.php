<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SettingPerusahaan;
use Illuminate\Http\Request;

class PerubahanEkuitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-perubahan-ekuitas');
    }

    public function index()
    {

        $bulan = date('m');
        $tahun = date('Y');
        $setting = SettingPerusahaan::select('nama')->first();
        $data = collect(DB::select("SELECT B.kode, B.coa, COALESCE(C.kredit - C.debet,0) AS SALDO_AWAL,
        COALESCE(A.debet,0) AS debet, COALESCE(A.kredit,0) AS kredit
        FROM
        (SELECT pk.kode_rekening AS kode,pk.nama AS coa, SUM(dj.debet) AS debet, SUM(dj.kredit) AS kredit FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        WHERE MONTH(j.tanggal_posting)='$bulan'
        AND YEAR(j.tanggal_posting)='$tahun'
        AND pk.kode_r ekening LIKE '3%'
        AND j.keterangan != 'Saldo Awal'
        AND j.id_tipe_jurnal IS NOT NULL
        GROUP BY pk.kode_rekening, pk.nama) A

        RIGHT JOIN
        (SELECT kode_rekening AS kode, nama AS coa FROM perkiraan WHERE kode_rekening LIKE '3%' ) BON B.kode =A.kode

        LEFT JOIN
        (SELECT pk.kode_rekening AS kode,pk.nama AS coa, SUM(dj.debet) AS debet, SUM(dj.kredit) AS kredit FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        WHERE MONTH(j.tanggal_posting)='$bulan'
        AND YEAR(j.tanggal_posting)='$tahun'
        AND pk.kode_rekening LIKE '3%'
        AND j.keterangan = 'Saldo Awal'
        AND j.id_tipe_jurnal IS NOT NULL
        GROUP BY pk.kode_rekening, pk.nama) C ON B.kode =C.kode ORDER BY B.kode"));

        return view('perubahan-ekuitas/index', compact('data', 'setting', 'bulan', 'tahun'));
    }

    public function laporan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $setting = SettingPerusahaan::select('nama')->first();
        $data = collect(DB::select("SELECT B.kode, B.coa, COALESCE(C.kredit - C.debet,0) AS SALDO_AWAL,
        COALESCE(A.debet,0) AS debet, COALESCE(A.kredit,0) AS kredit FROM

        (SELECT pk.kode_rekening AS kode,pk.nama AS coa, SUM(dj.debet) AS debet, SUM(dj.kredit) AS kredit FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        WHERE MONTH(j.tanggal_posting)='$request->bulan'
        AND YEAR(j.tanggal_posting)='$request->tahun'
        AND pk.kode_rekening LIKE '3%'
        AND j.keterangan != 'Saldo Awal'
        AND j.id_tipe_jurnal IS NOT NULL
        GROUP BY pk.kode_rekening, pk.nama) A

        RIGHT JOIN
        (SELECT kode_rekening AS kode, nama AS coa FROM perkiraan WHERE kode_rekening LIKE '3%' ) B ON B.kode =A.kode

        LEFT JOIN
        (SELECT pk.kode_rekening AS kode,pk.nama AS coa, SUM(dj.debet) AS debet, SUM(dj.kredit) AS kredit FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        WHERE MONTH(j.tanggal_posting)='$request->bulan'
        AND YEAR(j.tanggal_posting)='$request->tahun'
        AND pk.kode_rekening LIKE '3%'
        AND j.keterangan = 'Saldo Awal'
        AND j.id_tipe_jurnal IS NOT NULL
        GROUP BY pk.kode_rekening, pk.nama) C ON B.kode =C.kode ORDER BY B.kode"));

        return view('perubahan-ekuitas/index', compact('data', 'setting', 'bulan', 'tahun'));
    }
}
