<?php

namespace App\Http\Controllers;
use DB;
use App\ArusKas;
use App\Models\MutasiKas;
use App\Models\PeriodeKeuangan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanArusKasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-arus-kas');
    }

    public function index ()
    {

        $data = DB::select('
        select B.id, B.nama, A.saldo, C.total from
        (select a.id as id, a.nama , ( sum(dj.debet) - sum(dj.kredit) ) * jenis as saldo from
        jurnal j join detail_jurnal dj
        on dj.id_jurnal = j.id
        join perkiraan pk on pk.id=dj.id_perkiraan
        join arus_kas_detail d on d.id_perkiraan=pk.id
        left join arus_kas a on a.id=d.id_arus_kas
        group by a.id) A

        right join
        (select a.id as id, a.kode, a.nama  as nama from arus_kas a ) B
        on B.id=A.id


        left join (
        select a.id as id, (SUM(debet) - SUM(kredit))* jenis  as TOTAL from
        jurnal j
        join detail_jurnal dj on dj.id_jurnal=j.id
        join arus_kas_detail d on d.id_perkiraan=dj.id_perkiraan
        join arus_kas_rumus r on d.id_arus_kas=r.id_rumus
        join arus_kas a on r.id_arus_kas=a.id
        group by a.id) C
        on C.id=B.id

        order by kode');

        return view ('laporan-arus-kas/index', compact('data'));
    }

    public function pencarian (Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        $data = DB::select("select B.id, B.nama, A.saldo, C.total from
        (select a.id as id, a.nama , ( sum(dj.debet) - sum(dj.kredit) ) * jenis as saldo from
        jurnal j join detail_jurnal dj
        on dj.id_jurnal = j.id
        join perkiraan pk on pk.id=dj.id_perkiraan
        join arus_kas_detail d on d.id_perkiraan=pk.id
        left join arus_kas a on a.id=d.id_arus_kas
        WHERE tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai'
        group by a.id) A

        right join
        (select a.id as id, a.kode, a.nama  as nama from arus_kas a ) B
        on B.id=A.id


        left join (
        select a.id as id, (SUM(debet) - SUM(kredit))* jenis  as TOTAL from
        jurnal j
        join detail_jurnal dj on dj.id_jurnal=j.id
        join arus_kas_detail d on d.id_perkiraan=dj.id_perkiraan
        join arus_kas_rumus r on d.id_arus_kas=r.id_rumus
        join arus_kas a on r.id_arus_kas=a.id
        WHERE tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai'
        group by a.id) C
        on C.id=B.id

        order by B.kode");

        return view ('laporan-arus-kas/index', compact('data', 'tanggal_mulai', 'tanggal_selesai'));
    }
}
