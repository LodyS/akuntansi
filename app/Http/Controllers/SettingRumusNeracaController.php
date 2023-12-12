<?php

namespace App\Http\Controllers;
use DB;
use App\SetNeracaRumus;
use App\Models\SetNeraca;
use Illuminate\Http\Request;

class SettingRumusNeracaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-rumus-neraca');
    }

    public function index()
    {
        $data = DB::table('set_neraca')
        ->selectRaw('set_neraca.id, set_neraca.kode, set_neraca.nama, set_neraca.level, dua.nama as induk')
        ->selectRaw('case when set_neraca.jenis = "-1" then "Pengurang" when set_neraca.jenis= "1" then "Penambah" else ""end as jenis')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk')
        ->paginate(50);

        return view('setting-rumus-neraca/index', compact('data'));
    }

    public function detail (Request $request)
    {
        $id = $request->id;
        $nama = SetNeraca::selectRaw('nama, jenis_neraca')->findOrFail($request->id);
        $data = DB::table('set_neraca_rumus')
        ->selectRaw('set_neraca_rumus.id, set_neraca.nama, set_neraca.kode, st.nama as rumus')
        ->selectRaw('case when set_neraca.jenis = "-1" then "Pengurang" when set_neraca.jenis= "1" then "Penambah" else ""end as jenis')
        ->leftJoin('set_neraca', 'set_neraca.id', 'set_neraca_rumus.id_set_neraca')
        ->leftJoin('set_neraca as st', 'st.id', 'set_neraca_rumus.id_rumus')
        ->where('id_set_neraca', $request->id)
        ->paginate(25);

        return view('setting-rumus-neraca/detail', compact('data', 'id', 'nama'));
    }

    public function tambah(Request $request)
    {
        $neraca = SetNeraca::get(['id', 'nama']);
        $subRumus = SetNeraca::whereIn('id', SetNeracaRumus::select('id'))->get(['id', 'nama']);

        $data =  SetNeraca::selectRaw('set_neraca.id, set_neraca.jenis_neraca, set_neraca.kode, set_neraca.nama, dua.nama as induk')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk')
        ->findOrFail($request->id);

        return view("setting-rumus-neraca/form-tambah", compact('data','neraca', 'subRumus'));
    }

    public function edit(Request $request)
    {
        $neraca = SetNeraca::get(['id', 'nama']);
        $subRumus = SetNeraca::whereIn('id', SetNeracaRumus::select('id'))->get(['id', 'nama']);

        $data = SetNeracaRumus::selectRaw('set_neraca_rumus.id, set_neraca.kode, set_neraca.jenis_neraca')
        ->selectRaw('set_neraca.nama, dua.nama as induk, id_rumus, id_sub_rumus')
        ->leftJoin('set_neraca', 'set_neraca.id', 'set_neraca_rumus.id_set_neraca')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk')
        ->findOrFail($request->id);

        return view("setting-rumus-neraca/form-edit", compact('data','neraca', 'subRumus'));
    }

    public function store (Request $request)
    {
        DB::beginTransaction();

        try {

            $act = SetNeracaRumus::create($request->all());
            DB::commit();
            message($act, 'Berhasil disimpan', 'Gagal simpan');
            return redirect('setting-rumus-neraca/index');
        }
        catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $act = SetNeracaRumus::find($request->id)->update($request->all());
            DB::commit();
            message($act, 'Berhasil di update', 'Gagal diupdate');
            return redirect('setting-rumus-neraca/index');
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    public function delete (Request $request)
    {
        $data = SetNeracaRumus::find($request->id);

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {

            $act =SetNeracaRumus::find($request->id)->delete();
            DB::commit();
            message($act, "Berhasil hapus data", "Gagal hapus data");
            return redirect('setting-rumus-neraca/index');
        }
        catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }
}
