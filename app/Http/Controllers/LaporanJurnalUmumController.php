<?php

namespace App\Http\Controllers;
use DB;
use App\jurnal;
use App\DetailJurnal;
use App\Models\Perkiraan;
use App\TipeJurnal;
use App\Models\SettingPerusahaan;
use App\pendapatan_jasa;
use App\detail_pendapatan_jasa;
use App\Models\KasBank;
use Illuminate\Http\Request;
use App\Http\Requests\ValidasiTanggal;

class LaporanJurnalUmumController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-jurnal-umum');
    }

    public function index ()
    {
        $tanggal_mulai = date('Y-m-d');
        $tanggal_selesai = date('Y-m-d');
        $setting = SettingPerusahaan::select('nama')->first();
        $tipeJurnal = TipeJurnal::get(['id', 'tipe_jurnal']);
        return view ('laporan-jurnal-umum/index', compact('tipeJurnal', 'setting', 'tanggal_mulai', 'tanggal_selesai'));
    }

    public function laporan (Request $request)
    {
        $status = $request->status;
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;
        $tipe_jurnal = $request->tipe_jurnal;

        $request->validate([
            'tanggal_mulai'=>'required',
            'tanggal_selesai'=>'required',
        ]);

        $rekapitulasi = collect(DB::select("SELECT A.id, A.keterangan, A.tanggal_posting, A.kode_jurnal, A.kode_rekening, A.nama, A.debet,
        A.kredit, A.layer, A.urutan, A.unit, A.code_cost_centre, A.kode_rekening, A.id_tipe_jurnal, A.status,

        (CASE A.kode_jurnal
        WHEN @no_kode THEN @no_urut := @no_urut+1 ELSE @no_urut:=1 AND @no_kode :=A.kode_jurnal END) AS urutin
        from(SELECT jurnal.id, jurnal.keterangan, jurnal.tanggal_posting, jurnal.kode_jurnal,
        perkiraan.kode_rekening, perkiraan.nama,
        unit.nama AS unit, unit.code_cost_centre, jurnal.id_tipe_jurnal, jurnal.status,
        SUM(detail_jurnal.debet) AS debet,
        SUM(detail_jurnal.kredit) AS kredit, detail_jurnal.layer, detail_jurnal.urutan,

        CASE
        WHEN detail_jurnal.debet>0 AND detail_jurnal.layer IS NULL AND detail_jurnal.urutan IS NULL THEN '1'
        WHEN detail_jurnal.kredit >0 AND detail_jurnal.layer IS NULL AND detail_jurnal.urutan IS NULL THEN '2'
        END AS status_nominal

        FROM jurnal
        LEFT JOIN detail_jurnal  ON detail_jurnal.id_jurnal=jurnal.id
        LEFT JOIN perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        LEFT JOIN unit ON unit.id=detail_jurnal.id_unit
        WHERE tanggal_posting BETWEEN '$tanggal_mulai' AND '$tanggal_selesai' and concat(detail_jurnal.debet, detail_jurnal.kredit) <> '00'

        and case
        when '$tipe_jurnal' = '' then id_tipe_jurnal in(select id_tipe_jurnal from jurnal) or id_tipe_jurnal is null
        when '$tipe_jurnal' = null then id_tipe_jurnal in(select id_tipe_jurnal from jurnal) or id_tipe_jurnal is null
        else id_tipe_jurnal='$tipe_jurnal' end and CONCAT(detail_jurnal.debet, detail_jurnal.kredit) <>00

        GROUP BY jurnal.id, jurnal.tanggal_posting, jurnal.kode_jurnal, perkiraan.kode_rekening, perkiraan.nama,
        unit.nama, unit.code_cost_centre, detail_jurnal.layer, detail_jurnal.urutan
        ORDER BY jurnal.id,  detail_jurnal.layer , detail_jurnal.urutan, perkiraan.kode_rekening)A,
        (SELECT @no_urut:=1, @no_kode:='') B
        ORDER BY kode_jurnal, layer, urutan, status_nominal "));


        $data = [
            'rekapitulasi'=>$rekapitulasi,
            'setting'=>SettingPerusahaan::select('nama')->first(),
            'tipeJurnal'=>TipeJurnal::get(['id', 'tipe_jurnal']),
            'totalDebet'=>$rekapitulasi->sum('debet'),
            'totalKredit'=>$rekapitulasi->sum('kredit'),
            'balance'=>$rekapitulasi->sum('debet') - $rekapitulasi->sum('kredit'),
            'tanggal_mulai'=>$tanggal_mulai,
            'tanggal_selesai'=>$tanggal_selesai
        ];

        return view('laporan-jurnal-umum/index')->with($data);
    }

    public function cetak(Request $request)
    {
        //laporan();
        $tanggal_mulai=$request->tanggal_mulai;
        $tanggal_selesai=$request->tanggal_selesai;

        //dd($tanggal_selesai);

        $setting = SettingPerusahaan::select('nama')->first();
        $rekapitulasi = collect(DB::select("SELECT A.id, A.keterangan, A.tanggal_posting, A.kode_jurnal, A.kode_rekening, A.nama, A.debet,
        A.kredit, A.layer, A.urutan, A.unit, A.code_cost_centre, A.kode_rekening, A.id_tipe_jurnal, A.status,

        (CASE A.kode_jurnal
        WHEN @no_kode THEN @no_urut := @no_urut+1 ELSE @no_urut:=1 AND @no_kode :=A.kode_jurnal END) AS urutin
        FROM(SELECT jurnal.id, jurnal.keterangan, jurnal.tanggal_posting, jurnal.kode_jurnal,
        perkiraan.kode_rekening, perkiraan.nama,
        unit.nama AS unit, unit.code_cost_centre, jurnal.id_tipe_jurnal, jurnal.status,
        SUM(detail_jurnal.debet) AS debet,
        SUM(detail_jurnal.kredit) AS kredit, detail_jurnal.layer, detail_jurnal.urutan,

        CASE
        WHEN detail_jurnal.debet>0 AND detail_jurnal.layer IS NULL AND detail_jurnal.urutan IS NULL THEN '1'
        WHEN detail_jurnal.kredit >0 AND detail_jurnal.layer IS NULL AND detail_jurnal.urutan IS NULL THEN '2'
        END AS status_nominal

        FROM jurnal
        LEFT JOIN detail_jurnal  ON detail_jurnal.id_jurnal=jurnal.id
        LEFT JOIN perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        LEFT JOIN unit ON unit.id=detail_jurnal.id_unit
        WHERE kode_jurnal='$request->kode_jurnal'

        GROUP BY jurnal.id, jurnal.tanggal_posting, jurnal.kode_jurnal, perkiraan.kode_rekening, perkiraan.nama,
        unit.nama, unit.code_cost_centre, detail_jurnal.layer, detail_jurnal.urutan
        ORDER BY jurnal.id,  detail_jurnal.layer , detail_jurnal.urutan, perkiraan.kode_rekening)A,
        (SELECT @no_urut:=1, @no_kode:='') B
        ORDER BY kode_jurnal, layer, urutan, status_nominal"));

        $debet = $rekapitulasi->sum('debet');
        $kredit = $rekapitulasi->sum('kredit');
        $balance = $debet - $kredit;

        return view ('laporan-jurnal-umum/cetak', compact('rekapitulasi', 'debet', 'kredit', 'setting', 'balance', 'tanggal_mulai', 'tanggal_selesai'));
    }

    public function balance ()
    {
        $rekapitulasi = collect(DB::select("SELECT A.kode_jurnal, A.nama, A.debet, A.kredit, A.layer, A.urutan,

        (CASE A.kode_jurnal
        WHEN @no_kode THEN @no_urut := @no_urut+1 ELSE @no_urut:=1 AND @no_kode :=A.kode_jurnal END) AS urutin

        FROM (SELECT jurnal.id, jurnal.keterangan, jurnal.tanggal_posting, jurnal.kode_jurnal,
        perkiraan.kode_rekening, perkiraan.nama,
        unit.nama AS unit, unit.code_cost_centre, jurnal.id_tipe_jurnal, jurnal.status,
        SUM(detail_jurnal.debet) AS debet,
        SUM(detail_jurnal.kredit) AS kredit, detail_jurnal.layer, detail_jurnal.urutan

        FROM jurnal
        LEFT JOIN detail_jurnal  ON detail_jurnal.id_jurnal=jurnal.id
        LEFT JOIN perkiraan ON perkiraan.id=detail_jurnal.id_perkiraan
        LEFT JOIN unit ON unit.id=detail_jurnal.id_unit


        GROUP BY jurnal.id, jurnal.tanggal_posting, jurnal.kode_jurnal, perkiraan.kode_rekening, perkiraan.nama, unit.nama,
        unit.code_cost_centre, detail_jurnal.layer, detail_jurnal.urutan
        ORDER BY jurnal.id,  detail_jurnal.layer , detail_jurnal.urutan, perkiraan.kode_rekening)A,

        (SELECT @no_urut:=1, @no_kode:='') B

	    WHERE CONCAT(A.debet, A.kredit) <>00
        ORDER BY kode_jurnal, layer, urutan; "));

        return view ('laporan-jurnal-umum/balance', compact('rekapitulasi'));
    }

    public function edit (Request $request)
    {
        $data = Jurnal::where('id', $request->id_jurnal)->firstOrFail();

        return view('laporan-jurnal-umum/edit', compact('data'));
    }

    public function detail (Request $request)
    {
        $jurnal = jurnal::selectRaw('tanggal_posting, tipe_jurnal, keterangan, jurnal.kode_jurnal')
        ->leftJoin('tipe_jurnal', 'tipe_jurnal.id', 'jurnal.id_tipe_jurnal')
        ->where('jurnal.id', $request->id_jurnal)
        ->firstOrFail();

        $data = DB::table('detail_jurnal')
        ->selectRaw('nakes.kode, nakes.nama as nakes, detail_jurnal.debet, detail_jurnal.kredit, code_cost_centre, kode_rekening')
        ->selectRaw('perkiraan.nama as rekening, detail_jurnal.invoice, unit.nama as unit')
        ->leftJoin('nakes', 'nakes.id', 'detail_jurnal.id_nakes')
        ->leftJoin('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->leftJoin('invoice', 'invoice.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'detail_jurnal.id_unit')
        ->where('detail_jurnal.id_jurnal', $request->id_jurnal)
        ->get();

        return view ('laporan-jurnal-umum/detail', compact('jurnal', 'data'));
    }

    public function update (Request $request)
    {
        $request->validate([
            'status'=>'required',
        ]);

        Jurnal::where('id', $request->id)->update(['status'=>$request->status]);

        message(true, 'Berhasil update', 'Gagal Update');
        return redirect('laporan-jurnal-umum/index');
    }
}
