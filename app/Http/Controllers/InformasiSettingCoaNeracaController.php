<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SetNeraca;
use App\Models\SetNeracaDetail;
use App\Models\Perkiraan;
use App\Http\Requests\SettingCoaNeraca;
use Illuminate\Http\Request;

class InformasiSettingCoaNeracaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-informasi-setting-coa-neraca');
    }

    public function index()
    {
        $perkiraan = Perkiraan::select('nama', 'kode_rekening')->get();

        $data = DB::table('perkiraan')
        ->selectRaw("perkiraan.id as id, perkiraan.nama AS rekening, kode_rekening, set_neraca.nama AS neraca,

        CASE
        WHEN (perkiraan.id) IN(SELECT id_perkiraan FROM set_neraca_detail) THEN 'Yes'

        ELSE 'No'
        END AS flag_pl")
        ->leftJoin('set_neraca_detail', 'set_neraca_detail.id_perkiraan', 'perkiraan.id')
        ->leftJoin('set_neraca', 'set_neraca.id', 'set_neraca_detail.id_set_neraca')
        ->paginate(100);

        return view('informasi-setting-coa-neraca/index', compact('data', 'perkiraan'));
    }

    public function pencarian(Request $request)
    {
        $nama_perkiraan = $request->nama_perkiraan;
        $kode_rekening = $request->kode_rekening;

        $perkiraan = Perkiraan::select('nama', 'kode_rekening')->get();

        $data = DB::table('perkiraan')
        ->selectRaw("perkiraan.id as id, perkiraan.nama AS rekening, kode_rekening, set_neraca.nama AS neraca,

        CASE
        WHEN (perkiraan.id) IN(SELECT id_perkiraan FROM set_neraca_detail) THEN 'Yes'

        ELSE 'No'
        END AS flag_pl")
        ->leftJoin('set_neraca_detail', 'set_neraca_detail.id_perkiraan', 'perkiraan.id')
        ->leftJoin('set_neraca', 'set_neraca.id', 'set_neraca_detail.id_set_neraca')
        ->where(function($query) use($nama_perkiraan, $kode_rekening){
            if (isset($nama_perkiraan) && $kode_rekening === null){
                $query->where('perkiraan.nama', $nama_perkiraan);
            } else if (isset($kode_rekening) && $nama_perkiraan === null){
                $query->where('kode_rekening', $kode_rekening);
            }
        })
        ->paginate(100);

        return view('informasi-setting-coa-neraca/index', compact('data', 'perkiraan'));
    }

    public function tambah (Request $request)
    {
        $setNeraca = DB::table('set_neraca')->select('id', 'nama')->get();
        $data = Perkiraan::where('id', $request->id)->firstOrFail();

        return view('informasi-setting-coa-neraca/form', compact('data', 'setNeraca'));
    }

    public function store (SettingCoaNeraca $request)
    {
        SetNeracaDetail::create($request->all());

        message(true, 'Berhasil disimpan', 'Gagal Disimpan');
        return redirect('informasi-setting-coa-neraca/index');
    }
}
