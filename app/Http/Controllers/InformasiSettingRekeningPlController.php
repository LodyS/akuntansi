<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SurplusDefisitRek;
use App\Models\Perkiraan;
use App\Http\Requests\plRekeningRequest;
use Illuminate\Http\Request;

class InformasiSettingRekeningPlController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-informasi-setting-rekening-pl');
    }

    public function index()
    {

        $perkiraan = Perkiraan::select('nama', 'kode_rekening')->get();

        $data = DB::table('perkiraan')
        ->selectRaw("perkiraan.id as id, perkiraan.nama AS rekening, kode_rekening, surplus_defisit_detail.nama AS p_l,

        CASE
        WHEN (perkiraan.id) IN(SELECT id_perkiraan FROM surplus_defisit_rek) THEN 'Yes'

        ELSE 'No'
        END AS flag_pl")
        ->leftJoin('surplus_defisit_rek', 'surplus_defisit_rek.id_perkiraan', 'perkiraan.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_rek.id_surplus_defisit_detail')
        ->paginate(100);

        return view('informasi-setting-rekening-pl/index', compact('data', 'perkiraan'));
    }

    public function pencarian(Request $request)
    {

        $nama_perkiraan = $request->nama_perkiraan;
        $kode_rekening = $request->kode_rekening;

        $perkiraan = Perkiraan::select('nama', 'kode_rekening')->get();

        $data = DB::table('perkiraan')
        ->selectRaw("perkiraan.id as id, perkiraan.nama AS rekening, kode_rekening, surplus_defisit_detail.nama AS p_l,

        CASE
        WHEN (perkiraan.id) IN(SELECT id_perkiraan FROM surplus_defisit_rek) THEN 'Yes'

        ELSE 'No'
        END AS flag_pl")
        ->leftJoin('surplus_defisit_rek', 'surplus_defisit_rek.id_perkiraan', 'perkiraan.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_rek.id_surplus_defisit_detail')
        ->where(function($query) use($nama_perkiraan, $kode_rekening){
            if (isset($nama_perkiraan) && $kode_rekening === null):
                $query->where('perkiraan.nama', $nama_perkiraan);

            elseif (isset($kode_rekening) && $nama_perkiraan === null):
                $query->where('kode_rekening', $kode_rekening);

            endif;
        })
        ->paginate(100);

        return view('informasi-setting-rekening-pl/index', compact('data', 'perkiraan'));
    }

    public function tambah (Request $request)
    {
        $surplusDefisitDetail = DB::table('surplus_defisit_detail')->select('id', 'nama')->get();
        $data = Perkiraan::where('id', $request->id)->firstOrFail();

        return view('informasi-setting-rekening-pl/form', compact('data', 'surplusDefisitDetail'));
    }

    public function store (plRekeningRequest $request)
    {
        SurplusDefisitRek::create($request->all());

        message(true, 'Berhasil disimpan', 'Gagal Disimpan');
        return redirect('informasi-setting-rekening-pl/index');
    }
}
