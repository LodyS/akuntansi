<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SurplusDefisitUnit;
use App\Models\Unit;
use Illuminate\Http\Request;

class InformasiSettingUnitPlController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-informasi-setting-unit-pl');
    }

    public function index()
    {
        $unit = Unit::pluck('code_cost_centre', 'nama');

        $data = DB::table('unit')
        ->selectRaw('unit.id as id, unit.nama AS unit, code_cost_centre, surplus_defisit_detail.nama AS p_l')
        ->selectRaw("CASE WHEN (unit.id) IN(SELECT id_unit FROM surplus_defisit_unit) THEN 'Yes' ELSE 'No' END AS flag_pl")
        ->leftJoin('surplus_defisit_unit', 'surplus_defisit_unit.id_unit', 'unit.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_unit.id_surplus_defisit_detail')
        ->paginate(100);

        return view('informasi-setting-unit-pl/index', compact('data', 'unit'));
    }

    public function pencarian(Request $request)
    {
        $nama_unit = $request->nama_unit;
        $code_cost_centre = $request->code_cost_centre;

        $unit = Unit::pluck('code_cost_centre', 'nama');

        $data = DB::table('unit')
        ->selectRaw('unit.id as id, unit.nama AS unit, code_cost_centre, surplus_defisit_detail.nama AS p_l')
        ->selectRaw("CASE WHEN (unit.id) IN(SELECT id_unit FROM surplus_defisit_unit) THEN 'Yes' ELSE 'No' END AS flag_pl")
        ->leftJoin('surplus_defisit_unit', 'surplus_defisit_unit.id_unit', 'unit.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_unit.id_surplus_defisit_detail')
        ->when($nama_unit, function($query, $nama_unit){
            return $query->where('unit.nama', $nama_unit);
        })
        ->when($code_cost_centre, function($query, $code_cost_centre){
            return $query->where('unit.code_cost_centre', $code_cost_centre);
        })
        ->paginate(100);

        return view('informasi-setting-unit-pl/index', compact('data', 'unit'));
    }

    public function tambah (Request $request)
    {
        $surplusDefisitDetail = DB::table('surplus_defisit_detail')->pluck('nama', 'id');
        $data = Unit::where('id', $request->id)->firstOrFail();

        return view('informasi-setting-unit-pl/form', compact('data', 'surplusDefisitDetail'));
    }

    public function store (Request $request)
    {
        $request->validate([
            'id_surplus_defisit_detail'=>'required'
        ]);

        SurplusDefisitUnit::create($request->all());

        message(true, 'Berhasil disimpan', 'Gagal Disimpan');
        return redirect('informasi-setting-unit-pl/index');
    }
}

