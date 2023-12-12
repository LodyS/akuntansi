<?php

namespace App\Http\Controllers;
use DB;
use App\jurnal;
use App\DetailJurnal;
use App\Models\SettingPerusahaan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NeracaSaldo;
use Illuminate\Http\Request;

class NeracaLaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-neraca-laporan');
    }

    public function index()
    {
        $tanggal_awal = date('01-m-Y');
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));
        $setting = SettingPerusahaan::select('nama')->first();
        return view ('neraca-laporan/index', compact('setting', 'tanggal_awal', 'tanggal_abis'));
    }

    public function laporan (Request $request)
    {
        $setting = SettingPerusahaan::select('nama')->first();

        $request->validate([
            'bulan'=>'required',
            'tahun'=>'required',
        ]);

        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $tanggal = ($bulan) ? $tahun.'-'.$bulan.'-'.'1' : $tahun.'-'.'0'.$bulan;
        $tanggal_akhir = date('Y-m-t', strtotime($tanggal));
        //dd($tanggal_akhir);

        $tanggal_awal = ($bulan <10) ? date("01-0$bulan-$tahun") :date("01-$bulan-$tahun");
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));


        $bulan_indonesia = bulan($request->bulan);

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
            where sdd.aktif = 1 and sd.aktif = 1 and MONTH(j.tanggal_posting)='$bulan' AND YEAR(j.tanggal_posting)='$tahun' and j.status=2
            group by sdd.id_surplus_defisit, sdd.nama, sdd.urutan, sdd.id
        ) d on s.id = d.id_surplus_defisit
        where s.aktif = 1
        order by s.urutan, d.urutan, d.id"));


        $laporan = $data->groupBy('id_surplus_defisit');
        $bruto = $data->where('id_surplus_defisit',1)->sum('nominal') - $data->where('id_surplus_defisit',3)->sum('nominal');
        # SURPLUS/DEFISIT OPERASIONAL
        $oprasional = $bruto - $data->where('id_surplus_defisit',4)->sum('nominal');

        # SELISIH PENDAPATAN DAN BEBAN LAIN-LAIN
        $selisih = $data->where('id_surplus_defisit',5)->sum('nominal') - $data->where('id_surplus_defisit',6)->sum('nominal');

        # SURPLUS/DEFISIT SEBELUM PAJAK
        $surplusSebelumPajak = $oprasional + $selisih;


        $cek_jurnal = jurnal::select('id')
        ->where('keterangan', 'DPPK Tahun Berjalan')
        ->whereMonth('tanggal_posting', $request->bulan)
        ->whereYear('tanggal_posting', $request->tahun)
        ->first(); // cek jurnal yang dppk tahun berjalan

        if ($cek_jurnal == null){

            $jurnal = new jurnal;
            $jurnal->keterangan = 'DPPK Tahun Berjalan';
            $jurnal->tanggal_posting = $tanggal_akhir;
            $jurnal->status = 2;
            $jurnal->save();

            $detailJurnal = new detail_jurnal;
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

        $aktiva = collect(DB::select("SELECT B.id as id, B.nama as nama, ifnull(A.saldo,0) as saldo, ifnull(sum(C.total),0) as total FROM

        (SELECT sn.id AS id, sn.nama , ((SUM(dj.debet) - SUM(dj.kredit)) * sn.jenis) AS saldo FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=dj.id_perkiraan
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Aktiva' AND MONTH(tanggal_posting) = '$request->bulan' AND YEAR(tanggal_posting) = '$request->tahun' and j.status=2
        GROUP BY sn.id) A
        RIGHT JOIN

        (SELECT sn.id AS id, kode, sn.nama  AS nama FROM set_neraca sn WHERE jenis_neraca='Aktiva') B ON B.id=A.id
        LEFT JOIN (SELECT   r.id_rumus, r.id_set_neraca AS id, (((SUM(dj.debet))-(SUM(dj.kredit)))* n.jenis)  AS TOTAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN set_neraca_detail d ON d.id_perkiraan=dj.id_perkiraan
        JOIN set_neraca_rumus r ON d.id_set_neraca=r.id_rumus
        JOIN set_neraca n ON r.id_rumus=n.id
        WHERE jenis_neraca='Aktiva' AND MONTH(tanggal_posting) = '$request->bulan' AND YEAR(tanggal_posting) = '$request->tahun' and j.status=2
        GROUP BY  r.id_rumus ,  r.id_set_neraca) C ON C.id=B.id

        GROUP BY nama
        ORDER BY kode "));

        $passiva = collect(DB::select("SELECT B.id as id, B.kode as kode, B.nama as passiva, ifnull(A.saldo,0) as saldo_passiva, ifnull(C.TOTAL,0) as total
        FROM
        (SELECT sn.id AS id, sn.nama , ( SUM(dj.debet) - SUM(dj.kredit) ) * jenis AS saldo FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=pk.id
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Passiva'
        AND MONTH(j.tanggal_posting)='$bulan' AND YEAR(j.tanggal_posting)='$tahun' and j.status=2
        GROUP BY sn.id) A

        RIGHT JOIN
        (SELECT sn.id AS id, kode, sn.nama  AS nama FROM set_neraca sn WHERE jenis_neraca='Passiva') B
        ON B.id=A.id

        LEFT JOIN (
        SELECT n.id AS id, (SUM(debet) - SUM(kredit))* jenis  AS TOTAL FROM
        jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN set_neraca_detail d ON d.id_perkiraan=dj.id_perkiraan
        JOIN set_neraca_rumus r ON d.id_set_neraca=r.id_rumus
        JOIN set_neraca n ON r.id_set_neraca=n.id
        WHERE jenis_neraca='Passiva'
        AND MONTH(j.tanggal_posting)='$bulan' AND YEAR(j.tanggal_posting)='$tahun' and j.status=2
        GROUP BY n.id) C
        ON C.id=B.id "));

        $totalAktiva = $aktiva->count();
        $totalPassiva = $passiva->count();

        $jumlahAktiva = $totalAktiva - $totalPassiva;
        $jumlahPassiva = $totalPassiva - $totalAktiva;

        $selisihAktiva = ($jumlahAktiva >=0) ? $jumlahAktiva : 0;
        $selisihPassiva = ($jumlahPassiva >=0) ? $jumlahPassiva : 0;

        $totalAktiva = $aktiva->where('nama', 'TOTAL ASET')->sum('total');
        $saldoAktiva = $aktiva->where('nama', 'TOTAL ASET')->sum('saldo');
        $totalAset = $totalAktiva + $saldoAktiva;

        //$totalPassiva = $passiva->where('passiva', 'TOTAL MODAL DAN LIABILITAS')->sum('total');
        $saldoPassiva = $passiva->where('passiva', 'TOTAL MODAL DAN LIABILITAS')->sum('saldo_passiva');
        $totalPassiva = $passiva->where('passiva', 'TOTAL MODAL DAN LIABILITAS')->sum('total');

        $saldo_passiva = $saldoPassiva + $totalPassiva;
        //dd($saldoPassiva);
                                                  //TOTAL MODAL DAN LIABILITAS

        //dd($saldo_passiva);



        return view('neraca-laporan/index', compact('aktiva', 'passiva', 'bulan_indonesia', 'bulan', 'tahun', 'selisihAktiva', 'selisihPassiva',
        'totalAset', 'totalPassiva', 'saldo_passiva', 'tanggal_awal', 'tanggal_abis', 'setting'));
    }
}
