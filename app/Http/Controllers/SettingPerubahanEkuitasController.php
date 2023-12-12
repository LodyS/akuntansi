<?php

namespace App\Http\Controllers;
use DB;
use App\SetLapEkuitasDetail;
use App\Models\Perkiraan;
use App\Models\SetLapEkuita;
use Illuminate\Http\Request;
use App\Http\Requests\SettingEkuitas;

class SettingPerubahanEkuitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-perubahan-ekuitas');
    }

    public function index()
    {
        $data = DB::table('set_lap_ekuitas')
        ->selectRaw('set_lap_ekuitas.id, set_lap_ekuitas.kode, set_lap_ekuitas.nama, set_lap_ekuitas.level, dua.nama as induk,
        case when set_lap_ekuitas.jenis = "-1" then "Pengurang"
        when set_lap_ekuitas.jenis= "1" then "Penambah" else ""end as jenis')
        ->leftJoin('set_lap_ekuitas as dua', 'dua.id', 'set_lap_ekuitas.induk')
        ->paginate(25);

        return view('setting-perubahan-ekuitas/index', compact('data'));
    }

    public function detail (Request $request)
    {
        $id_set_lap_ekuitas = $request->id;
        $nama = SetLapEkuita::select('nama')->where('id', $request->id)->where('level', '>', '0')->firstOrFail();
        $data = DB::table('set_lap_ekuitas_detail')
        ->selectRaw('set_lap_ekuitas_detail.id, setting_surplus_defisit.nama as komponen_surplus_defisit, perkiraan.kode_rekening,
        perkiraan.nama as rekening_coa')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_lap_ekuitas_detail.id_set_surplus_defisit')
        ->leftJoin('perkiraan', 'perkiraan.id', 'set_lap_ekuitas_detail.id_perkiraan')
        ->where('set_lap_ekuitas_detail.id_set_lap_ekuitas', $request->id)
        ->paginate(25);

        return view('setting-perubahan-ekuitas/detail', compact('data', 'id_set_lap_ekuitas', 'nama'));
    }

    public function tambah(Request $request)
    {
        $aksi = "Tambah";
        $id = $request->id;
        $setting_surplus = DB::table('setting_surplus_defisit')->selectRaw('id, nama')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $data =  SetLapEkuita::selectRaw('set_lap_ekuitas.kode, set_lap_ekuitas.nama, dua.nama as induk')
        ->leftJoin('set_lap_ekuitas as dua', 'dua.id', 'set_lap_ekuitas.induk')
        ->where('set_lap_ekuitas.id', $request->id)
        ->firstOrFail();

        return view("setting-perubahan-ekuitas/form-tambah", compact('data','setting_surplus', 'perkiraan', 'aksi', 'id'));
    }

    public function edit(Request $request)
    {
        $aksi = "Tambah";
        $id = $request->id;
        $setting_surplus = DB::table('setting_surplus_defisit')->selectRaw('id, nama')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $data = SetLapEkuitasDetail::selectRaw('set_lap_ekuitas.kode, set_lap_ekuitas.id as id_set_lap_ekuitas,set_lap_ekuitas.nama,
        dua.nama as induk, id_set_surplus_defisit, id_perkiraan, id_set_lap_ekuitas')
        ->leftJoin('set_lap_ekuitas', 'set_lap_ekuitas.id', 'set_lap_ekuitas_detail.id_set_lap_ekuitas')
        ->leftJoin('set_lap_ekuitas as dua', 'dua.id', 'set_lap_ekuitas.induk')
        ->where('set_lap_ekuitas_detail.id', $request->id)
        ->firstOrFail();

        return view("setting-perubahan-ekuitas/form-edit", compact('data','setting_surplus', 'perkiraan', 'aksi', 'id'));
    }

    public function store (SettingEkuitas $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_set_lap_ekuitas;
            $act = SetLapEkuitasDetail::create($request->all());
            DB::commit();
            message($act, 'Berhasil disimpan', 'Gagal simpan');
            return redirect('setting-perubahan-ekuitas/detail/'.$id);
        }
        catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            //return 'Gagal sistem karena error sistem';
            return back()->withError('Invalid data');
        }
    }

    public function update(SettingEkuitas $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_set_lap_ekuitas;
            $act = SetLapEkuitasDetail::find($request->id)->update($request->all());
            DB::commit();
            message($act, 'Berhasil di update', 'Gagal diupdate');
            return redirect('setting-perubahan-ekuitas/detail/'.$id);
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    public function delete (Request $request)
    {
        $data = SetLapEkuitasDetail::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_set_lap_ekuitas;
            $act =SetLapEkuitasDetail::where('id', $request->id)->delete();
            DB::commit();
            message($act, "Berhasil hapus data", "Gagal hapus data");
            return redirect('setting-perubahan-ekuitas/detail/'.$id);
        }
        catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }
}
