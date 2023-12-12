<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SettingPerusahaan;
use Illuminate\Http\Request;

class LaporanProfitCenterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-profit-center');
    }

    public function index()
    {
        $anggaranProfit = DB::table('anggaran_profit')->get(['id', 'nama']);
        $bulan = date('m');
        $tahun = date('Y');
        $setting = SettingPerusahaan::select('nama')->first();

        $anggaran = null;

        return view('laporan-profit-center/index', compact('anggaranProfit', 'anggaran', 'setting'));
    }

    public function pencarian (Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $tanggal_awal = ($bulan <10) ? date("01-0$bulan-$tahun") : date("01-$bulan-$tahun");
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));

        $anggaranProfit = DB::table('anggaran_profit')->get(['id', 'nama']);
        $setting = SettingPerusahaan::select('nama')->first();

        $anggaran = DB::table('anggaran_profit')->select('nama')->where('id', $request->id)->first();

        //awal beban

        $bebanAnggaran = DB::table('anggaran_profit as ap')
        ->selectRaw("pk.nama AS perkiraan, ap.id AS id, apr.id AS id_apr, coalesce(cast(sum(apr.nilai/12*$bulan)as unsigned),0) AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '5%')
        ->where('ap.id', $request->id)
        ->first();

        $bebanAktual = DB::table('jurnal')
        ->selectRaw('anggaran_profit.id, anggaran_profit_by_rekening.id AS id_apr, perkiraan.nama')
        ->selectRaw('SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit) AS aktual')
        ->join('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->join('anggaran_profit_kelompok_detail', 'anggaran_profit_kelompok_detail.id_unit', 'detail_jurnal.id_unit')
        ->join('anggaran_profit_kelompok', 'anggaran_profit_kelompok.id', 'anggaran_profit_kelompok_detail.angg_profit_kelompok')
        ->join('anggaran_profit', 'anggaran_profit.id', 'anggaran_profit_kelompok.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail', 'anggaran_profit_rek_detail.id_perkiraan', 'detail_jurnal.id_perkiraan')
        ->join('anggaran_profit_by_rekening', 'anggaran_profit_by_rekening.id', 'anggaran_profit_rek_detail.id_anggaran_profit_rek')
        ->where('anggaran_profit.id', $request->id)
        ->where('perkiraan.kode_rekening','like', '5%')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun)
        ->groupBy('anggaran_profit_by_rekening.id')
        ->first();

        $totalBebanAktual = ($bebanAktual == null) ? 0 : $bebanAktual->aktual;

        $beban = DB::select("SELECT A.KETERANGAN as keterangan,

        A.anggaran,
        A.anggaran/AB.sum_anggaran as PERSEN,
        A.anggaran/12*$bulan AS anggaran_ytd,
        (A.anggaran/12*$bulan )/AB.sum_anggaran as persen_to_pendapatan,
        (B.AKTUAL_YTD ) as AKTUAL_YDT_FIX ,
        ((B.AKTUAL_YTD ))/(AB.sum_anggaran) as persen_to_pend,
        (B.AKTUAL_YTD)- (A.anggaran/12*$bulan) as variance,
        ((B.AKTUAL_YTD)- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan ) AS variance_persen
        FROM

        (SELECT  ap.id as id_ap, pk.id as ID_PK,  pk.nama as KETERANGAN, apr.id as ID,
        pk.nama as rekening, (apr.nilai) as anggaran FROM anggaran_profit ap
        join anggaran_profit_by_rekening apr on apr.id_anggaran_profit = ap.id
        join perkiraan pk on pk.id = apr.id_perkiraan
        where pk.kode_rekening like '5%'
        and ap.id='$request->id' )A


        LEFT JOIN

        (SELECT apr.id as ID, ap.id as id_ap, pk.id ID_PK,  pk.nama as KETERANGAN, sum(dj.kredit)-sum(dj.debet) AS AKTUAL_YTD  from jurnal j
        join detail_jurnal dj on dj.id_jurnal=j.id
        join anggaran_profit_kelompok_detail apkd on apkd.id_unit=dj.id_unit
        join anggaran_profit_kelompok apk on apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap on ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd on aprd.id_perkiraan= dj.id_perkiraan
        join anggaran_profit_by_rekening apr on apr.id=aprd.id_anggaran_profit_rek
        join perkiraan pk on pk.id=apr.id_perkiraan
        join unit u on u.id=dj.id_unit
        WHERE ap.id='$request->id' and apr.id_anggaran_profit=ap.id and pk.kode_rekening LIKE '5%' and month(j.tanggal_posting)='$bulan' and
        year(j.tanggal_posting)='$tahun' GROUP BY apr.id) B on A.ID_PK = B.ID_PK

        JOIN

        (SELECT  ap.id as id_ap, apr.id as ID, SUM(apr.nilai) as sum_anggaran FROM anggaran_profit ap
        join anggaran_profit_by_rekening apr on apr.id_anggaran_profit = ap.id
        join perkiraan pk on pk.id = apr.id_perkiraan
        where apr.id=(SELECT min(apr.id) FROM anggaran_profit ap
        join anggaran_profit_by_rekening apr on apr.id_anggaran_profit=ap.id
        where ap.id='$request->id'))AB ON AB.id_ap=A.id_ap


        LEFT JOIN

        (SELECT ap.id as ID, ap.id as id_ap, (sum(dj.kredit))-(sum(dj.debet))  AS AKTUAL_YTD  from jurnal j
        join detail_jurnal dj on dj.id_jurnal=j.id
        join anggaran_profit_kelompok_detail apkd on apkd.id_unit=dj.id_unit
        join anggaran_profit_kelompok apk on apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap on ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd on aprd.id_perkiraan= dj.id_perkiraan
        join anggaran_profit_by_rekening apr on apr.id=aprd.id_anggaran_profit_rek
        join perkiraan pk on pk.id=apr.id_perkiraan
        join unit u on u.id=dj.id_unit
        WHERE ap.id='$request->id' and apr.id_anggaran_profit=ap.id and pk.kode_rekening LIKE '5%' and month(j.tanggal_posting)='$bulan'
        and year(j.tanggal_posting)='$tahun') C on B.id_ap = C.ID");
        //akhir beban

        // awal pendapatan
        // hitung pendapatan total

        $pendapatanAnggaran = DB::table('anggaran_profit')
        ->selectRaw("cast(sum(anggaran_profit_by_rekening.nilai)as unsigned) AS anggaran")
        ->join('anggaran_profit_by_rekening', 'anggaran_profit_by_rekening.id_anggaran_profit', 'anggaran_profit.id')
        ->join('perkiraan', 'perkiraan.id', 'anggaran_profit_by_rekening.id_perkiraan')
        ->where('perkiraan.kode_rekening', 'like', '4%')
        ->where('perkiraan.nama', '<>', 'Pendapatan')
        ->where('anggaran_profit.id', $request->id)
        ->first();

        //dd($pendapatanAnggaran->anggaran);

        $sumAnggaran = DB::table('anggaran_profit')
        ->selectRaw('cast(coalesce(null, 1, SUM(anggaran_profit_by_rekening.nilai)) as unsigned) AS sum_anggaran')
        ->leftJoin('anggaran_profit_by_rekening', 'anggaran_profit_by_rekening.id_anggaran_profit', 'anggaran_profit.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'anggaran_profit_by_rekening.id_perkiraan')
        ->where('anggaran_profit_by_rekening.id', function($query){
            $query->selectRaw('min(anggaran_profit_by_rekening.id)')
            ->from('anggaran_profit')
            ->join('anggaran_profit_by_rekening', 'anggaran_profit_by_rekening.id_anggaran_profit', 'anggaran_profit.id');
        })
        ->where('anggaran_profit.id', $request->id)
        ->first();

        $aktualYtdPendapatan = DB::table('jurnal')
        ->selectRaw("SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet) as aktual_ytd")
        ->join('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->join('anggaran_profit_kelompok_detail', 'anggaran_profit_kelompok_detail.id_unit', 'detail_jurnal.id_unit')
        ->join('anggaran_profit_kelompok', 'anggaran_profit_kelompok.id', 'anggaran_profit_kelompok_detail.angg_profit_kelompok')
        ->join('anggaran_profit', 'anggaran_profit.id', 'anggaran_profit_kelompok.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail', 'anggaran_profit_rek_detail.id_perkiraan', 'detail_jurnal.id_perkiraan')
        ->join('anggaran_profit_by_rekening', 'anggaran_profit_by_rekening.id', 'anggaran_profit_rek_detail.id_anggaran_profit_rek')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->join('unit','unit.id', 'detail_jurnal.id_unit')
        ->where('anggaran_profit.id', $request->id)
        ->whereColumn('anggaran_profit_by_rekening.id_anggaran_profit', 'anggaran_profit.id')
        ->where('perkiraan.kode_rekening', 'like', '4%')
        ->whereMonth('jurnal.tanggal_posting', $bulan)
        ->whereYear('jurnal.tanggal_posting', $tahun)
        ->first();

        $aktual = $aktualYtdPendapatan->aktual_ytd;
        $aktualPendapatan = (is_null($aktual)) ? 0: $aktual;


        $accrualPendapatan = DB::table('jurnal')
        ->selectRaw('(SUM(detail_jurnal.kredit))-(SUM(detail_jurnal.debet)) as accrual')
        ->join('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->join('anggaran_profit_kelompok_detail', 'anggaran_profit_kelompok_detail.id_unit', 'detail_jurnal.id_unit')
        ->join('anggaran_profit_kelompok', 'anggaran_profit_kelompok.id', 'anggaran_profit_kelompok_detail.angg_profit_kelompok')
        ->join('anggaran_profit', 'anggaran_profit.id', 'anggaran_profit_kelompok.id_anggaran_profit')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->join('unit', 'unit.id', 'detail_jurnal.id_unit')
        ->where('anggaran_profit.id', $request->id)
        ->where('perkiraan.id', '407')
        ->whereMonth('jurnal.tanggal_posting', $bulan)
        ->whereYear('jurnal.tanggal_posting', $tahun)
        ->first();
        $accruall = $accrualPendapatan->accrual;

        $akrual = ($accruall ==null) ? 0 : $accruall;

        //hitung rincian

        $pendapatanAktual = DB::table('jurnal')
        ->selectRaw('anggaran_profit.id, anggaran_profit_by_rekening.id AS id_apr, perkiraan.nama')
        ->selectRaw('SUM(detail_jurnal.debet)-SUM(detail_jurnal.kredit) AS aktual')
        ->join('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->join('anggaran_profit_kelompok_detail', 'anggaran_profit_kelompok_detail.id_unit', 'detail_jurnal.id_unit')
        ->join('anggaran_profit_kelompok', 'anggaran_profit_kelompok.id', 'anggaran_profit_kelompok_detail.angg_profit_kelompok')
        ->join('anggaran_profit', 'anggaran_profit.id', 'anggaran_profit_kelompok.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail', 'anggaran_profit_rek_detail.id_perkiraan', 'detail_jurnal.id_perkiraan')
        ->join('anggaran_profit_by_rekening', 'anggaran_profit_by_rekening.id', 'anggaran_profit_rek_detail.id_anggaran_profit_rek')
        ->where('anggaran_profit.id', $request->id)
        ->where('perkiraan.kode_rekening','like', '4%')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun)
        ->groupBy('anggaran_profit_by_rekening.id')
        ->first();

        $totalPendapatanAktual = ($pendapatanAktual == null) ? 0: $pendapatanAktual->aktual;

        $totalPendapatan = DB::select("SELECT 'Pendapatan Usaha' AS KETERANGAN , A.anggaran, A.AKTUAL_YTD, A.anggaran_ytd AS anggaran_ytd,  A.AKTUAL_YTD/B.AKTUAL_YTD AS
        PERSENTASE , ((A.AKTUAL_YTD/B.AKTUAL_YTD) * C.ACCRUAL) AS ACCRUAL, A.AKTUAL_YTD - A.anggaran_ytd AS VARIANCE,
        ((A.AKTUAL_YTD - A.anggaran_ytd) /A.anggaran_ytd) *100 AS variance_persen,
        (A.AKTUAL_YTD ) + ((A.AKTUAL_YTD/B.AKTUAL_YTD) * C.ACCRUAL) AS AKTUAL_YDT_FIX FROM

        (SELECT ap.id AS ID,  SUM(dj.kredit)-SUM(dj.debet)
        AS AKTUAL_YTD, SUM(apr.nilai) AS anggaran, SUM(dj.kredit)-SUM(dj.debet) /12 *$bulan AS anggaran_ytd
        FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd ON aprd.id_perkiraan= dj.id_perkiraan
        JOIN anggaran_profit_by_rekening apr ON apr.id=aprd.id_anggaran_profit_rek
        JOIN perkiraan pk ON pk.id=apr.id_perkiraan
        JOIN unit u ON u.id=dj.id_unit
        WHERE ap.id=1 AND apr.id_anggaran_profit=ap.id AND pk.kode_rekening LIKE '4%' AND
        MONTH(j.tanggal_posting)='$bulan' AND YEAR(j.tanggal_posting)='$tahun') A

        LEFT JOIN

        (SELECT apr.id AS id_apr, ap.id, (SUM(dj.kredit))-(SUM(dj.debet)) AS AKTUAL_YTD FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd ON aprd.id_perkiraan= dj.id_perkiraan
        JOIN anggaran_profit_by_rekening apr ON apr.id=aprd.id_anggaran_profit_rek
        JOIN perkiraan pk ON pk.id=apr.id_perkiraan
        JOIN unit u ON u.id=dj.id_unit
        WHERE ap.id='$request->id' AND apr.id_anggaran_profit=ap.id AND pk.kode_rekening LIKE '4%' AND
        MONTH(j.tanggal_posting)='$bulan' AND YEAR(j.tanggal_posting)='$tahun') B ON A.ID = B.ID

        LEFT JOIN
        (SELECT ap.id, (SUM(dj.kredit))-(SUM(dj.debet)) AS ACCRUAL FROM
        jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN unit u ON u.id=dj.id_unit
        WHERE pk.id=407 AND MONTH(j.tanggal_posting)='$bulan' and ap.id='$request->id' AND YEAR(j.tanggal_posting)='$tahun') C ON A.id=C.id

        LEFT JOIN

        (SELECT ap.id AS id , apr.id AS id_apr, pk.nama, SUM(dj.kredit)-SUM(dj.debet) AS aktual fROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd ON aprd.id_perkiraan= dj.id_perkiraan
        JOIN anggaran_profit_by_rekening apr ON apr.id=aprd.id_anggaran_profit_rek
        WHERE ap.id='$request->id' AND pk.kode_rekening LIKE '4%' AND MONTH(tanggal_posting)='$bulan' AND YEAR(tanggal_posting)='$tahun') D ON D.id_apr = B.id_apr");


        $pendapatan = DB::select("SELECT  B.KETERANGAN AS KETERANGAN,
        A.anggaran,
        A.anggaran/AB.sum_anggaran AS persen,
        A.anggaran/12*$bulan AS anggaran_ytd,
        (A.anggaran/12*$bulan )/AB.sum_anggaran AS persen_to_pendapatan,
        (B.AKTUAL_YTD ) + ((B.AKTUAL_YTD/C.AKTUAL_YTD) * D.ACCRUAL) AS AKTUAL_YDT_FIX ,
        ((B.AKTUAL_YTD ) + ((B.AKTUAL_YTD/C.AKTUAL_YTD) * D.ACCRUAL))/(AB.sum_anggaran) AS persen_to_pend,
        ((B.AKTUAL_YTD ) + ((B.AKTUAL_YTD/C.AKTUAL_YTD) * D.ACCRUAL))- (A.anggaran/12*$bulan) AS variance,
        (((B.AKTUAL_YTD ) + ((B.AKTUAL_YTD/C.AKTUAL_YTD) * D.ACCRUAL))- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan ) AS variance_persen
        FROM

        (SELECT ap.id AS id_ap, apr.id AS ID, pk.nama AS rekening, (apr.nilai) AS anggaran FROM anggaran_profit ap
        JOIN anggaran_profit_by_rekening apr ON apr.id_anggaran_profit = ap.id
        JOIN perkiraan pk ON pk.id = apr.id_perkiraan
        WHERE pk.kode_rekening LIKE '4%' AND ap.id='$request->id') A

        JOIN

        (SELECT ap.id AS id_ap, apr.id AS ID, SUM(apr.nilai) AS sum_anggaran FROM anggaran_profit ap
        JOIN anggaran_profit_by_rekening apr ON apr.id_anggaran_profit = ap.id
        JOIN perkiraan pk ON pk.id = apr.id_perkiraan
        WHERE apr.id=(SELECT MIN(apr.id) FROM anggaran_profit ap
        JOIN anggaran_profit_by_rekening apr ON apr.id_anggaran_profit=ap.id
        WHERE ap.id='$request->id')) AB ON AB.id_ap=A.id_ap

        JOIN

        (SELECT apr.id AS ID, ap.id AS id_ap, pk.nama AS KETERANGAN, SUM(dj.kredit)-SUM(dj.debet) AS AKTUAL_YTD  FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd ON aprd.id_perkiraan= dj.id_perkiraan
        JOIN anggaran_profit_by_rekening apr ON apr.id=aprd.id_anggaran_profit_rek
        JOIN perkiraan pk ON pk.id=apr.id_perkiraan
        JOIN unit u ON u.id=dj.id_unit
        WHERE ap.id='$request->id' AND apr.id_anggaran_profit=ap.id AND pk.kode_rekening LIKE '4%' AND MONTH(j.tanggal_posting)='$bulan'
        and year(j.tanggal_posting)='$tahun' GROUP BY apr.id) B ON A.ID = B.ID

        JOIN

        (SELECT ap.id AS ID, ap.id AS id_ap, (SUM(dj.kredit))-(SUM(dj.debet))  AS AKTUAL_YTD  FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN anggaran_profit_rek_detail aprd ON aprd.id_perkiraan= dj.id_perkiraan
        JOIN anggaran_profit_by_rekening apr ON apr.id=aprd.id_anggaran_profit_rek
        JOIN perkiraan pk ON pk.id=apr.id_perkiraan
        JOIN unit u ON u.id=dj.id_unit
        WHERE ap.id='$request->id' AND apr.id_anggaran_profit=ap.id AND pk.kode_rekening LIKE '4%' AND MONTH(j.tanggal_posting)='$bulan'
        and year(j.tanggal_posting)='$tahun') C ON B.id_ap = C.id_ap

        JOIN

        (SELECT ap.id ID, (SUM(dj.kredit))-(SUM(dj.debet)) AS ACCRUAL  FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN anggaran_profit_kelompok_detail apkd ON apkd.id_unit=dj.id_unit
        JOIN anggaran_profit_kelompok apk ON apk.id=apkd.angg_profit_kelompok
        JOIN anggaran_profit ap ON ap.id=apk.id_anggaran_profit
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN unit u ON u.id=dj.id_unit
        WHERE ap.id='$request->id' AND pk.id=407 AND MONTH(j.tanggal_posting)='$bulan' and year(j.tanggal_posting)='$tahun') D ON D.ID=C.ID");

        return view('laporan-profit-center/index', compact('pendapatan', 'beban','bulan', 'bebanAnggaran', 'totalBebanAktual', 'totalPendapatan',
        'anggaran', 'tanggal_awal', 'tanggal_abis', 'setting',
        'pendapatanAnggaran','totalPendapatanAktual', 'anggaranProfit', 'sumAnggaran',
        'accrualPendapatan', 'aktualYtdPendapatan', 'akrual', 'aktualPendapatan'));
    }
}
