<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class MutasiJurnalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-mutasi-jurnal');
    }

    public function index()
    {
        return view('mutasi-jurnal/index');
    }

    public function laporan (Request $request)
    {
        $data = DB::table('detail_jurnal')
        ->selectRaw('detail_jurnal.id, jurnal.keterangan, perkiraan.kode_rekening, unit.code_cost_centre')
        ->selectRaw('perkiraan.nama AS perkiraan, unit.nama AS unit')
        ->selectRaw("CASE
        WHEN jurnal.keterangan = 'Saldo Awal' THEN detail_jurnal.debet
        WHEN jurnal.keterangan <> 'Saldo Awal' THEN 0 END AS debet_saldo_awal,

        CASE
        WHEN jurnal.keterangan = 'Saldo Awal' THEN detail_jurnal.kredit
        WHEN jurnal.keterangan <> 'Saldo Awal' THEN 0 END AS kredit_saldo_awal,

        CASE
        WHEN jurnal.keterangan <> 'Saldo Awal' THEN detail_jurnal.debet
        WHEN jurnal.keterangan = 'Saldo Awal' THEN 0 END AS debet_mutasi,

        CASE
        WHEN jurnal.keterangan <> 'Saldo Awal' THEN detail_jurnal.kredit
        WHEN jurnal.keterangan = 'Saldo Awal' THEN 0 END AS kredit_mutasi")
        ->leftJoin('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'detail_jurnal.id_unit')
        ->whereMonth('jurnal.tanggal_posting', $request->bulan)
        ->whereYear('jurnal.tanggal_posting', $request->tahun)
        ->groupBy('id_perkiraan')
        ->groupBy('id_unit')
        ->get();

        $debetSaldoAwal = $data->sum('debet_saldo_awal');
        $kreditSaldoAwal = $data->sum('kredit_saldo_awal');
        $debetMutasi = $data->sum('debet_mutasi');
        $kreditMutasi = $data->sum('kredit_mutasi');

        return view('mutasi-jurnal/index', compact('data', 'debetSaldoAwal', 'kreditSaldoAwal', 'debetMutasi', 'kreditMutasi'));
    }
}
