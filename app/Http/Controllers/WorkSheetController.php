<?php

namespace App\Http\Controllers;
use DB;
use App\Http\Requests\ValidasiTanggal;
use App\Models\SettingPerusahaan;
use Illuminate\Http\Request;

class WorkSheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-worksheet');
    }

    public function index ()
    {
        $setting = SettingPerusahaan::select('nama')->first();
        return view ('worksheet/index', compact('setting'));
    }

    public function laporan (Request $request)
    {

        $data = collect(DB::select("SELECT A.kode_rekening, A.PERKIRAAN AS Rekening, A.fungsi AS Fungsi,
        IFNULL ((A.DEBIT),0) AS DEBET,
        IFNULL ((A.KREDIT),0) AS KREDIT,
        IFNULL ((B.DEBIT),0) AS DEBET_ADJ,
        IFNULL ((B.KREDIT),0 ) AS KREDIT_ADJ,

        CASE
        WHEN ((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0 )) > (IFNULL ((A.KREDIT),0 ))+( IFNULL ((B.KREDIT),0 ))) THEN
        ABS (((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0 )) - (IFNULL ((A.KREDIT),0 ))-( IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS debit_after_adj,

        CASE
        WHEN ((IFNULL ( (A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) < (IFNULL ((A.KREDIT),0)) + (IFNULL ((B.KREDIT),0))) THEN
        ABS(((IFNULL ( (A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0 )) - (IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS kredit_after_adj,

        CASE
        WHEN ((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT),0 )) > (IFNULL ((A.KREDIT),0)) + (IFNULL ((B.KREDIT),0 )) AND
        (A.FUNGSI = 4 OR A.FUNGSI = 5 OR  A.FUNGSI = 6 OR A.FUNGSI = 7 OR A.FUNGSI = 8)) THEN
        ABS(((IFNULL ( (A.DEBIT),0 )) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0)) - (IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS debet_laba_rugi,

        CASE WHEN ((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT), 0)) < (IFNULL ((A.KREDIT), 0)) + (IFNULL ((B.KREDIT),0))  AND
        (A.FUNGSI = 4 OR A.FUNGSI = 5 OR  A.FUNGSI = 6 OR A.FUNGSI = 7 OR A.FUNGSI = 8)) THEN
        ABS(((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0 )) - (IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS kredit_laba_rugi,

        CASE WHEN ((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT), 0)) > (IFNULL ((A.KREDIT), 0)) + (IFNULL ((B.KREDIT), 0))
        AND (A.FUNGSI = 1 OR A.FUNGSI = 2 OR  A.FUNGSI = 3)) THEN
        ABS(((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0 )) - (IFNULL ((B.KREDIT),0)))) ELSE 0 END AS debet_neraca,

        CASE WHEN ((IFNULL ((A.DEBIT), 0)) + (IFNULL ((B.DEBIT),0)) < (IFNULL ((A.KREDIT),0 )) + (IFNULL ((B.KREDIT), 0))
        AND (A.FUNGSI = 1 OR A.FUNGSI = 2 OR  A.FUNGSI = 3)) THEN
        ABS(((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT), 0)) - (IFNULL ((A.KREDIT), 0)) - (IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS kredit_neraca

        FROM (SELECT p.kode_rekening, p.nama AS PERKIRAAN , p.fungsi, SUM(dj.debet) AS DEBIT , SUM(dj.kredit) AS KREDIT FROM jurnal j
        LEFT JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        LEFT JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE j.keterangan='Saldo Awal' OR j.id_tipe_jurnal IN(1,2,3,4,5,6) and tanggal_posting between
        '$request->tanggal_mulai' and '$request->tanggal_selesai'
        GROUP BY dj.id_perkiraan ORDER BY p.kode_rekening) A
        LEFT JOIN (SELECT p.kode_rekening , p.nama AS PERKIRAAN , SUM(dj.debet) AS DEBIT , SUM(dj.kredit) AS KREDIT FROM jurnal j
        LEFT JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        LEFT JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE j.id_tipe_jurnal =7 and
        tanggal_posting between '$request->tanggal_mulai' and '$request->tanggal_selesai'
        GROUP BY dj.id_perkiraan ORDER BY p.kode_rekening) B ON B.KODE_REKENING=A.KODE_REKENING

        UNION

        SELECT B.kode_rekening, B.PERKIRAAN AS Rekening, B.fungsi AS fungsi,
        IFNULL ((A.DEBIT),0) AS DEBET , IFNULL((A.KREDIT),0) AS KREDIT , IFNULL((B.DEBIT),0) AS DEBET_ADJ,  IFNULL ((B.KREDIT),0) AS KREDIT_ADJ,

        CASE WHEN ((IFNULL ( (A.DEBIT),0 ))+(IFNULL ((B.DEBIT),0 )) > (IFNULL ((A.KREDIT),0 )) + (IFNULL ((B.KREDIT),0 ))) THEN
        ABS(((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT), 0))-(IFNULL ((A.KREDIT),0 )) - (IFNULL ((B.KREDIT),0)))) ELSE 0 END AS debit_after_adj,

        CASE WHEN ((IFNULL ((A.DEBIT),0)) + (IFNULL ( (B.DEBIT),0 )) < (IFNULL ((A.KREDIT),0 )) + (IFNULL ((B.KREDIT),0))) THEN
        ABS(((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0)) - (IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS KREDIT_after_adj,

        CASE WHEN ((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0 )) > (IFNULL ((A.KREDIT),0 )) + (IFNULL ((B.KREDIT), 0)) AND
        (B.FUNGSI = 4 OR B.FUNGSI = 5 OR  B.FUNGSI = 6 OR B.FUNGSI = 7 OR B.FUNGSI = 8)) THEN
        ABS(((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0 )) - (IFNULL ((A.KREDIT),0 )) - (IFNULL ((B.KREDIT),0)))) ELSE 0 END AS DEBIT_L_R,


        CASE WHEN ((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) < (IFNULL ((A.KREDIT),0)) + (IFNULL ((B.KREDIT),0))  AND
        (B.FUNGSI = 4 OR B.FUNGSI = 5 OR  B.FUNGSI = 6 OR B.FUNGSI = 7 OR B.FUNGSI = 8  )  ) THEN
        ABS(((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0 )) - (IFNULL ((B.KREDIT),0)))) ELSE 0 END AS KREDIT_L_R,

        CASE WHEN ((IFNULL ((A.DEBIT),0 )) + (IFNULL ((B.DEBIT),0)) > (IFNULL ((A.KREDIT), 0)) + (IFNULL ((B.KREDIT),0)) AND
        (B.FUNGSI = 1 OR B.FUNGSI = 2 OR  B.FUNGSI = 3)) THEN
        ABS(((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT),0)) - (IFNULL ((B.KREDIT),0 )))) ELSE 0 END AS DEBIT_NERACA,

        CASE WHEN ((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT), 0)) < (IFNULL ((A.KREDIT), 0)) + (IFNULL ((B.KREDIT),0))  AND
        (B.FUNGSI = 1 OR B.FUNGSI = 2 OR  B.FUNGSI = 3   )  ) THEN
        ABS(((IFNULL ((A.DEBIT),0)) + (IFNULL ((B.DEBIT),0)) - (IFNULL ((A.KREDIT), 0)) - (IFNULL ((B.KREDIT),0)))) ELSE 0 END AS KREDIT_NERACA

        FROM (SELECT p.kode_rekening , p.nama AS PERKIRAAN , p.fungsi, SUM(dj.debet) AS DEBIT , SUM(dj.kredit) AS KREDIT FROM jurnal j
        left JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        left JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE j.keterangan='Saldo Awal' OR j.id_tipe_jurnal IN(1,2,3,4,5,6)
        and tanggal_posting between '$request->tanggal_mulai' and '$request->tanggal_selesai'
        GROUP BY dj.id_perkiraan ORDER BY p.kode_rekening) A

        RIGHT JOIN  (SELECT p.kode_rekening , p.nama AS PERKIRAAN ,p.fungsi,  SUM(dj.debet) AS DEBIT , SUM(dj.kredit) AS KREDIT FROM jurnal j
        left JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        left JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE j.id_tipe_jurnal =7 and tanggal_posting between '$request->tanggal_mulai' and '$request->tanggal_selesai'
        GROUP BY dj.id_perkiraan ORDER BY p.kode_rekening) B ON B.KODE_REKENING=A.KODE_REKENING"));

        $parsing = [
            'tanggal_mulai'=>$request->tanggal_mulai,
            'tanggal_selesai'=>$request->tanggal_selesai,
            'setting'=>SettingPerusahaan::select('nama')->first(),
            'data'=>$data,
            'total_debet'=>$data->sum('DEBET'),
            'total_kredit'=>$data->sum('KREDIT'),
            'total_debet_adj'=>$data->sum('DEBET_ADJ'),
            'total_kredit_adj'=>$data->sum('KREDIT_ADJ'),
            'total_debet_after_adj'=>$data->sum('debit_after_adj'),
            'total_kredit_after_adj'=>$data->sum('kredit_after_adj'),
            'total_debet_laba_rugi'=>$data->sum('debet_laba_rugi'),
            'total_kredit_laba_rugi'=>$data->sum('kredit_laba_rugi'),
            'total_debet_neraca'=>$data->sum('total_debet_neraca'),
            'total_kredit_neraca'=>$data->sum('total_kredit_neraca')
        ];

        return view('worksheet/index')->with($parsing);
    }
}
