<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class InformasiSettingPlController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-informasi-setting-pl');
    }

    public function index()
    {
        $surplusUnit = DB::table('unit')
        ->selectRaw('nama, code_cost_centre')
        ->whereNotIn('id', DB::table('surplus_defisit_unit')->select('id_unit'))
        ->paginate(50);

        $surplusPerkiraan = DB::table('perkiraan')
        ->selectRaw('nama, kode_rekening')
        ->whereNotIn('id', DB::table('surplus_defisit_rek')->select('id_perkiraan'))
        ->where('kode_rekening', 'like', '4%')
        ->where('kode_rekening', 'like', '5%')
        ->where('kode_rekening', 'like', '6%')
        ->where('kode_rekening', 'like', '7%')
        ->paginate(50);

        return view ('informasi-setting-pl/index', compact('surplusUnit', 'surplusPerkiraan'));
    }
}
