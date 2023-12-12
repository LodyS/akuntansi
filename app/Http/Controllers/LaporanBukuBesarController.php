<?php

namespace App\Http\Controllers;
use App\Models\Perkiraan;
use DB;
use App\jurnal;
use App\Models\SettingPerusahaan;
use App\detail_jurnal;
use Illuminate\Http\Request;
use App\Http\Requests\ValidasiTanggal;

class LaporanBukuBesarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-laporan-buku-besar');
    }

    public function index ()
    {
        $setting = SettingPerusahaan::select('nama')->first();
        $unit = DB::table('unit')->get(['id', 'nama']);
        $perkiraan = Perkiraan::get(['id', 'nama']);
        return view ('laporan-buku-besar/index', compact('perkiraan', 'unit', 'setting'));
    }

    public function laporan (ValidasiTanggal $request)
    {
        $unit = DB::table('unit')->get(['id', 'nama']);
        $perkiraan = Perkiraan::get(['id', 'nama']);
        $setting = SettingPerusahaan::select('nama')->first();

        $request->validate([
            'id_perkiraan'=>'required',
        ]);

        $id_perkiraan = $request->id_perkiraan;
        $id_unit = $request->id_unit;
        $tanggal_selesai = $request->tanggal_selesai;
        $tanggal_mulai = $request->tanggal_mulai;
        $rekening = Perkiraan::select('nama', 'kode_rekening')->where('id', $request->id_perkiraan)->first();

       if (isset($id_unit))
        {
            $rekapitulasi = DB::table('detail_jurnal')
            ->selectRaw("tanggal_posting, jurnal.keterangan, unit.code_cost_centre, unit.nama as unit, debet, kredit,

            CASE
            WHEN

            (SELECT SUM(dj.debet) > SUM(dj.kredit) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai'  and
            id_perkiraan='$request->id_perkiraan' and id_unit='$request->id_unit')

            THEN

            (SELECT SUM(dj.debet) - SUM(dj.kredit) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai' AND id_perkiraan='$request->id_perkiraan'
            and id_unit='$request->id_unit')
            ELSE '0' END AS saldo_debet,

            CASE
            WHEN

            (SELECT SUM(dj.debet) < SUM(dj.kredit) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai' AND id_perkiraan='$request->id_perkiraan'
            and id_unit='$request->id_unit')

            THEN

            (SELECT SUM(dj.kredit) - SUM(dj.debet) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai' AND id_perkiraan='$request->id_perkiraan' and
            id_unit='$request->id_unit')
            ELSE '0' END AS saldo_kredit")
            ->leftjoin('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
            ->leftJoin('unit', 'unit.id', 'detail_jurnal.id_unit')
            ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
            ->when($id_perkiraan, function($query, $id_perkiraan){
                return  $query->where('id_perkiraan', $id_perkiraan);
            })
            ->when($id_unit, function($query, $id_unit){
                return $query->where('id_unit', $id_unit);
            })
            ->get();

        } else {

            $rekapitulasi = DB::table('detail_jurnal')
            ->selectRaw("tanggal_posting, jurnal.keterangan, unit.code_cost_centre, unit.nama as unit, debet, kredit,

            CASE
            WHEN

            (SELECT SUM(dj.debet) > SUM(dj.kredit) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai'  and
            id_perkiraan='$request->id_perkiraan')

            THEN

            (SELECT SUM(dj.debet) - SUM(dj.kredit) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai' AND id_perkiraan='$request->id_perkiraan')
            ELSE '0' END AS saldo_debet,

            CASE
            WHEN

            (SELECT SUM(dj.debet) < SUM(dj.kredit) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai' AND id_perkiraan='$request->id_perkiraan')

            THEN

            (SELECT SUM(dj.kredit) - SUM(dj.debet) FROM detail_jurnal dj
            JOIN jurnal ON jurnal.id = dj.id_jurnal
            WHERE dj.id <= detail_jurnal.id
            AND tanggal_posting BETWEEN '$request->tanggal_mulai' AND '$request->tanggal_selesai' AND id_perkiraan='$request->id_perkiraan' )
            ELSE '0' END AS saldo_kredit")
            ->leftjoin('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
            ->leftJoin('unit', 'unit.id', 'detail_jurnal.id_unit')
            ->whereBetween('tanggal_posting', [$tanggal_mulai, $tanggal_selesai])
            ->when($id_perkiraan, function($query, $id_perkiraan){
                return  $query->where('id_perkiraan', $id_perkiraan);
            })->get();

        }

        return view('laporan-buku-besar/index', compact('rekapitulasi', 'perkiraan', 'tanggal_mulai', 'unit','tanggal_selesai', 'rekening', 'setting'));
    }
}
