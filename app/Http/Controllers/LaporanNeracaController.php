<?php

namespace App\Http\Controllers;
use DB;
use App\Models\perkiraan;
use App\jurnal;
use App\DetailJurnal;
use Illuminate\Http\Request;

class LaporanNeracaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-neraca');
    }

    public function index ()
    {
        return view ('laporan-neraca/index');
    }

    public function laporan (Request $request)
    {
        $request->validate([
            'bulan'=>'required',
            'tahun'=>'required',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bulan_indonesia = bulan($bulan);

        if ($bulan == null || $tahun == null)
        {
            message('', 'Bulan atau tahun kosong, silahkan di isi');
            return redirect ('laporan-neraca/index');
        }

        // query untuk aktiva
        $total_aktiva = DB::table('detail_jurnal')
        ->selectRaw('"TOTAL AKTIVA" AS perkiraan ,  "0" AS nominal_satu, cast(SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit)as signed) AS nominal_dua ')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('perkiraan.kode', 'like', '11%')
        ->orWhere('perkiraan.kode', 'like', '12%')
        ->orWhere('perkiraan.kode', 'like', '13%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun);

        $total_aktiva_tetap = DB::table('detail_jurnal')
        ->selectRaw('"TOTAL AKTIVA TETAP" AS perkiraan,  "0" AS nominal_satu, cast(SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit)as signed) AS nominal_dua')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->orWhere('perkiraan.kode', 'like', '12%')
        ->orWhere('perkiraan.kode', 'like', '13%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun);

        $aktiva_tetap = DB::table('detail_jurnal')
        ->selectRaw('perkiraan.nama AS perkiraan ,  cast(SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit) as signed) AS nominal_satu, "0" AS nominal_dua')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->orWhere('perkiraan.kode', 'like', '12%')
        ->orWhere('perkiraan.kode', 'like', '13%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->groupBy('detail_jurnal.id_perkiraan');

        $total_aktiva_lancar = DB::table('detail_jurnal')
        ->selectRaw('"TOTAL AKTIVA TETAP" AS perkiraan, "0" AS nominal_satu, cast(SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit)as signed) AS nominal_dua')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->orWhere('perkiraan.kode', 'like', '11%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun);

        $aktiva_lancar = DB::table('detail_jurnal')
        ->selectRaw('perkiraan.nama AS perkiraan, cast(SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit)as signed) AS nominal_satu, "0" AS nominal_dua')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->orWhere('perkiraan.kode', 'like', '11%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->groupBy('detail_jurnal.id_perkiraan')
        ->unionAll($total_aktiva_lancar)
        ->unionAll($aktiva_tetap)
        ->union($total_aktiva_tetap)
        ->union($total_aktiva)
        ->get();

        //query untuk passiva

        $passiva = DB::select
        (" SELECT perkiraan.nama AS perkiraan, CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet) AS SIGNED) AS nominal_satu, 0 AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode = '2' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY detail_jurnal.id_perkiraan

        union all

        SELECT perkiraan.nama AS perkiraan, CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet) AS SIGNED) AS nominal_satu, 0 AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '21%' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY detail_jurnal.id_perkiraan

        UNION ALL

        SELECT 'Hutang Jangka Pendek' AS perkiraan ,  0 AS nominal_satu, CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '21%' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun'

        UNION ALL

        SELECT perkiraan.nama AS perkiraan , CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) AS nominal_satu, 0 AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '22%' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY detail_jurnal.id_perkiraan

        UNION ALL

        SELECT 'Hutang Jangka Panjang' AS perkiraan,  0 AS nominal_satu, CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '22%' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun'

        UNION ALL

        SELECT 'MODAL' AS perkiraan, 0 AS nominal_satu, 0 AS nominal_dua

        UNION ALL

        SELECT perkiraan.nama AS perkiraan , CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) AS nominal_satu, 0 AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '3%' AND perkiraan.kode <> 34  AND perkiraan.kode NOT IN(341) AND
        MONTH(tanggal_posting) = $bulan AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY detail_jurnal.id_perkiraan

        UNION ALL

        SELECT perkiraan.nama AS perkiraan , CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) + ((((SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%'
        AND ( MONTH(j.tanggal_posting) =$bulan) AND (YEAR(j.tanggal_posting)='$tahun')))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '42%' AND ( MONTH(j.tanggal_posting) =$bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '43%'
        AND ( MONTH(j.tanggal_posting)=  $bulan) AND (YEAR(j.tanggal_posting)='$tahun')))
        -
        (SELECT
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND
        (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun')))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%' AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%' AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%' AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun') ))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0 AND
        (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun') ))
        +
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND
        (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun') )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun') )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tahun-02-01' AND '$tahun-02-28') )))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND  (MONTH(j.tanggal_posting) = $bulan) AND (YEAR(j.tanggal_posting)='$tahun')))))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '6%'AND  (MONTH(j.tanggal_posting)= $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '71%'
        AND  (MONTH(j.tanggal_posting)= $bulan) AND (YEAR(j.tanggal_posting)='$tahun'))
        +
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '72%'
        AND  (MONTH(j.tanggal_posting)= $bulan) AND (YEAR(j.tanggal_posting)='$tahun')) AS nominal_satu, 0 AS nominal_dua
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode = '34'  AND perkiraan.kode NOT IN(341) AND MONTH(tanggal_posting) = $bulan AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY detail_jurnal.id_perkiraan

        UNION ALL

        SELECT perkiraan.nama AS perkiraan, CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) + ((SELECT (SELECT
        (SUM(A.nominal)-(
        SELECT  IFNULL ((A.nominal),0)  FROM (SELECT SUBSTR(kode, 1, 2) AS kode, CAST(SUM(d.debet)- SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '43%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 2) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=1 AND kode LIKE '43%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL ((A.nominal),0)  FROM (SELECT SUBSTR(kode, 1, 2) AS kode, CAST(SUM(d.debet)- SUM(d.kredit) AS SIGNED) AS nominal
        FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '42%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 2) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=1 AND kode LIKE '42%')B ON B.kode=A.kode)) AS NOMINAL
        FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        CAST(SUM(d.kredit)- SUM(d.debet) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '41%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        LEFT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=2 AND fungsi=4)B ON B.kode=A.kode)
        -
        (SELECT(SELECT (SELECT dj.debet AS PERSEDIAAN_AWAL FROM jurnal
        JOIN detail_jurnal dj ON dj.id_jurnal= jurnal.id
        JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1141 AND jurnal.keterangan='Saldo Awal'
        AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun')
        +
        (SELECT SUM(A.nominal) AS NOMINAL FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.debet)- SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id=d.id_jurnal
        WHERE kode LIKE '521%' AND  jurnal.id_tipe_jurnal IN (1,2,3,4,5,6) AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '521%')B ON B.kode=A.kode)
        +
        (SELECT  IFNULL(SUM(A.nominal),0)  FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.kredit) - SUM(d.debet) AS SIGNED ) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '522%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '522%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0)  AS RETUR FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.kredit) - SUM(d.debet) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '523%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '523%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0) AS POTONGAN_PEMBELIAN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.kredit) - SUM(d.debet) AS SIGNED) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '524%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '524%')B ON B.kode=A.kode) AS Persediaan_Obat_HV_Bersih)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS AMPRAHAN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.debet) - SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '525%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A RIGHT JOIN
        (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '525%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0) AS RESEP_KARYAWAN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.debet) - SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '526%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '526%')B ON B.kode=A.kode)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS MUTASI_KARYAWAN FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        CAST(SUM(d.debet) - SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '527%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '527%')B ON B.kode=A.kode)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS KERUGIAN_OBAT_RUSAK FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        CAST(SUM(d.debet) - SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '528%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '529%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0) AS PERSEDIAAN_AKHIR FROM (SELECT p.kode AS KODE, dj.debet AS NOMINAL  FROM jurnal
        JOIN detail_jurnal dj ON dj.id_jurnal= jurnal.id
        RIGHT JOIN  perkiraan p ON p.id= dj.id_perkiraan
        WHERE kode=1141 AND  jurnal.id_tipe_jurnal=7 AND dj.debet > 0 AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun') A
        RIGHT JOIN  (SELECT nama , 'Persediaan Akhir' AS nama2 , kode FROM perkiraan WHERE kode=1141) B ON B.kode=A.kode)
        +
        (SELECT  IFNULL(SUM(A.nominal),0) AS HPP_PELAYANAN_MEDIS FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        CAST(SUM(d.debet)- SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '51%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=2 AND fungsi=5 AND kode LIKE '51%')B ON B.kode=A.kode)
        +
        (SELECT
        (SELECT IFNULL(SUM(A.nominal),0) AS PERSEDIAAN_KANTIN FROM
        (SELECT p.kode AS KODE, p.nama AS perkiraan, dj.debet AS NOMINAL FROM jurnal
        JOIN detail_jurnal dj ON dj.id_jurnal= jurnal.id
        JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1142 AND jurnal.keterangan='Saldo Awal' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun') A)
        +
        (SELECT  IFNULL(SUM(A.nominal),0) AS PEMBELIAN_KANTIN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        CAST(SUM(d.debet) - SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '5341%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 5) )A)
        -
        ((SELECT IFNULL(SUM(A.nominal),0) AS PERSEDIAAN_AKHIR FROM (SELECT p.kode AS KODE, dj.debet AS NOMINAL  FROM jurnal
        JOIN detail_jurnal dj ON dj.id_jurnal= jurnal.id
        RIGHT JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1142 AND  jurnal.id_tipe_jurnal=7 AND dj.debet > 0 AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun') A
        RIGHT JOIN  (SELECT nama , 'Persediaan Akhir' AS nama2 , kode FROM perkiraan WHERE kode=1142) B ON B.kode=A.kode))
        -
        (SELECT  IFNULL(SUM(dj.debet) ,0)AS PENUNJANG_RS FROM jurnal
        JOIN detail_jurnal dj ON dj.id_jurnal= jurnal.id
        RIGHT JOIN  perkiraan p ON p.id=dj.id_perkiraan WHERE fungsi=5 AND LEVEL = 2 AND kode LIKE '53%' AND kode != 534)
        AS HPP_PENUNJ_RS)
        AS JUMLAH_HARGA_POKOK_USH )))
        +
        (SELECT
        (SELECT IFNULL((A.nominal),0) AS PEND_LUAR_USAHA  FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        cast(SUM(d.kredit) as signed)- cast(SUM(d.debet)as signed ) AS nominal  FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal ON  jurnal.id = d.id_jurnal
        WHERE kode LIKE '71%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=2 AND kode LIKE '71%')B ON B.kode=A.kode)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS BIAYA_LUAR_USHA FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        CAST(SUM(d.debet)- SUM(d.kredit) AS SIGNED) AS nominal  FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal ON jurnal.id = d.id_jurnal
        WHERE kode LIKE '72%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=1 AND kode LIKE '72%')B ON B.kode=A.kode) AS NOMINAL)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS NOMINAL FROM
        (SELECT SUBSTR(kode, 1, 2) AS kode, CAST(SUM(d.debet)- SUM(d.kredit)AS SIGNED) AS nominal  FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal ON jurnal.id = d.id_jurnal
        WHERE kode LIKE '8%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 2) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=0 AND kode LIKE '8%')B ON B.kode=A.kode) AS nominal_satu, 0 AS 'nominal_dua'
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode=341 AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY detail_jurnal.id_perkiraan

        UNION ALL

        SELECT 'TOTAL MODAL' as perkiraan, 0 as nominal_satu, nominal_dua FROM
        (SELECT 'PENDAPATAN KOTOR' AS kode, '' AS perkiraan,
        ((((SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%' OR kode=4
        AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '42%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '43%'
        AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND
        MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0 AND
       MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        +
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND
        MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '6%'AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun')
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '71%'
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun')
        +
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '72%'
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun')
        +
	    (SELECT CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) AS nominal
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '3%' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun')
        AS nominal_dua) AS nominal_dua

        UNION ALL

        SELECT 'TOTAL PASSIVA' as perkiraan, 0 as nominal_satu, nominal_dua FROM
        (SELECT 'PENDAPATAN KOTOR' AS kode, '' AS perkiraan,
        ((((SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%' OR kode=4
        AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '42%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '43%'
        AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND
        MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%' AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0 AND
       MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))
        +
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND
        MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' )))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun' ))))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '6%'AND MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun')
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '71%'
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun')
        +
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '72%'
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun')
        +
	    (SELECT CAST(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet)AS SIGNED) AS nominal
        FROM detail_jurnal
        JOIN jurnal  ON jurnal.id = detail_jurnal.id_jurnal
        JOIN perkiraan perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        WHERE perkiraan.kode LIKE '3%' OR perkiraan.kode LIKE '2%' AND MONTH(tanggal_posting) = '$bulan' AND YEAR(tanggal_posting) = '$tahun')
        AS nominal_dua) AS nominal_dua

        ");

        return view ('laporan-neraca/index', compact('aktiva_lancar', 'bulan_indonesia', 'tahun', 'passiva'));
    }
}
