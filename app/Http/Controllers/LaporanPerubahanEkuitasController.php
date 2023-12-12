<?php
namespace App\Http\Controllers;
use DB;
use App\Models\Perkiraan;
use App\jurnal;
use App\DetailJurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanPerubahanEkuitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-perubahan-ekuitas');
    }

    public function index ()
    {
        return view ('laporan-perubahan-ekuitas/index');
    }

    public function laporan (Request $request)
    {
        $request->validate([
            'bulan'=>'required',
            'tahun'=>'required',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        if ($bulan == null || $tahun == null)
        {
            message (false, '', 'Bulan atau Tahun kosong');
            return redirect ('laporan-perubahan-ekuitas/index');
        }

        $bulan_terpilih = new Carbon($tahun.'-'.$bulan.'-01');

        // Saldo 1 Januari (tgl awal dari bulan yg dipilih)
        $modal_saham = DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS modal_saham')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where(function ($query) {
            $query->where('kode', 'like', '32%')
                  ->orWhere('kode', 'like', '31%');
        })
        ->where('jurnal.keterangan',  'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $modal_non_saham = DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS modal_non_saham')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode', 'like', '33%')
        ->where('jurnal.keterangan',  'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $saldo_laba = DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS saldo_laba')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode', 'like', '34%')
        ->where('jurnal.keterangan',  'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $jumlah_ekuitas = $modal_saham->modal_saham + $modal_non_saham->modal_non_saham + $saldo_laba->saldo_laba;

        //penambahan modal parsarikatan
        $modal_saham_b = DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS modal_saham')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode', 'like', '32%')
        ->where('jurnal.keterangan', '<>', 'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $modal_non_saham_b = DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS modal_non_saham')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode', 'like', '33%')
        ->where('jurnal.keterangan',  '<>', 'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $saldo_laba_b = DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS saldo_laba')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode', 'like', '34%')
        ->where('jurnal.keterangan', '<>', 'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $jumlah_ekuitas_b = $modal_saham_b->modal_saham + $modal_non_saham_b->modal_non_saham + $saldo_laba_b->saldo_laba;

        $saldo_laba_c =  DetailJurnal::selectRaw('(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet)) AS saldo_laba')
        ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode', 'like', '35%')
        ->where('jurnal.keterangan', '<>', 'saldo awal')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->first();

        $laba_rugi = DB::select("SELECT (CASE WHEN laba_rugi >= 0 THEN 'LABA BERSIH' ELSE 'RUGI BERSIH' END) AS kode, laba_rugi FROM
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
        AND  MONTH(j.tanggal_posting)= '$bulan' AND YEAR(j.tanggal_posting)= '$tahun') AS laba_rugi)AS laba_rugi");

        // untuk hitung saldo 31 januari (tgl akhir dari bulan yg dipilih)

        $saldo_akhir = Perkiraan::selectRaw("SUM(debet) + SUM(kredit) +
        ((SELECT (SELECT

        (SUM(A.nominal)-
        (SELECT  IFNULL ( (A.nominal),0 )  FROM (SELECT SUBSTR(kode, 1, 2) AS kode,(SUM(d.debet)- SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON d.id_jurnal
        WHERE kode LIKE '43%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 2) )A
        RIGHT JOIN
        (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=1 AND kode LIKE '43%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL ((A.nominal),0) FROM (SELECT SUBSTR(kode, 1, 2) AS kode,(SUM(d.debet)- SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON d.id_jurnal
        WHERE kode LIKE '42%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 2) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=1 AND kode LIKE '42%')B ON B.kode=A.kode)) AS NOMINAL
        FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal  FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id WHERE kode LIKE '41%'
        GROUP BY SUBSTR(kode, 1, 3) )A
        LEFT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=2 AND fungsi=4)B ON B.kode=A.kode)
        -
        (SELECT(SELECT
        (SELECT dj.debet AS PERSEDIAAN_AWAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1141 AND j.keterangan='Saldo Awal' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun')
        +
        (SELECT SUM(A.nominal) AS NOMINAL FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal  FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '521%' AND j.id_tipe_jurnal IN (1,2,3,4,5,6) AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '521%')B ON B.kode=A.kode)
        +
        (SELECT  IFNULL(SUM(A.nominal),0)  FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.kredit) - SUM(d.debet)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '522%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '522%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0)  AS RETUR FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.kredit) - SUM(d.debet)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '523%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4))A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '523%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0) AS POTONGAN_PEMBELIAN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.kredit) - SUM(d.debet)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '524%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4))A
        RIGHT JOIN  (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '524%')B ON B.kode=A.kode) AS Persediaan_Obat_HV_Bersih)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS AMPRAHAN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.debet) - SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '525%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4))A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '525%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0) AS RESEP_KARYAWAN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.debet) - SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '526%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4))A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '526%')B ON B.kode=A.kode)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS MUTASI_KARYAWAN FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        (SUM(d.debet) - SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '527%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4))A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '527%')B ON B.kode=A.kode)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS KERUGIAN_OBAT_RUSAK FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        (SUM(d.debet) - SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '528%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 4))A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE kode LIKE '52%')B ON B.kode=A.kode)
        -
        (SELECT  IFNULL(SUM(A.nominal),0) AS PERSEDIAAN_AKHIR FROM (SELECT p.kode AS KODE, dj.debet AS NOMINAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        RIGHT JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1141 AND j.id_tipe_jurnal=7 AND dj.debet > 0 AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun') A
        RIGHT JOIN (SELECT nama , 'Persediaan Akhir' AS nama2 , kode FROM perkiraan WHERE kode=1141) B ON B.kode=A.kode)
        +
        (SELECT  IFNULL(SUM(A.nominal),0) AS HPP_PELAYANAN_MEDIS FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '51%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=2 AND fungsi=5 AND kode LIKE '51%')B ON B.kode=A.kode)
        +
        (SELECT
        (SELECT IFNULL(SUM(A.nominal),0) AS PERSEDIAAN_KANTIN FROM
        (SELECT p.kode AS KODE, p.nama AS PERKIRAAN, dj.debet AS NOMINAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1142 AND j.keterangan='Saldo Awal' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun') A )
        +
        (SELECT  IFNULL(SUM(A.nominal),0) AS PEMBELIAN_KANTIN FROM (SELECT SUBSTR(kode, 1, 4) AS kode,
        (SUM(d.debet) - SUM(d.kredit)) AS nominal  FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '5341%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 5) )A)
        -
        ((SELECT IFNULL(SUM(A.nominal),0) AS PERSEDIAAN_AKHIR FROM (SELECT p.kode AS KODE, dj.debet AS NOMINAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        RIGHT JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode=1142 AND j.id_tipe_jurnal=7 AND dj.debet > 0  AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun') A
        RIGHT JOIN (SELECT nama , 'Persediaan Akhir' AS nama2 , kode FROM perkiraan WHERE kode=1142) B ON B.kode=A.kode))
        -
        (SELECT  IFNULL(SUM(dj.debet) ,0)AS PENUNJANG_RS FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        RIGHT JOIN  perkiraan p ON p.id=dj.id_perkiraan
        WHERE fungsi=5 AND LEVEL = 2 AND kode LIKE '53%' AND kode != 534 AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun')
        AS HPP_PENUNJ_RS) AS JUMLAH_HARGA_POKOK_USH )))
        +
        (SELECT
        (SELECT IFNULL((A.nominal),0) AS PEND_LUAR_USAHA  FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        (SUM(d.kredit))- (SUM(d.debet)) AS nominal  FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '71%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=2 AND kode LIKE '71%')B ON B.kode=A.kode)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS BIAYA_LUAR_USHA FROM (SELECT SUBSTR(kode, 1, 3) AS kode,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal  FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '72%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 3) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=1 AND kode LIKE '72%')B ON B.kode=A.kode) AS NOMINAL)
        -
        (SELECT IFNULL(SUM(A.nominal),0) AS NOMINAL FROM (SELECT SUBSTR(kode, 1, 2) AS kode,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal  FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal j ON j.id = d.id_jurnal
        WHERE kode LIKE '8%' AND MONTH(tanggal_posting) ='$bulan' AND YEAR(tanggal_posting) = '$tahun'
        GROUP BY SUBSTR(kode, 1, 2) )A
        RIGHT JOIN (SELECT kode,nama FROM perkiraan p1 WHERE LEVEL=0 AND kode LIKE '8%')B ON B.kode=A.kode) AS total")
        ->where('fungsi', 3)
        ->first();

        return view ('laporan-perubahan-ekuitas/index', compact('modal_saham', 'modal_non_saham', 'saldo_laba', 'jumlah_ekuitas',
        'modal_saham_b', 'modal_non_saham_b', 'saldo_laba_b', 'jumlah_ekuitas_b', 'saldo_laba_c', 'laba_rugi', 'saldo_akhir', 'bulan_terpilih'));
    }
}
