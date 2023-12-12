<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SettingPerusahaan;
use Illuminate\Http\Request;

class LaporanSurplusDefisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-surplus-defisit');
    }

    public function index()
    {
        return view('laporan-surplus-defisit/index');
    }

    public function laporan(Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;
        $setting = SettingPerusahaan::select('nama')->first();

        $data = collect(DB::select("
        select s.id as id_surplus_defisit, s.urutan_romawi, s.nama as surplus_defisit, d.surplus_defisit_detail, d.nominal
        from surplus_defisit s left join
        (
            select
                sdd.id, sdd.id_surplus_defisit, sdd.nama as surplus_defisit_detail, sdd.urutan,
                case when sd.id = 1 or sd.id = 5
                        then sum( (coalesce(dj.kredit,0) * sdd.type) - (coalesce(dj.debet,0) * sdd.type) )
                        else sum( (coalesce(dj.debet,0)) - (coalesce(dj.kredit,0)) )
                end as nominal
            from surplus_defisit sd
                inner join surplus_defisit_detail sdd on sd.id = sdd.id_surplus_defisit
                inner join surplus_defisit_rek sdr on sdd.id = sdr.id_surplus_defisit_detail
                inner join surplus_defisit_unit sdu on sdd.id = sdu.id_surplus_defisit_detail
                inner join detail_jurnal dj on sdr.id_perkiraan = dj.id_perkiraan and sdu.id_unit = dj.id_unit
                inner join jurnal j on dj.id_jurnal = j.id
            where sdd.aktif = 1 and sd.aktif = 1 and j.tanggal_posting between ? and ? and. j.status=2
            group by sdd.id_surplus_defisit, sdd.nama, sdd.urutan, sdd.id
        ) d on s.id = d.id_surplus_defisit
        where s.aktif = 1
        order by s.urutan, d.urutan, d.id", [$tanggal_mulai,$tanggal_selesai]));


        $laporan = $data->groupBy('id_surplus_defisit');

        # SURPLUS/DEFISIT BRUTO
        $bruto = $data->where('id_surplus_defisit',1)->sum('nominal') - $data->where('id_surplus_defisit',3)->sum('nominal');
        $laporan[7][0]->nominal = $bruto;

        # SURPLUS/DEFISIT OPERASIONAL
        $oprasional = $bruto - $data->where('id_surplus_defisit',4)->sum('nominal');
        $laporan[10][0]->nominal = $oprasional;

        # SELISIH PENDAPATAN DAN BEBAN LAIN-LAIN
        $selisih = $data->where('id_surplus_defisit',5)->sum('nominal') - $data->where('id_surplus_defisit',6)->sum('nominal');
        $laporan[8][0]->nominal = $selisih;

        # SURPLUS/DEFISIT SEBELUM PAJAK
        $laporan[9][0]->nominal = $oprasional + $selisih;

        return view('laporan-surplus-defisit/laporan', compact(
            'tanggal_mulai', 'tanggal_selesai', 'laporan', 'setting'
        ));
    }

    public function laporan_old (Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        $pendapatanOperasional = DB::table('jurnal')
        ->selectRaw("setting_surplus_defisit.kode, setting_surplus_defisit.nama,
        (IFNULL(SUM(detail_jurnal.kredit),0) - IFNULL(SUM(detail_jurnal.debet),0)) * IFNULL((setting_surplus_defisit.jenis),0) AS debet")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->selectRaw('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '4%')
        ->where('setting_surplus_defisit.kode', 'like', '1%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->groupBy('setting_surplus_defisit.id')
        ->get();

        $anakPendapatanOperasional = DB::select("select s.kode, s.nama,
        (IFNULL(SUM(dj.debet),0) - IFNULL(SUM(dj.kredit),0)) * IFNULL((s.jenis),0) AS debet from jurnal j
        left join detail_jurnal dj on dj.id_jurnal=j.id
        left join perkiraan pk on pk.id=dj.id_perkiraan
        left join set_surplus_defisit_detail  d on d.id_perkiraan=dj.id_perkiraan
        left join setting_surplus_defisit s on s.id=d.id_set_surplus_defisit
        where dj.id_perkiraan IN(select d.id_perkiraan from set_surplus_defisit_detail d
        join setting_surplus_defisit s on s.id=d.id_set_surplus_defisit)
        and MONTH(j.tanggal_posting)=6
        AND s.kode like '1%'
        GROUP BY s.id");

        $bebanPokokPelayanan = DB::table('jurnal')
        ->selectRaw("'Beban Pokok Pelayanan' AS nama, '' AS kode, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS debet")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->selectRaw('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '5%')
        ->where('setting_surplus_defisit.kode', 'like', '2%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->first();

        $anakBebanPokokPelayanan = DB::table('jurnal')
        ->selectRaw("setting_surplus_defisit.kode, setting_surplus_defisit.nama , IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS kredit")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->selectRaw('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '5%')
        ->where('setting_surplus_defisit.kode', 'like', '2%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->groupBy('setting_surplus_defisit.id')
        ->get();

        $bebanAdministrasiUmum = DB::table('jurnal')
        ->selectRaw("'Beban Administrasi Umum' AS nama, '' AS kode, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS debet")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->selectRaw('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '5%')
        ->where('setting_surplus_defisit.kode', 'like', '3%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->first();

        $anakBebanAdministrasiUmum = DB::table('jurnal')
        ->selectRaw("setting_surplus_defisit.kode, setting_surplus_defisit.nama, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS kredit")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->selectRaw('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '5%')
        ->where('setting_surplus_defisit.kode', 'like', '3%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->groupBy('setting_surplus_defisit.id')
        ->get();

        $pendapatanLainLain = DB::table('jurnal')
        ->selectRaw("'Pendapatan Lain-lain' AS nama, '' AS kode, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS debet")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->selectRaw('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '6%')
        ->where('setting_surplus_defisit.kode', 'like', '4%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->first();

        $anakPendapatanLainLain = DB::table('jurnal')
        ->selectRaw("setting_surplus_defisit.kode, setting_surplus_defisit.nama, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS kredit")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->select('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '6%')
        ->where('setting_surplus_defisit.kode', 'like', '4%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->groupBy('setting_surplus_defisit.id')
        ->get();

        $bebanLainLain = DB::table('jurnal')
        ->selectRaw("'Beban Lain-lain' AS nama, '' AS kode, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS debet")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->select('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '7%')
        ->where('setting_surplus_defisit.kode', 'like', '5%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->first();

        $anakBebanLainLain = DB::table('jurnal')
        ->selectRaw("setting_surplus_defisit.kode, setting_surplus_defisit.nama, IFNULL(SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet),0) AS kredit")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('set_surplus_defisit_detail', 'set_surplus_defisit_detail.id_unit', 'detail_jurnal.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->whereIn('detail_jurnal.id_unit', DB::table('set_surplus_defisit_detail')->select('set_surplus_defisit_detail.id_unit'))
        ->where('perkiraan.kode_rekening', 'like', '7%')
        ->where('setting_surplus_defisit.kode', 'like', '5%')
        ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
        ->groupBy('setting_surplus_defisit.id')
        ->get();

        return view('laporan-surplus-defisit/laporan', compact(
            'pendapatanOperasional', 'tanggal_mulai', 'tanggal_selesai', 'bebanAdministrasiUmum', 'bebanLainLain', 'pendapatanLainLain',
            'bebanPokokPelayanan', 'anakBebanLainLain', 'anakPendapatanLainLain', 'anakBebanAdministrasiUmum', 'anakBebanPokokPelayanan',
            'anakPendapatanOperasional'
        ));
    }
}
