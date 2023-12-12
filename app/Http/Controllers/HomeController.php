<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Datetime;
//use App\Models\Jurnal;
use App\jurnal;
use Illuminate\Support\Facades\DB;
use App\Models\SettingPerusahaan;
use Carbon\Carbon;
use App\Models\Notification;
date_default_timezone_set("Asia/Jakarta");

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $setting = SettingPerusahaan::select('*')->first();
        $tahun = date('Y');
        $bulan = date('m');
        $aset = collect(DB::select("SELECT SUM(dj.debet) - SUM(dj.kredit) AS aset FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=pk.id
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Aktiva' AND j.status=2
        AND MONTH(j.tanggal_posting)=$bulan
        AND YEAR(j.tanggal_posting)=$tahun"));

        $totalAset = $aset->sum('aset');

        $passiva = collect(DB::select("SELECT SUM(dj.debet) - SUM(dj.kredit)AS passiva FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=pk.id
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Passiva' AND j.status=2
        AND MONTH(j.tanggal_posting)=$bulan
        AND YEAR(j.tanggal_posting)=$tahun"));

        $totalPassiva = $passiva->sum('passiva');

        $bulan_huruf = bulan($bulan);

        $laba = collect(DB::select("SELECT CASE
        WHEN A.bulan =1 THEN 'Januari'
        WHEN A.bulan =2 THEN 'Febuari'
        WHEN A.bulan =3 THEN 'Maret'
        WHEN A.bulan =4 THEN 'April'
        WHEN A.bulan =5 THEN 'Mei'
        WHEN A.bulan =6 THEN 'Juni'
        WHEN A.bulan =7 THEN 'Juli'
        WHEN A.bulan =8 THEN 'Agustus'
        WHEN A.bulan =9 THEN 'September'
        WHEN A.bulan =10 THEN 'Oktober'
        WHEN A.bulan =11 THEN 'November'
        WHEN A.bulan =12 THEN 'Desember'
        END AS bulan, A.bulan as month, ifnull(A.total_biaya,0) as total_biaya, ifnull(B.total_pendapatan,0) as total_pendapatan
        FROM
        (SELECT MONTH(tanggal_posting) AS bulan, (SUM(detail_jurnal.debet) + SUM(detail_jurnal.kredit)) AS total_biaya FROM jurnal
        JOIN detail_jurnal  ON detail_jurnal.id_jurnal=jurnal.id
        JOIN perkiraan  ON perkiraan.id=detail_jurnal.id_perkiraan
        AND YEAR(tanggal_posting)='$tahun'
        AND perkiraan.kode_rekening LIKE '5%' OR perkiraan.kode_rekening LIKE '7%'
        GROUP BY MONTH(tanggal_posting))A

        LEFT JOIN
        (SELECT MONTH(tanggal_posting) AS bulan, (SUM(detail_jurnal.kredit) + SUM(detail_jurnal.debet)) AS total_pendapatan FROM jurnal
        JOIN detail_jurnal ON detail_jurnal.id_jurnal=jurnal.id
        JOIN perkiraan  ON perkiraan.id=detail_jurnal.id_perkiraan
        AND YEAR(tanggal_posting)='$tahun'
        AND perkiraan.kode_rekening LIKE '4%' OR  perkiraan.kode_rekening LIKE '6%'
        GROUP BY MONTH(tanggal_posting)) B ON B.bulan = A.bulan"));

        $profit = collect(DB::select("SELECT (SUM(dj.kredit) + SUM(dj.debet)) AS total_biaya FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        WHERE MONTH(tanggal_posting)=$bulan
        AND YEAR(tanggal_posting)=$tahun
        AND (pk.kode_rekening LIKE '6%' OR pk.kode_rekening LIKE '4%')"));

        $totalProfit = $profit->sum('total_biaya');

        $res[] = ['Bulan', 'Total Biaya', 'Total Pendapatan'];

        foreach ($laba as $key =>$data){
            $res[++$key] = [$data->bulan, (int)$data->total_biaya, (int)$data->total_pendapatan];
        }

        $shortLiabilitas = DB::table('jurnal')
        ->selectRaw("'Short Term Liability' AS nama, ifnull(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet),0) AS nominal")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode_rekening', 'like', '2.1%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun);

        $liab = Jurnal::selectRaw("'Long Term Liability' AS nama, ifnull(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet),0) AS nominal")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode_rekening', 'like', '2.2%')
        ->whereMonth('tanggal_posting', $bulan)
        ->whereYear('tanggal_posting', $tahun)
        ->unionAll($shortLiabilitas)
        ->get();

        $datapoints = [];

        foreach($liab as $l)
        {
            $datapoints[] =
            [
                'name'=>$l['nama'],
                'y'=>floatval($l['nominal'])
            ];
        } // untuk query harus menggunakan model untuk mendapatkan data grafik liabiitas karena mendukung collection

        return view('home')
        ->with('data', json_encode($datapoints))
        ->with('totalAset', $totalAset)
        ->with('totalPassiva', $totalPassiva)
        ->with('bulan_huruf', $bulan_huruf)
        ->with('tahun', $tahun)
        ->with('setting', $setting)
        ->with('totalProfit', $totalProfit)
        ->with('laba', json_encode($res));
   }

    public function search(Request $request)
    {
        $setting = SettingPerusahaan::select('*')->first();
        $tahun = date('Y');
        $bulan = date('m');
        $month = $request->bulan;
        $year = $request->tahun;

        $cek = DB::table('jurnal')->whereYear('tanggal_posting', $year)->first();

        if($cek== null){
            message(false, '', 'Data tidak ditemukan');
            return back()->with('danger', 'Data laba tidak ditemukan');
        }

        $profit = collect(DB::select("SELECT (SUM(dj.kredit) + SUM(dj.debet)) AS total_biaya FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        WHERE MONTH(tanggal_posting)=$bulan
        AND YEAR(tanggal_posting)=$tahun
        AND (pk.kode_rekening LIKE '6%' OR pk.kode_rekening LIKE '4%')"));

        $totalProfit = $profit->sum('total_biaya');

        $aset = collect(DB::select("SELECT SUM(dj.debet) - SUM(dj.kredit) AS aset FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=pk.id
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Aktiva' AND j.status=2
        AND MONTH(j.tanggal_posting)=$bulan
        AND YEAR(j.tanggal_posting)=$tahun"));

        $totalAset = $aset->sum('aset');

        $passiva = collect(DB::select("SELECT SUM(dj.debet) - SUM(dj.kredit)AS passiva FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=pk.id
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Passiva' AND j.status=2
        AND MONTH(j.tanggal_posting)=$bulan
        AND YEAR(j.tanggal_posting)=$tahun"));

        $totalPassiva = $passiva->sum('passiva');

        $bulan_huruf = bulan($bulan);

        $laba = collect(DB::select("SELECT CASE
        WHEN A.bulan =1 THEN 'Januari'
        WHEN A.bulan =2 THEN 'Febuari'
        WHEN A.bulan =3 THEN 'Maret'
        WHEN A.bulan =4 THEN 'April'
        WHEN A.bulan =5 THEN 'Mei'
        WHEN A.bulan =6 THEN 'Juni'
        WHEN A.bulan =7 THEN 'Juli'
        WHEN A.bulan =8 THEN 'Agustus'
        WHEN A.bulan =9 THEN 'September'
        WHEN A.bulan =10 THEN 'Oktober'
        WHEN A.bulan =11 THEN 'November'
        WHEN A.bulan =12 THEN 'Desember'
        END AS bulan, A.bulan as month, A.tahun, ifnull(A.total_biaya,0) as total_biaya, ifnull(B.total_pendapatan,0) as total_pendapatan
        FROM
        (SELECT MONTH(tanggal_posting) AS bulan, YEAR(tanggal_posting) AS tahun, (SUM(detail_jurnal.debet) + SUM(detail_jurnal.kredit)) AS total_biaya FROM jurnal
        JOIN detail_jurnal  ON detail_jurnal.id_jurnal=jurnal.id
        JOIN perkiraan  ON perkiraan.id=detail_jurnal.id_perkiraan
        AND YEAR(tanggal_posting)='$year'
        AND perkiraan.kode_rekening LIKE '5%' OR perkiraan.kode_rekening LIKE '7%'
        GROUP BY MONTH(tanggal_posting))A

        LEFT JOIN
        (SELECT MONTH(tanggal_posting) AS bulan, YEAR(tanggal_posting) AS tahun, (SUM(detail_jurnal.kredit) + SUM(detail_jurnal.debet)) AS total_pendapatan FROM jurnal
        JOIN detail_jurnal ON detail_jurnal.id_jurnal=jurnal.id
        JOIN perkiraan  ON perkiraan.id=detail_jurnal.id_perkiraan
        AND YEAR(tanggal_posting)='$year'
        AND perkiraan.kode_rekening LIKE '4%' OR  perkiraan.kode_rekening LIKE '6%'
        GROUP BY MONTH(tanggal_posting)) B ON B.bulan = A.bulan where A.tahun ='$year'"));

        $res[] = ['Bulan', 'Total Biaya', 'Total Pendapatan'];

        foreach ($laba as $key =>$data):
            $res[++$key] = [$data->bulan, (int)$data->total_biaya, (int)$data->total_pendapatan];
        endforeach;

        $shortLiabilitas = DB::table('jurnal')
        ->selectRaw("'Short Term Liability' AS nama, ifnull(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet),0) AS nominal")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode_rekening', 'like', '2.1%')
        ->whereMonth('tanggal_posting', $month)
        ->whereYear('tanggal_posting', $tahun);

        $liab = Jurnal::selectRaw("'Long Term Liability' AS nama, ifnull(SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet),0) AS nominal")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->where('kode_rekening', 'like', '2.2%')
        ->whereMonth('tanggal_posting', $month)
        ->whereYear('tanggal_posting', $tahun)
        ->unionAll($shortLiabilitas)
        ->get();

        $datapoints = [];

        foreach($liab as $l):
            
            $datapoints[] = [
                'name'=>$l['nama'],
                'y'=>floatval($l['nominal']),
                'nominal'=>'Rp.'.' '.number_format($l['nominal'])
            ];

        endforeach; // untuk query harus menggunakan model untuk mendapatkan data grafik liabiitas

        return view('home')
        ->with('data', json_encode($datapoints))
        ->with('totalAset', $totalAset)
        ->with('totalPassiva', $totalPassiva)
        ->with('bulan_huruf', $bulan_huruf)
        ->with('tahun', $tahun)
        ->with('setting', $setting)
        ->with('totalProfit', $totalProfit)
        ->with('laba', json_encode($res));
   }
}
