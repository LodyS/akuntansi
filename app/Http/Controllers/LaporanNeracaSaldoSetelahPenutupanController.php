<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class LaporanNeracaSaldoSetelahPenutupanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-neraca-saldo-setelah-penyesuaian');
    }

    public function index ()
    {
        return view ('laporan-neraca-saldo-setelah-penutupan/index');
    }

    public function laporan (Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulan_indonesia = bulan($bulan);

        if ($bulan == null || $tahun == null)
        {
            message('', 'Bulan atau tahun kosong, silahkan di isi');
            return redirect ('laporan-neraca/index');
        }

        $neraca = DB::select("
        SELECT
        A.kode, A.perkiraan,
        CASE
        WHEN ((IFNULL ( (A.DEBIT),0 )) > (IFNULL ( (A.KREDIT),0 ))) THEN
        CAST(ABS(((IFNULL ( (A.DEBIT),0 ))-(IFNULL ( (A.KREDIT),0 )) ))AS SIGNED) ELSE 0
        END AS debet,

        CASE
        WHEN ((IFNULL  ((A.DEBIT),0 )) < (IFNULL ( (A.KREDIT),0 ))) THEN
        CAST(ABS(((IFNULL ( (A.DEBIT),0 ))-(IFNULL ( (A.KREDIT),0 )) )) AS SIGNED) ELSE CAST(0 AS SIGNED)
        END AS kredit

        FROM (SELECT p.kode, p.nama AS PERKIRAAN , p.fungsi, SUM(dj.debet) AS DEBIT , SUM(dj.kredit) AS KREDIT FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan
        where month(tanggal_posting) ='$bulan' and year(tanggal_posting)='$tahun'
        GROUP BY dj.id_perkiraan ORDER BY p.kode) A");

        return view ('laporan-neraca-saldo-setelah-penutupan/index', compact('neraca', 'bulan_indonesia', 'tahun'));
    }
}
