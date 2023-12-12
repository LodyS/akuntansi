<?php

namespace App\Http\Controllers;
use DB;
use App\transaksi;
use App\Models\Perkiraan;
use App\Models\PeriodeKeuangan;
use App\jurnal;
use App\Models\SettingPerusahaan;
use App\DetailJurnal;
use Illuminate\Http\Request;

class LaporanNeracaSaldoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-neraca-saldo');
    }

    public function index (Request $request)
    {
        $tanggal = date('d-m-Y');
        $tanggal_akhir = date("t-m-Y", strtotime($tanggal));
        $setting = SettingPerusahaan::select('nama')->first();
        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);
        $perkiraan = DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']);
        return view ('laporan-neraca-saldo/index', compact('unit', 'perkiraan', 'setting', 'tanggal_akhir'));
    }

    public function laporan (Request $request)
    {
        $id_perkiraan = $request->id_perkiraan;
        $id_unit = $request->id_unit;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggal = ($bulan <10) ? date("d-0$bulan-$tahun") : date("d-$bulan-$tahun");

        $dataa = collect(DB::select("
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
            where sdd.aktif = 1 and sd.aktif = 1 and MONTH(j.tanggal_posting)='$bulan' AND YEAR(j.tanggal_posting)='$tahun' and j.status=2
            group by sdd.id_surplus_defisit, sdd.nama, sdd.urutan, sdd.id
        ) d on s.id = d.id_surplus_defisit
        where s.aktif = 1
        order by s.urutan, d.urutan, d.id"));


        $laporan = $dataa->groupBy('id_surplus_defisit');
        $bruto = $dataa->where('id_surplus_defisit',1)->sum('nominal') - $dataa->where('id_surplus_defisit',3)->sum('nominal');
        # SURPLUS/DEFISIT OPERASIONAL
        $oprasional = $bruto - $dataa->where('id_surplus_defisit',4)->sum('nominal');

        # SELISIH PENDAPATAN DAN BEBAN LAIN-LAIN
        $selisih = $dataa->where('id_surplus_defisit',5)->sum('nominal') - $dataa->where('id_surplus_defisit',6)->sum('nominal');

        # SURPLUS/DEFISIT SEBELUM PAJAK
        $surplusSebelumPajak = $oprasional + $selisih;

        $cek_jurnal = Jurnal::select('id')
        ->where('keterangan', 'DPPK Tahun Berjalan')
        ->whereMonth('tanggal_posting', $request->bulan)
        ->whereYear('tanggal_posting', $request->tahun)
        ->first();

        if ($cek_jurnal == null){

            $tanggal = date('Y-m-01');

            $jurnal = new jurnal;
            $jurnal->keterangan = 'DPPK Tahun Berjalan';
            $jurnal->tanggal_posting = $tanggal;
            $jurnal->status = 2;
            $jurnal->save();

            $detailJurnal = new DetailJurnal;
            $detailJurnal->id_jurnal=$jurnal->id;
            $detailJurnal->id_perkiraan=411;
            $detailJurnal->debet=0;
            $detailJurnal->kredit=$surplusSebelumPajak;
            $detailJurnal->save();

            } else if (isset($cek_jurnal->id)){

                DetailJurnal::where('id_jurnal', $cek_jurnal->id)->update(['kredit'=>$surplusSebelumPajak]);
            } else {
                DetailJurnal::where('id_jurnal', $cek_jurnal->id)->update(['kredit'=>$surplusSebelumPajak]);
        }

        $dppk= DB::table('jurnal')
        ->select('detail_jurnal.kredit as total')
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->where('jurnal.keterangan', 'DPPK Tahun Berjalan')
        ->whereMonth('jurnal.tanggal_posting', $bulan)
        ->whereYear('jurnal.tanggal_posting', $tahun)
        ->first();

        $data = DB::table('jurnal')
        ->selectRaw("perkiraan.kode_rekening as kode,unit.code_cost_centre , perkiraan.nama as perkiraan, unit.nama as unit,
        CASE
        WHEN sum(IFNULL((detail_jurnal.debet),0)) > sum(IFNULL((detail_jurnal.kredit),0))
        THEN sum(IFNULL((detail_jurnal.debet),0)) - sum(IFNULL((detail_jurnal.kredit),0))
        ELSE '0' END AS debet,
        CASE
        WHEN sum(IFNULL((detail_jurnal.debet),0)) < sum(IFNULL((detail_jurnal.kredit),0))
        THEN sum(IFNULL((detail_jurnal.kredit),0)) - sum(IFNULL((detail_jurnal.debet),0))
        ELSE '0' END AS kredit")
        ->join('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->join('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'detail_jurnal.id_unit')
        ->whereNotNull('id_tipe_jurnal')
        ->where('jurnal.status', 2)
        ->whereMonth('tanggal_posting',  $bulan)
        ->when($id_perkiraan, function($query, $id_perkiraan){
            return $query->where('detail_jurnal.id_perkiraan', $id_perkiraan);
        })
        ->when($id_unit, function($query, $id_unit){
            return $query->where('detail_jurnal.id_unit', $id_unit);
        })
        ->orderBy('perkiraan.kode_rekening', 'asc')
        ->groupBy('id_perkiraan')
        ->groupBy('id_unit')
        ->get();

        $parsing = [
            'data'=>$data,
            'tanggal_akhir'=> date('t-m-Y', strtotime($tanggal)),
            'unit'=>DB::table('unit')->get(['id', 'nama', 'code_cost_centre']),
            'perkiraan'=>DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']),
            'dppk'=>$dppk,
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'setting'=>SettingPerusahaan::select('nama')->first(),
            'total_debet'=>$data->sum('debet'),
            'total_kredit'=> $data->sum('kredit')
        ];

        return view('laporan-neraca-saldo/index')->with($parsing);
    }
}
