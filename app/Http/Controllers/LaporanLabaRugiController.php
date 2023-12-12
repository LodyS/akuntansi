<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class LaporanLabaRugiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-laba-rugi');
    }

    public function index ()
    {
        return view ('laporan-laba-rugi/index');
    }

    public function labaRugi (Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;
        $request->validate([
            'tanggal_mulai'=>'required',
            'tanggal_selesai'=>'required',
        ]);

        if ($tanggal_mulai == null || $tanggal_selesai == null)
        {
            message (false, '', 'Harap isi tanggal dengan lengkap');
            return redirect ('laporan-laba-rugi/index');
        }

        $data = DB::select("SELECT 'PENDAPATAN' AS kode, '' AS perkiraan, 0 AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM perkiraan p
        WHERE fungsi=4 AND LEVEL=0

        UNION ALL

        SELECT kode, p.nama AS perkiraan,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        LEFT JOIN detail_jurnal d ON d.id_perkiraan=p.id
        LEFT JOIN jurnal j ON j.id=d.id_jurnal WHERE kode =4
        AND  ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        union all

        SELECT kode AS kode, p.nama,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        UNION ALL

        SELECT 'PENDAPATAN KOTOR' AS kode, '' AS perkiraan, (SELECT
        (SUM(d.kredit)- SUM(d.debet)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%' or kode=4
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')) AS nominal_satu, 0 AS nominal_dua, 0
        AS nominal_tiga

        UNION ALL

        SELECT kode AS kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '42%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tig FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '43%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT 'PENDAPATAN BERSIH' AS kode, '' AS perkiraaan,
        ((SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal
        FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%' or kode=4
        AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal
        FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '42%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0 )) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '43%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        AS nominal_satu, 1-1 AS nominal_dua, 1-1 AS nominal_tiga

        UNION ALL

        SELECT p.kode, p.nama AS perkiraan, 0 AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM perkiraan p WHERE fungsi=5 AND LEVEL=0

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '51%'
        AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama AS perkiraan, 0 AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p WHERE kode=52 GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama AS perkiraan, (SUM(dj.debet)- SUM(dj.kredit)) AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal'
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT 'PEMBELIAN BERSIH' AS kode , '' AS perkiraan,
        (SELECT (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') )
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') ) AS NOMINAL, 1-1 AS NOMINAL, 1-1 AS NOMINAL

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet) - SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        UNION ALL

        SELECT 'OBAT TERSEDIA UNTUK DIJUAL' AS kode,'' AS perkiraan,
        (SELECT (SUM(dj.debet)- SUM(dj.kredit)) AS NOMINAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        (SELECT (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')) AS NOMINAL)
        -
        (SELECT
        (IFNULL(SUM(d.debet),0) - IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')) AS nominal_satu, 0 AS nominal_dua, 0
        AS nominal_tiga

        UNION ALL

        SELECT p.kode AS KODE, p.nama AS perkiraan, (SUM(dj.debet)- SUM(dj.kredit)) AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT 'OBAT TERSEDIA UNTUK DIJUAL' AS kode, '' AS perkiraan, ((SELECT (SUM(dj.debet)- SUM(dj.kredit)) AS NOMINAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND
        (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        (SELECT (IFNULL(SUM(d.debet),0) - IFNULL(SUM(d.kredit),0)) FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '521%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT (IFNULL(SUM(d.debet),0) - IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '522%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0) - IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0) - IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')) AS NOMINAL)
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '525%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%'
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%'
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
        -
        (SELECT(SUM(dj.debet)- SUM(dj.kredit)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id)
        AS nominal_satu , 0 AS nominal_dua , 0 AS nominal_tiga

        UNION ALL

        SELECT p.kode, p.nama AS perkiraan, 0 AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM perkiraan p WHERE kode=53 GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama AS perkiraan, (SUM(dj.debet)- SUM(dj.kredit)) AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM jurnal j
        LEFT JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        LEFT JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE p.kode LIKE '1142%' AND j.keterangan='Saldo Awal'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        UNION ALL

        SELECT 'PEMBELIAN KANTIN BERSIH' AS kode, '' AS perkiraan,
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '5311%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '5313%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '5314%' AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga

        UNION ALL

        SELECT 'PERSEDIAAN KANTIN YANG TERSEDIA' AS kode,'' AS perkiraan,
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') )
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') ) )
        AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga

        UNION ALL

        SELECT p.kode, p.nama AS perkiraan, (SUM(dj.debet)- SUM(dj.kredit)) AS nominal_satu, 0 AS nominal_dua , 0 AS nominal_tiga FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')

        UNION ALL

        SELECT 'HPP KANTIN ' AS kode,'' AS perkiraan,
        ((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND
        (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '5311%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8)AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0 AND
        (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga

        UNION ALL

        SELECT 'TOTAL HPP ' AS kode,'' AS perkiraan,
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND
        (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan
        WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND
        (dj.debet)>0 AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
        +
        (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND
        (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        ((SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        -
        (SELECT
        (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
        AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') )))
        -
        (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') ))
        AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga

        UNION ALL


        SELECT (CASE WHEN nominal_satu >= 0 THEN 'LABA KOTOR' ELSE 'RUGI KOTOR' END) AS kode, perkiraan, nominal_satu, nominal_dua, nominal_tiga FROM
        (
            SELECT 'PENDAPATAN KOTOR' AS kode, '' AS perkiraan,
            (((SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0 )) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%' or kode=4
            AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0 )) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal
            WHERE kode LIKE '42%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0 )) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '43%'
            AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT
            (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j
            JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan
            WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            ((SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j
            JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan
            WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0 AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            +
            (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan
            WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            ((SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))))
            -
            (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
            AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            AS NOMINAL)
            AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga
        ) laba_rugi_kotor

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '6%'
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')
        GROUP BY p.id

        UNION ALL

        select 'TOTAL BIAYA' as kode, '' AS perkiraan,
        (sum(d.debet)- sum(d.kredit)) as nominal , 0 as NOMINAL, 0 AS NOMINAL from perkiraan p join detail_jurnal d on d.id_perkiraan=p.id
        JOIN jurnal j on j.id=d.id_jurnal where kode like '6%'
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')

        UNION ALL

        SELECT p.kode, p.nama AS perkiran, 0 AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p WHERE FUNGSI=7 AND LEVEL=0

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '71%'
        AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.kredit)- SUM(d.debet)) AS nominal_satu , 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '72%'
        AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT 'TOTAL BIAYA DAN PENDAPATAN DI LUAR USAHA' AS kode, '' AS perkiraan, ((SELECT
        IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0) AS nominal FROM perkiraan p
        JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '72%' AND  ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
        +
        (SELECT
        IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE kode LIKE '71%' AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))) AS
        nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE p.fungsi=8 AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') GROUP BY p.id

        UNION ALL

        SELECT p.kode, p.nama as perkiraan,
        (SUM(d.debet)- SUM(d.kredit)) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
        JOIN jurnal j ON j.id=d.id_jurnal
        WHERE p.fungsi=8 AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')

        UNION ALL


        SELECT (CASE WHEN nominal_satu >= 0 THEN 'LABA BERSIH' ELSE 'RUGI BERSIH' END) AS kode, perkiraan, nominal_satu, nominal_dua, nominal_tiga FROM
        (
            SELECT 'PENDAPATAN KOTOR' AS kode, '' AS perkiraan,
            ((((SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '41%' or kode=4
            AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal
            WHERE kode LIKE '42%' AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '43%'
            AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT
            (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.keterangan='Saldo Awal' AND
            (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            ((SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '521%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND ( j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '522%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '523%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '524%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '525%' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '526%' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '527%' AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            -
            (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS NOMINAL FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1141%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0 AND
            (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))
            +
            (((SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.keterangan='Saldo Awal' AND
            (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            ((SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5311%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5312%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5314%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '5313%'
            AND j.id_tipe_jurnal IN (1,2,3,4,5,7,8) AND (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai') )))
            -
            (SELECT (IFNULL(SUM(dj.debet),0)- IFNULL(SUM(dj.kredit),0)) AS nominal FROM jurnal j JOIN detail_jurnal dj ON dj.id_jurnal=j.id
            JOIN perkiraan p ON p.id=dj.id_perkiraan WHERE kode LIKE '1142%' AND j.id_tipe_jurnal=6 AND (dj.debet)>0
            AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')))))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0)) AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal
            WHERE kode LIKE '6%'AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            -
            (SELECT
            (IFNULL(SUM(d.debet),0)- IFNULL(SUM(d.kredit),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '71%'
            AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'))
            +
            (SELECT
            (IFNULL(SUM(d.kredit),0)- IFNULL(SUM(d.debet),0))AS nominal FROM perkiraan p JOIN detail_jurnal d ON d.id_perkiraan=p.id
            JOIN jurnal j ON j.id=d.id_jurnal WHERE kode LIKE '72%'
            AND  (j.tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')) AS nominal_satu, 0 AS nominal_dua, 0 AS nominal_tiga
        ) laba_rugi_bersih");

        return view ('laporan-laba-rugi/index', compact('data', 'tanggal_mulai', 'tanggal_selesai'));
    }
}
