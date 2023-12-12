<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Unit;
use App\SettingCoaJasaPegawai;
use Illuminate\Http\Request;

class SettingCoaJasaPegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-coa-jasa-pegawai');
    }

    public function index()
    {
        $unit = DB::table('unit')->selectRaw('id, nama')->paginate(50);
        $yunit = DB::table('unit')->selectRaw('id, nama')->get();
        return view ('setting-coa-jasa-pegawai/index', compact('unit', 'yunit'));
    }

    public function cari(Request $request)
    {
        $unit = Unit::selectRaw('id, nama')->where('id', $request->id_unit)->firstOrFail();
        $yunit = DB::table('unit')->selectRaw('id, nama')->get();
        return view('setting-coa-jasa-pegawai/cari', compact('unit', 'yunit'));
    }

    public function tambah ()
    {
        $perkiraan = DB::table('perkiraan')->selectRaw('id, nama')->get();
        $unit = DB::table('unit')->selectRaw('id, nama')->get();
        $jasaPegawai = DB::table('jasa_pegawai')->selectRaw('id, nama')->get();

        return view('setting-coa-jasa-pegawai/tambah', compact('perkiraan', 'unit', 'jasaPegawai'));
    }

    public function detail (Request $request)
    {
        $unit = Unit::select('nama')->where('id', $request->id)->firstOrFail();
        $settingCoaJasaPegawai = DB::table('setting_coa_jasa_pegawai')
        ->selectRaw('setting_coa_jasa_pegawai.id, jasa_pegawai.nama as komponen, perkiraan.nama as rekening')
        ->leftJoin('jasa_pegawai', 'jasa_pegawai.id', 'setting_coa_jasa_pegawai.id_jasa_pegawai')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa_jasa_pegawai.id_perkiraan')
        ->where('id_unit', $request->id)
        ->paginate(50);

        return view('setting-coa-jasa-pegawai/detail', compact('unit', 'settingCoaJasaPegawai'));
    }

    public function edit (Request $request)
    {
        $data = SettingCoaJasaPegawai::selectRaw
        ('setting_coa_jasa_pegawai.id, concat(unit.nama, " - ", jasa_pegawai.nama) as nama, perkiraan.nama as rekening')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa_jasa_pegawai.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'setting_coa_jasa_pegawai.id_unit')
        ->leftJoin('jasa_pegawai', 'jasa_pegawai.id', 'setting_coa_jasa_pegawai.id_jasa_pegawai')
        ->where('setting_coa_jasa_pegawai.id', $request->id)
        ->first();

        echo json_encode($data);
    }


    public function delete (Request $request)
    {
        $data = SettingCoaJasaPegawai::selectRaw('setting_coa_jasa_pegawai.id, concat(unit.nama, " - ", jasa_pegawai.nama) as nama')
        ->leftJoin('unit', 'unit.id', 'setting_coa_jasa_pegawai.id_unit')
        ->leftJoin('jasa_pegawai', 'jasa_pegawai.id', 'setting_coa_jasa_pegawai.id_jasa_pegawai')
        ->where('setting_coa_jasa_pegawai.id', $request->id)
        ->first();

        echo json_encode($data);
    }

    public function simpan (Request $request)
    {
        $request->validate([
            'id_unit'=>'required',
            'id_perkiraan'=>'required',
            'id_jasa_pegawai'=>'required',
        ]);

        $data = $request->all();
        $check = DB::table('setting_coa_jasa_pegawai')
        ->selectRaw('COUNT(id_unit) AS unit, COUNT(id_jasa_pegawai) AS jasa_pegawai')
        ->where('id_unit', $request->id_unit)
        ->whereIn('id_jasa_pegawai', $request->id_jasa_pegawai)
        ->first();

        DB::beginTransaction();

        try {
            if ($check->unit == 0 && $check->jasa_pegawai == 0)
            {
                for($i=0; $i<count($data['id_jasa_pegawai']); $i++)
                {
                    $input = array(
                        'id_unit'=>$request->id_unit,
                        'id_jasa_pegawai'=>$data['id_jasa_pegawai'][$i],
                        'id_perkiraan'=>$data['id_perkiraan'][$i],);

                    SettingCoaJasaPegawai::insert($input);
                }
            }
            DB::commit();
        }
        catch (Exception $e){
            DB::rollback();
        }

        if ($check->unit > 0 && $check->jasa_pegawai > 0)
        {
            message(false, '', 'Gagal simpan Setting COA Jasa Pegawai karena Unit dan Jasa Pegawai sudah ada');
            return redirect('setting-coa-jasa-pegawai/tambah')->with('danger', 'Setting COA Jasa Pegawai gagal disimpan');
        } else {
            return redirect('setting-coa-jasa-pegawai/index')->with('success', 'Setting COA Jasa Pegawai berhasil disimpan');
        }
    }

    public function update (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->id_perkiraan == null && $request->id == null)
            {
                message(false, '', 'Setting COA Jasa Pegawai gagal disimpan');
                return redirect('setting-coa-jasa-pegawai/detail');
            }

            if (isset($request->id_perkiraan) && isset($request->id))
            {
                $act = SettingCoaJasaPegawai::where('id', $request->id)->update(['id_perkiraan' =>$request->id_perkiraan]);

                DB::commit();
                message($act, 'Setting COA Jasa Pegawai Berhasil disimpan', 'Setting COA Jasa Pegawai gagal disimpan');
                return redirect('setting-coa-jasa-pegawai/index');
            }
        }
        catch (Exception $e) {
            DB::rollback();
            return 'Gagal simpan';
        }
    }

    public function remove(Request $request)
    {
        DB::beginTransaction();

        try {
            SettingCoaJasaPegawai::where('id', $request->id)->update(['id_perkiraan'=>null]);
            DB::commit();
            message(true, 'Setting COA Jasa Pegawai Berhasil di update', 'Setting COA Jasa Pegawai gagal di update');
            return redirect('setting-coa-jasa-pegawai/index');
        } catch (Exception $e){
            DB::rollback();
        }
    }
}
