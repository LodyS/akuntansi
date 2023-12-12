<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class LaporanPlCCController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-pl-cc');
    }

    public function index()
    {
        $tanggal_mulai = date('y-m-d');
        $tanggal_selesai = date('y-m-d');

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


        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);

        return view('laporan-pl-cc/index', compact('tanggal_mulai', 'tanggal_selesai', 'unit', 'laporan'));
    }

    public function laporan(Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);

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
            where sdd.aktif = 1 and sd.aktif = 1 and j.tanggal_posting between ? and ? and. j.status=2 and dj.id_unit='$request->id_unit'
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
        // mengganti nilai array pada variable laporan pada key urut 9

        return view('laporan-pl-cc/index', compact('tanggal_mulai', 'tanggal_selesai', 'laporan', 'unit'));
    }
}
