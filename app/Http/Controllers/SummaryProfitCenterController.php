<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SettingPerusahaan;
use Illuminate\Http\Request;

class SummaryProfitCenterController extends Controller
{
    public function __construct()
    {
        $setting = SettingPerusahaan::select('nama')->first();
        $this->middleware('permission:read-summary-profit-center', compact('setting'));
    }

    public function index (Request $request)
    {
        $bulan = (int)date('m');
        $tahun = (int)date('Y');
        $tanggal_awal = ($bulan <10) ? date("01-0$bulan-$tahun") : date("01-$bulan-$tahun");
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));

         // Query Master
        $pendapatan_a = DB::table('anggaran_profit as ap')
        ->selectRaw("(apr.nilai) AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '4%');

        $pendapatan_b = DB::table('jurnal as j')
        ->selectRaw('pk.nama, SUM(dj.kredit)-SUM(dj.debet) AS AKTUAL_YTD')
        ->join('detail_jurnal as dj', 'dj.id_jurnal','j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail as aprd', 'aprd.id_perkiraan', 'dj.id_perkiraan')
        ->join('anggaran_profit_by_rekening as apr',  'apr.id','aprd.id_anggaran_profit_rek')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereRaw('apr.id_anggaran_profit = ap.id')
        ->where('pk.kode_rekening', 'like', '4%')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun)
        ->groupBy('apr.id');

        $pendapatan_c = DB::table('jurnal as j')
        ->selectRaw('(SUM(dj.kredit))-(SUM(dj.debet))  AS AKTUAL_YTD ')
        ->join('detail_jurnal as dj', 'dj.id_jurnal','j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail as aprd','aprd.id_perkiraan', 'dj.id_perkiraan')
        ->join('anggaran_profit_by_rekening as apr', 'apr.id', 'aprd.id_anggaran_profit_rek')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereColumn('apr.id_anggaran_profit', 'ap.id')
        ->where('pk.kode_rekening', 'like', '4%')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun);

        $pendapatan_d = DB::table('jurnal as j')
        ->selectRaw('(SUM(dj.kredit))-(SUM(dj.debet)) AS ACCRUAL ')
        ->join('detail_jurnal as dj', 'dj.id_jurnal', 'j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('perkiraan as pk', 'pk.id', 'dj.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun)
        ->where('pk.id', 407); //khusus pendapatan

         //rajal - pendapatan
        $rajalA = (clone $pendapatan_a)->where('ap.id',1);
        $rajalB = (clone $pendapatan_b)->where('ap.id',1)->get();
        $rajalC = (clone $pendapatan_c)->where('ap.id',1);
        $rajalD = (clone $pendapatan_d)->where('ap.id',1);

        $rajal_b = $rajalB->sum('AKTUAL_YTD');
        $rajal_c = $rajalC->first()->AKTUAL_YTD;
        $rajal_d = $rajalD->first()->ACCRUAL;

        $pendapatanRajal = DB::table(DB::raw("({$rajalA->toSql()}) as A"))
        ->mergeBindings($rajalA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$rajal_b') + (('$rajal_b'/'$rajal_c') * '$rajal_d') AS AKTUAL")
        ->selectRaw("((('$rajal_b') + (('$rajal_b'/'$rajal_c') * '$rajal_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $rajal_aktual = ($pendapatanRajal == null) ? 0 : $pendapatanRajal->AKTUAL;
        $rajal_target = ($pendapatanRajal == null) ? 0 : $pendapatanRajal->TARGET;

        //ranap
        $ranapA = (clone $pendapatan_a)->where('ap.id',2);
        $ranapB = (clone $pendapatan_b)->where('ap.id',2)->get();
        $ranapC = (clone $pendapatan_c)->where('ap.id',2);
        $ranapD = (clone $pendapatan_d)->where('ap.id',2);

        $ranap_b = $ranapB->sum('AKTUAL_YTD');
        $ranap_c = $ranapC->first()->AKTUAL_YTD;
        $ranap_d = $ranapD->first()->ACCRUAL;

        $pendapatanRanap = DB::table(DB::raw("({$ranapA->toSql()}) as A"))
        ->mergeBindings($ranapA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$ranap_b') + (('$ranap_b'/'$ranap_c') * '$ranap_d') AS AKTUAL")
        ->selectRaw("((('$ranap_b') + (('$ranap_b'/'$ranap_c') * '$ranap_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $ranap_aktual = ($pendapatanRanap == null) ? 0 : $pendapatanRanap->AKTUAL;
        $ranap_target = ($pendapatanRanap == null) ? 0 : $pendapatanRanap->TARGET;

        // penunjang
        $penunjangA = (clone $pendapatan_a)->where('ap.id',3);
        $penunjangB = (clone $pendapatan_b)->where('ap.id',3)->get();
        $penunjangC = (clone $pendapatan_c)->where('ap.id',3);
        $penunjangD = (clone $pendapatan_d)->where('ap.id',3);

        $penunjang_b = $penunjangB->sum('AKTUAL_YTD');
        $penunjang_c = $penunjangC->first()->AKTUAL_YTD;
        $penunjang_d = $penunjangD->first()->ACCRUAL;

        $pendapatanPenunjang = DB::table(DB::raw("({$penunjangA->toSql()}) as A"))
        ->mergeBindings($penunjangA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$penunjang_b') + (('$penunjang_b'/'$penunjang_c') * '$penunjang_d') AS AKTUAL")
        ->selectRaw("((('$penunjang_b') + (('$penunjang_b'/'$penunjang_c') * '$penunjang_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $penunjang_aktual = ($pendapatanPenunjang == null) ? 0 : $pendapatanPenunjang->AKTUAL;
        $penunjang_target = ($pendapatanPenunjang == null) ? 0 : $pendapatanPenunjang->TARGET;

         //farmasi
        $farmasiA = (clone $pendapatan_a)->where('ap.id',4);
        $farmasiB = (clone $pendapatan_b)->where('ap.id',4)->get();
        $farmasiC = (clone $pendapatan_c)->where('ap.id',4);
        $farmasiD = (clone $pendapatan_d)->where('ap.id',4);

        $farmasi_b = $farmasiB->sum('AKTUAL_YTD');
        $farmasi_c = $farmasiC->first()->AKTUAL_YTD;
        $farmasi_d = $farmasiD->first()->ACCRUAL;

        $pendapatanFarmasi = DB::table(DB::raw("({$farmasiA->toSql()}) as A"))
        ->mergeBindings($farmasiA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$farmasi_b') + (('$farmasi_b'/'$farmasi_c') * '$farmasi_d') AS AKTUAL")
        ->selectRaw("((('$farmasi_b') + (('$farmasi_b'/'$farmasi_c') * '$farmasi_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $farmasi_target = ($pendapatanFarmasi == null) ? 0 : (int)$pendapatanFarmasi->TARGET;
        $farmasi_aktual = ($pendapatanFarmasi == null) ? 0 : (int)$pendapatanFarmasi->AKTUAL;

        // query beban
        // master beban
        $beban_a = DB::table('anggaran_profit as ap')
        ->selectRaw("(apr.nilai) AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '5%');

        $beban_b = DB::table('jurnal as j')
        ->selectRaw('pk.nama, SUM(dj.kredit)-SUM(dj.debet) AS AKTUAL_YTD')
        ->join('detail_jurnal as dj', 'dj.id_jurnal','j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail as aprd', 'aprd.id_perkiraan', 'dj.id_perkiraan')
        ->join('anggaran_profit_by_rekening as apr',  'apr.id','aprd.id_anggaran_profit_rek')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereRaw('apr.id_anggaran_profit = ap.id')
        ->where('pk.kode_rekening', 'like', '5%')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun)
        ->groupBy('apr.id');

        //rajal
        $bebanRajalA = (clone $beban_a)->where('ap.id',1);
        $bebanRajalB = (clone $beban_b)->where('ap.id',1);

        $bebanRajalb = $bebanRajalB->first();
        $beban_rajal_b = ($bebanRajalb == null) ? 0 : $bebanRajalB->AKTUAL_YTD;

        $bebanRajal = DB::table(DB::raw("({$bebanRajalA->toSql()}) as A"))
        ->mergeBindings($bebanRajalA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_rajal_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_rajal_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_rajal_aktual = ($bebanRajal == null) ? 0 : (int)$bebanRajal->AKTUAL;
        $beban_rajal_target = ($bebanRajal == null) ? 0 : (int)$bebanRajal->TARGET;

        // beban ranap
        $bebanRanapA = (clone $beban_a)->where('ap.id',2);
        $bebanRanapB = (clone $beban_b)->where('ap.id',2);

        $bebanRanapb = $bebanRanapB->first();
        $beban_ranap_b = ($bebanRanapb == null) ? 0 : $bebanRanapB->AKTUAL_YTD;

        $bebanRanap = DB::table(DB::raw("({$bebanRanapA->toSql()}) as A"))
        ->mergeBindings($bebanRanapA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_ranap_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_ranap_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_ranap_aktual = ($bebanRanap == null) ? 0 : (int)$bebanRanap->AKTUAL;
        $beban_ranap_target = ($bebanRanap == null) ? 0 : (int)$bebanRanap->TARGET;

        //beban penunjang
        $bebanPenunjangA = (clone $beban_a)->where('ap.id',3);
        $bebanPenunjangB = (clone $beban_b)->where('ap.id',3);

        $bebanPenunjangb = $bebanPenunjangB->first();
        $beban_penunjang_b = ($bebanPenunjangb == null) ? 0 : $bebanPenunjangB->AKTUAL_YTD;

        $bebanPenunjang = DB::table(DB::raw("({$bebanPenunjangA->toSql()}) as A"))
        ->mergeBindings($bebanPenunjangA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_penunjang_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_penunjang_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_penunjang_aktual = ($bebanPenunjang == null) ? 0 : $bebanPenunjang->AKTUAL;
        $beban_penunjang_target = ($bebanPenunjang == null) ? 0 : $bebanPenunjang->TARGET;

         //farmasi
        $bebanFarmasiA = (clone $beban_a)->where('ap.id',4);
        $bebanFarmasiB = (clone $beban_b)->where('ap.id',4);

        $bebanFarmasib = $bebanFarmasiB->first();
        $beban_farmasi_b = ($bebanFarmasib == null) ? 0 : $bebanFarmasiB->AKTUAL_YTD;

        $bebanFarmasi = DB::table(DB::raw("({$bebanFarmasiA->toSql()}) as A"))
        ->mergeBindings($bebanFarmasiA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_farmasi_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_farmasi_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_farmasi_aktual = ($bebanFarmasi == null) ? 0 : $bebanFarmasi->AKTUAL;
        $beban_farmasi_target = ($bebanFarmasi == null) ? 0 : $bebanFarmasi->TARGET;

        // master query beban
        $bebanAnggaran = DB::table('anggaran_profit as ap')
        ->selectRaw("pk.nama AS perkiraan, ap.id AS id, apr.id AS id_apr, sum(apr.nilai/12*'$bulan') AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '5%');

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
        ->where('perkiraan.kode_rekening','like', '5%')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun);

        // master query pendapatan
        $pendapatanAnggaran = DB::table('anggaran_profit as ap')
        ->selectRaw("pk.nama AS perkiraan, ap.id AS id, apr.id AS id_apr, sum(apr.nilai/12*'$bulan') AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '4%');

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
        ->where('perkiraan.kode_rekening','like', '4%')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun);

        // variance rajal
        //beban
        $bebanRajalAnggaran = $bebanAnggaran->where('ap.id', 1)->first();
        $beban_rajal_aktual = $bebanAktual->where('anggaran_profit.id', 1)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanRajalAktual = (isset($beban_rajal_aktual)) ? $beban_rajal_aktual->aktual : 0;
        $bebanRajalAnggaran = (isset($bebanRajalAnggaran)) ? $bebanRajalAnggaran->anggaran : 0;

        //pendapatan
        $pendapatanRajalAnggaran = $pendapatanAnggaran->where('ap.id', 1)->first();
        $pendapatan_rajal_aktual = $pendapatanAktual->where('anggaran_profit.id', 1)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanRajalAktual = (isset($pendapatan_rajal_aktual)) ? $pendapatan_rajal_aktual->aktual : 0;
        $pendapatanRajalAnggaran = (isset($pendapatanRajalAnggaran)) ? $pendapatanRajalAnggaran->anggaran :0;

        //variance Ranap
        //beban
        $bebanRanapAnggaran = $bebanAnggaran->where('ap.id', 2)->first();
        $beban_ranap_aktual = $bebanAktual->where('anggaran_profit.id', 2)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanRanapAktual = (isset($beban_ranap_aktual)) ? $beban_ranap_aktual->aktual : 0;
        $bebanRanapAnggaran = (isset($bebanRanapAnggaran)) ? $bebanRanapAnggaran->anggaran :0;

        //pendapatan
        $pendapatanRanapAnggaran = $pendapatanAnggaran->where('ap.id', 2)->first();
        $pendapatan_ranap_aktual = $pendapatanAktual->where('anggaran_profit.id', 2)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanRanapAktual = (isset($pendapatan_ranap_aktual)) ? $pendapatan_ranap_aktual->aktual : 0;
        $pendapatanRanapAnggaran = (isset($pendapatanRanapAnggaran)) ? $pendapatanRanapAnggaran->anggaran :0;

        //variance farmasi
        //beban
        $bebanFarmasiAnggaran = $bebanAnggaran->where('ap.id', 3)->first();
        $beban_farmasi_aktual = $bebanAktual->where('anggaran_profit.id', 3)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanFarmasiAktual = (isset($beban_farmasi_aktual)) ? $beban_farmasi_aktual->aktual : 0;
        $bebanFarmasiAnggaran = (isset($bebanFarmasiAnggaran)) ? $bebanFarmasiAnggaran->anggaran : 0;

        //pendapatan
        $pendapatanFarmasiAnggaran = $pendapatanAnggaran->where('ap.id', 3)->first();
        $pendapatan_farmasi_aktual = $pendapatanAktual->where('anggaran_profit.id', 3)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanFarmasiAktual = (isset($pendapatan_farmasi_aktual)) ? $pendapatan_farmasi_aktual->aktual : 0;
        $pendapatanFarmasiAnggaran = (isset($pendapatanFarmasiAnggaran)) ? $pendapatanFarmasiAnggaran->anggaran : 0;

        // variance penunjang
        //beban
        $bebanPenunjangAnggaran = $bebanAnggaran->where('ap.id', 4)->first();
        $beban_penunjang__aktual = $bebanAktual->where('anggaran_profit.id', 4)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanPenunjangAktual = (isset($beban_penunjang__aktual)) ? $beban_penunjang__aktual->aktual : 0;
        $bebanPenunjangAnggaran = (isset($bebanPenunjangAnggaran)) ? $bebanPenunjangAnggaran->anggaran :0;

        //pendapatan
        $pendapatanPenunjangAnggaran = $pendapatanAnggaran->where('ap.id', 4)->first();
        $pendapatan_penunjang_aktual = $pendapatanAktual->where('anggaran_profit.id', 4)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanPenunjangAktual = (isset($pendapatan_penunjang_aktual)) ? $pendapatan_penunjang_aktual->aktual : 0;
        $pendapatanPenunjangAnggaran = (isset($pendapatanPenunjangAnggaran)) ? $pendapatanPenunjangAnggaran->anggaran :0;

        $data = [
            'pendapatanRajal'=>$pendapatanRajal,
            'pendapatanRanap'=>$pendapatanRanap,
            'pendapatanPenunjang'=>$pendapatanPenunjang,
            'pendapatanFarmasi'=>$pendapatanFarmasi,
            'bebanRajal'=>$bebanRajal,
            'bebanRanap'=>$bebanRanap,
            'bebanPenunjang'=>$bebanPenunjang,
            'bebanFarmasi'=>$bebanFarmasi,
            'bulan'=>$bulan,
            'bebanRajalAnggaran'=>$bebanRajalAnggaran,
            'bebanRajalAktual'=>$bebanRajalAktual,
            'pendapatanRajalAnggaran'=>$pendapatanRajalAnggaran,
            'pendapatanRajalAktual'=>$pendapatanRajalAktual,
            'bebanRanapAnggaran'=>$bebanRanapAnggaran,
            'bebanRanapAktual'=>$bebanRanapAktual,
            'pendapatanRanapAnggaran'=>$pendapatanRanapAnggaran,
            'pendapatanRanapAktual'=>$pendapatanRanapAktual,
            'bebanFarmasiAnggaran'=>$bebanFarmasiAnggaran,
            'bebanFarmasiAktual'=>$bebanFarmasiAktual,
            'pendapatanFarmasiAnggaran'=>$pendapatanFarmasiAnggaran,
            'pendapatanRanapAnggaran'=>$pendapatanRanapAnggaran,
            'bebanPenunjangAnggaran'=>$bebanPenunjangAnggaran,
            'bebanPenunjangAktual'=>$bebanPenunjangAktual,
            'pendapatanPenunjangAnggaran'=>$pendapatanPenunjangAnggaran,
            'pendapatanPenunjangAktual'=>$pendapatanPenunjangAktual,
            'rajal_target'=>$rajal_target,
            'rajal_aktual'=>$rajal_aktual,
            'tanggal_awal'=>$tanggal_awal,
            'tanggal_abis'=>$tanggal_abis,
            'setting'=>SettingPerusahaan::select('nama')->first(),
            'farmasi_aktual'=>$farmasi_aktual,
            'farmasi_target'=>$farmasi_target,
            'beban_rajal_aktual'=>$beban_rajal_aktual,
            'beban_rajal_target'=>$beban_rajal_target,
            'ranap_target'=>$ranap_target,
            'ranap_aktual'=>$ranap_aktual,
            'beban_farmasi_target'=>$beban_farmasi_target,
            'beban_farmasi_aktual'=>$beban_farmasi_aktual,
            'beban_penunjang_aktual'=>$beban_penunjang_aktual,
            'beban_penunjang_target'=>$beban_penunjang_target,
            'penunjang_target'=>$penunjang_target,
            'penunjang_aktual'=>$penunjang_aktual,
            'total_rajal_beban_aktual'=>$rajal_aktual - $beban_ranap_aktual,
            'beban_ranap_target'=>$beban_ranap_target,
            'beban_ranap_aktual'=>$beban_ranap_aktual
        ];

        return view ('summary-profit-center/index')->with($data);
    }

    public function pencarian (Request $request)
    {
        $bulan = (int)$request->bulan;
        $tahun = (int)$request->tahun;
        $tanggal_awal = ($bulan <10) ? date("01-0$bulan-$tahun") : date("01-$bulan-$tahun");
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));

        // Query Master
        $pendapatan_a = DB::table('anggaran_profit as ap')
        ->selectRaw("(apr.nilai) AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '4%');

        $pendapatan_b = DB::table('jurnal as j')
        ->selectRaw('pk.nama, SUM(dj.kredit)-SUM(dj.debet) AS AKTUAL_YTD')
        ->join('detail_jurnal as dj', 'dj.id_jurnal','j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail as aprd', 'aprd.id_perkiraan', 'dj.id_perkiraan')
        ->join('anggaran_profit_by_rekening as apr',  'apr.id','aprd.id_anggaran_profit_rek')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereRaw('apr.id_anggaran_profit = ap.id')
        ->where('pk.kode_rekening', 'like', '4%')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun)
        ->groupBy('apr.id');

        $pendapatan_c = DB::table('jurnal as j')
        ->selectRaw('(SUM(dj.kredit))-(SUM(dj.debet))  AS AKTUAL_YTD ')
        ->join('detail_jurnal as dj', 'dj.id_jurnal','j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail as aprd','aprd.id_perkiraan', 'dj.id_perkiraan')
        ->join('anggaran_profit_by_rekening as apr', 'apr.id', 'aprd.id_anggaran_profit_rek')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereColumn('apr.id_anggaran_profit', 'ap.id')
        ->where('pk.kode_rekening', 'like', '4%')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun);

        $pendapatan_d = DB::table('jurnal as j')
        ->selectRaw('(SUM(dj.kredit))-(SUM(dj.debet)) AS ACCRUAL ')
        ->join('detail_jurnal as dj', 'dj.id_jurnal', 'j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('perkiraan as pk', 'pk.id', 'dj.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun)
        ->where('pk.id', 407); //khusus pendapatan

        //rajal - pendapatan
        $rajalA = (clone $pendapatan_a)->where('ap.id',1);
        $rajalB = (clone $pendapatan_b)->where('ap.id',1)->get();
        $rajalC = (clone $pendapatan_c)->where('ap.id',1);
        $rajalD = (clone $pendapatan_d)->where('ap.id',1);

        $rajal_b = $rajalB->sum('AKTUAL_YTD');
        $rajal_c = $rajalC->first()->AKTUAL_YTD;
        $rajal_d = $rajalD->first()->ACCRUAL;

        $pendapatanRajal = DB::table(DB::raw("({$rajalA->toSql()}) as A"))
        ->mergeBindings($rajalA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$rajal_b') + (('$rajal_b'/'$rajal_c') * '$rajal_d') AS AKTUAL")
        ->selectRaw("((('$rajal_b') + (('$rajal_b'/'$rajal_c') * '$rajal_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $rajal_aktual = ($pendapatanRajal == null) ? 0 : $pendapatanRajal->AKTUAL;
        $rajal_target = ($pendapatanRajal == null) ? 0 : $pendapatanRajal->TARGET;

        //ranap
        $ranapA = (clone $pendapatan_a)->where('ap.id',2);
        $ranapB = (clone $pendapatan_b)->where('ap.id',2)->get();
        $ranapC = (clone $pendapatan_c)->where('ap.id',2);
        $ranapD = (clone $pendapatan_d)->where('ap.id',2);

        $ranap_b = $ranapB->sum('AKTUAL_YTD');
        $ranap_c = $ranapC->first()->AKTUAL_YTD;
        $ranap_d = $ranapD->first()->ACCRUAL;

        $pendapatanRanap = DB::table(DB::raw("({$ranapA->toSql()}) as A"))
        ->mergeBindings($ranapA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$ranap_b') + (('$ranap_b'/'$ranap_c') * '$ranap_d') AS AKTUAL")
        ->selectRaw("((('$ranap_b') + (('$ranap_b'/'$ranap_c') * '$ranap_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $ranap_aktual = ($pendapatanRanap == null) ? 0 : $pendapatanRanap->AKTUAL;
        $ranap_target = ($pendapatanRanap == null) ? 0 : $pendapatanRanap->TARGET;

        // penunjang
        $penunjangA = (clone $pendapatan_a)->where('ap.id',3);
        $penunjangB = (clone $pendapatan_b)->where('ap.id',3)->get();
        $penunjangC = (clone $pendapatan_c)->where('ap.id',3);
        $penunjangD = (clone $pendapatan_d)->where('ap.id',3);

        $penunjang_b = $penunjangB->sum('AKTUAL_YTD');
        $penunjang_c = $penunjangC->first()->AKTUAL_YTD;
        $penunjang_d = $penunjangD->first()->ACCRUAL;

        $pendapatanPenunjang = DB::table(DB::raw("({$penunjangA->toSql()}) as A"))
        ->mergeBindings($penunjangA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$penunjang_b') + (('$penunjang_b'/'$penunjang_c') * '$penunjang_d') AS AKTUAL")
        ->selectRaw("((('$penunjang_b') + (('$penunjang_b'/'$penunjang_c') * '$penunjang_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $penunjang_aktual = ($pendapatanPenunjang == null) ? 0 : $pendapatanPenunjang->AKTUAL;
        $penunjang_target = ($pendapatanPenunjang == null) ? 0 : $pendapatanPenunjang->TARGET;

        //farmasi
        $farmasiA = (clone $pendapatan_a)->where('ap.id',4);
        $farmasiB = (clone $pendapatan_b)->where('ap.id',4)->get();
        $farmasiC = (clone $pendapatan_c)->where('ap.id',4);
        $farmasiD = (clone $pendapatan_d)->where('ap.id',4);

        $farmasi_b = $farmasiB->sum('AKTUAL_YTD');
        $farmasi_c = $farmasiC->first()->AKTUAL_YTD;
        $farmasi_d = $farmasiD->first()->ACCRUAL;

        $pendapatanFarmasi = DB::table(DB::raw("({$farmasiA->toSql()}) as A"))
        ->mergeBindings($farmasiA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("('$farmasi_b') + (('$farmasi_b'/'$farmasi_c') * '$farmasi_d') AS AKTUAL")
        ->selectRaw("((('$farmasi_b') + (('$farmasi_b'/'$farmasi_c') * '$farmasi_d')) - (A.anggaran/12*'$bulan')) /(A.anggaran/12*'$bulan') AS PERSEN_VARIANCE")
        ->first();

        $farmasi_target = ($pendapatanFarmasi == null) ? 0 : (int)$pendapatanFarmasi->TARGET;
        $farmasi_aktual = ($pendapatanFarmasi == null) ? 0 : (int)$pendapatanFarmasi->AKTUAL;

        // query beban
        // master beban
        $beban_a = DB::table('anggaran_profit as ap')
        ->selectRaw("(apr.nilai) AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '5%');

        $beban_b = DB::table('jurnal as j')
        ->selectRaw('pk.nama, SUM(dj.kredit)-SUM(dj.debet) AS AKTUAL_YTD')
        ->join('detail_jurnal as dj', 'dj.id_jurnal','j.id')
        ->join('anggaran_profit_kelompok_detail as apkd', 'apkd.id_unit', 'dj.id_unit')
        ->join('anggaran_profit_kelompok as apk', 'apk.id', 'apkd.angg_profit_kelompok')
        ->join('anggaran_profit as ap', 'ap.id', 'apk.id_anggaran_profit')
        ->join('anggaran_profit_rek_detail as aprd', 'aprd.id_perkiraan', 'dj.id_perkiraan')
        ->join('anggaran_profit_by_rekening as apr',  'apr.id','aprd.id_anggaran_profit_rek')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->join('unit as u', 'u.id', 'dj.id_unit')
        ->whereRaw('apr.id_anggaran_profit = ap.id')
        ->where('pk.kode_rekening', 'like', '5%')
        ->whereMonth('j.tanggal_posting', $bulan)
        ->whereYear('j.tanggal_posting', $tahun)
        ->groupBy('apr.id');

        //rajal
        $bebanRajalA = (clone $beban_a)->where('ap.id',1);
        $bebanRajalB = (clone $beban_b)->where('ap.id',1);

        $bebanRajalb = $bebanRajalB->first();
        $beban_rajal_b = ($bebanRajalb == null) ? 0 : $bebanRajalB->AKTUAL_YTD;

        $bebanRajal = DB::table(DB::raw("({$bebanRajalA->toSql()}) as A"))
        ->mergeBindings($bebanRajalA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_rajal_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_rajal_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_rajal_aktual = ($bebanRajal == null) ? 0 : (int)$bebanRajal->AKTUAL;
        $beban_rajal_target = ($bebanRajal == null) ? 0 : (int)$bebanRajal->TARGET;

        // beban ranap
        $bebanRanapA = (clone $beban_a)->where('ap.id',2);
        $bebanRanapB = (clone $beban_b)->where('ap.id',2);

        $bebanRanapb = $bebanRanapB->first();
        $beban_ranap_b = ($bebanRanapb == null) ? 0 : $bebanRanapB->AKTUAL_YTD;

        $bebanRanap = DB::table(DB::raw("({$bebanRanapA->toSql()}) as A"))
        ->mergeBindings($bebanRanapA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_ranap_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_ranap_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_ranap_aktual = ($bebanRanap == null) ? 0 : (int)$bebanRanap->AKTUAL;
        $beban_ranap_target = ($bebanRanap == null) ? 0 : (int)$bebanRanap->TARGET;

        //beban penunjang
        $bebanPenunjangA = (clone $beban_a)->where('ap.id',3);
        $bebanPenunjangB = (clone $beban_b)->where('ap.id',3);

        $bebanPenunjangb = $bebanPenunjangB->first();
        $beban_penunjang_b = ($bebanPenunjangb == null) ? 0 : $bebanPenunjangB->AKTUAL_YTD;

        $bebanPenunjang = DB::table(DB::raw("({$bebanPenunjangA->toSql()}) as A"))
        ->mergeBindings($bebanPenunjangA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_penunjang_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_penunjang_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_penunjang_aktual = ($bebanPenunjang == null) ? 0 : $bebanPenunjang->AKTUAL;
        $beban_penunjang_target = ($bebanPenunjang == null) ? 0 : $bebanPenunjang->TARGET;

        //farmasi
        $bebanFarmasiA = (clone $beban_a)->where('ap.id',4);
        $bebanFarmasiB = (clone $beban_b)->where('ap.id',4);

        $bebanFarmasib = $bebanFarmasiB->first();
        $beban_farmasi_b = ($bebanFarmasib == null) ? 0 : $bebanFarmasiB->AKTUAL_YTD;

        $bebanFarmasi = DB::table(DB::raw("({$bebanFarmasiA->toSql()}) as A"))
        ->mergeBindings($bebanFarmasiA)
        ->selectRaw("SUM(A.anggaran/12*'$bulan') AS TARGET")
        ->selectRaw("SUM(('$beban_farmasi_b')) AS AKTUAL")
        ->selectRaw("SUM((('$beban_farmasi_b')- (A.anggaran/12*$bulan) )/ (A.anggaran/12*$bulan )) AS PERSEN_VARIANCE")
        ->first();

        $beban_farmasi_aktual = ($bebanFarmasi == null) ? 0 : $bebanFarmasi->AKTUAL;
        $beban_farmasi_target = ($bebanFarmasi == null) ? 0 : $bebanFarmasi->TARGET;

        // master query beban
        $bebanAnggaran = DB::table('anggaran_profit as ap')
        ->selectRaw("pk.nama AS perkiraan, ap.id AS id, apr.id AS id_apr, sum(apr.nilai/12*'$bulan') AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '5%');

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
        ->where('perkiraan.kode_rekening','like', '5%')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun);

        // master query pendapatan
        $pendapatanAnggaran = DB::table('anggaran_profit as ap')
        ->selectRaw("pk.nama AS perkiraan, ap.id AS id, apr.id AS id_apr, sum(apr.nilai/12*'$bulan') AS anggaran")
        ->join('anggaran_profit_by_rekening as apr', 'apr.id_anggaran_profit', 'ap.id')
        ->join('perkiraan as pk', 'pk.id', 'apr.id_perkiraan')
        ->where('pk.kode_rekening', 'like', '4%');

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
        ->where('perkiraan.kode_rekening','like', '4%')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun);

        // variance rajal
        //beban
        $bebanRajalAnggaran = $bebanAnggaran->where('ap.id', 1)->first();
        $beban_rajal_aktual = $bebanAktual->where('anggaran_profit.id', 1)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanRajalAktual = (isset($beban_rajal_aktual)) ? $beban_rajal_aktual->aktual : 0;
        $bebanRajalAnggaran = (isset($bebanRajalAnggaran)) ? $bebanRajalAnggaran->anggaran : 0;

        //pendapatan
        $pendapatanRajalAnggaran = $pendapatanAnggaran->where('ap.id', 1)->first();
        $pendapatan_rajal_aktual = $pendapatanAktual->where('anggaran_profit.id', 1)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanRajalAktual = (isset($pendapatan_rajal_aktual)) ? $pendapatan_rajal_aktual->aktual : 0;
        $pendapatanRajalAnggaran = (isset($pendapatanRajalAnggaran)) ? $pendapatanRajalAnggaran->anggaran :0;

        //variance Ranap
        //beban
        $bebanRanapAnggaran = $bebanAnggaran->where('ap.id', 2)->first();
        $beban_ranap_aktual = $bebanAktual->where('anggaran_profit.id', 2)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanRanapAktual = (isset($beban_ranap_aktual)) ? $beban_ranap_aktual->aktual : 0;
        $bebanRanapAnggaran = (isset($bebanRanapAnggaran)) ? $bebanRanapAnggaran->anggaran :0;

        //pendapatan
        $pendapatanRanapAnggaran = $pendapatanAnggaran->where('ap.id', 2)->first();
        $pendapatan_ranap_aktual = $pendapatanAktual->where('anggaran_profit.id', 2)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanRanapAktual = (isset($pendapatan_ranap_aktual)) ? $pendapatan_ranap_aktual->aktual : 0;
        $pendapatanRanapAnggaran = (isset($pendapatanRanapAnggaran)) ? $pendapatanRanapAnggaran->anggaran :0;

        //variance farmasi
        //beban
        $bebanFarmasiAnggaran = $bebanAnggaran->where('ap.id', 3)->first();
        $beban_farmasi_aktual = $bebanAktual->where('anggaran_profit.id', 3)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanFarmasiAktual = (isset($beban_farmasi_aktual)) ? $beban_farmasi_aktual->aktual : 0;
        $bebanFarmasiAnggaran = (isset($bebanFarmasiAnggaran)) ? $bebanFarmasiAnggaran->anggaran : 0;

        //pendapatan
        $pendapatanFarmasiAnggaran = $pendapatanAnggaran->where('ap.id', 3)->first();
        $pendapatan_farmasi_aktual = $pendapatanAktual->where('anggaran_profit.id', 3)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanFarmasiAktual = (isset($pendapatan_farmasi_aktual)) ? $pendapatan_farmasi_aktual->aktual : 0;
        $pendapatanFarmasiAnggaran = (isset($pendapatanFarmasiAnggaran)) ? $pendapatanFarmasiAnggaran->anggaran : 0;

        // variance penunjang
        //beban
        $bebanPenunjangAnggaran = $bebanAnggaran->where('ap.id', 4)->first();
        $beban_penunjang__aktual = $bebanAktual->where('anggaran_profit.id', 4)->groupBy('anggaran_profit_by_rekening.id')->first();
        $bebanPenunjangAktual = (isset($beban_penunjang__aktual)) ? $beban_penunjang__aktual->aktual : 0;
        $bebanPenunjangAnggaran = (isset($bebanPenunjangAnggaran)) ? $bebanPenunjangAnggaran->anggaran :0;

        //pendapatan
        $pendapatanPenunjangAnggaran = $pendapatanAnggaran->where('ap.id', 4)->first();
        $pendapatan_penunjang_aktual = $pendapatanAktual->where('anggaran_profit.id', 4)->groupBy('anggaran_profit_by_rekening.id')->first();
        $pendapatanPenunjangAktual = (isset($pendapatan_penunjang_aktual)) ? $pendapatan_penunjang_aktual->aktual : 0;
        $pendapatanPenunjangAnggaran = (isset($pendapatanPenunjangAnggaran)) ? $pendapatanPenunjangAnggaran->anggaran :0;

        $data = [
            'pendapatanRajal'=>$pendapatanRajal,
            'pendapatanRanap'=>$pendapatanRanap,
            'pendapatanPenunjang'=>$pendapatanPenunjang,
            'pendapatanFarmasi'=>$pendapatanFarmasi,
            'bebanRajal'=>$bebanRajal,
            'bebanRanap'=>$bebanRanap,
            'bebanPenunjang'=>$bebanPenunjang,
            'bebanFarmasi'=>$bebanFarmasi,
            'bulan'=>$bulan,
            'bebanRajalAnggaran'=>$bebanRajalAnggaran,
            'bebanRajalAktual'=>$bebanRajalAktual,
            'pendapatanRajalAnggaran'=>$pendapatanRajalAnggaran,
            'pendapatanRajalAktual'=>$pendapatanRajalAktual,
            'bebanRanapAnggaran'=>$bebanRanapAnggaran,
            'bebanRanapAktual'=>$bebanRanapAktual,
            'pendapatanRanapAnggaran'=>$pendapatanRanapAnggaran,
            'pendapatanRanapAktual'=>$pendapatanRanapAktual,
            'bebanFarmasiAnggaran'=>$bebanFarmasiAnggaran,
            'bebanFarmasiAktual'=>$bebanFarmasiAktual,
            'pendapatanFarmasiAnggaran'=>$pendapatanFarmasiAnggaran,
            'pendapatanRanapAnggaran'=>$pendapatanRanapAnggaran,
            'bebanPenunjangAnggaran'=>$bebanPenunjangAnggaran,
            'bebanPenunjangAktual'=>$bebanPenunjangAktual,
            'pendapatanPenunjangAnggaran'=>$pendapatanPenunjangAnggaran,
            'pendapatanPenunjangAktual'=>$pendapatanPenunjangAktual,
            'rajal_target'=>$rajal_target,
            'rajal_aktual'=>$rajal_aktual,
            'tanggal_awal'=>$tanggal_awal,
            'tanggal_abis'=>$tanggal_abis,
            'setting'=>SettingPerusahaan::select('nama')->first(),
            'farmasi_aktual'=>$farmasi_aktual,
            'farmasi_target'=>$farmasi_target,
            'beban_rajal_aktual'=>$beban_rajal_aktual,
            'beban_rajal_target'=>$beban_rajal_target,
            'ranap_target'=>$ranap_target,
            'ranap_aktual'=>$ranap_aktual,
            'beban_farmasi_target'=>$beban_farmasi_target,
            'beban_farmasi_aktual'=>$beban_farmasi_aktual,
            'beban_penunjang_aktual'=>$beban_penunjang_aktual,
            'beban_penunjang_target'=>$beban_penunjang_target,
            'penunjang_target'=>$penunjang_target,
            'penunjang_aktual'=>$penunjang_aktual,
            'total_rajal_beban_aktual'=>$rajal_aktual - $beban_ranap_aktual,
            'beban_ranap_target'=>$beban_ranap_target,
            'beban_ranap_aktual'=>$beban_ranap_aktual
        ];

        return view ('summary-profit-center/index')->with($data);
    }
}
