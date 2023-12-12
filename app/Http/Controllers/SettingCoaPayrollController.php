<?php

namespace App\Http\Controllers;
use DB;
use App\SettingCoaPayroll;
use App\SettingCoaPayrollDua;
use Illuminate\Http\Request;

class SettingCoaPayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-coa-payroll');
    }

    public function index()
    {
        $perkiraan = DB::table('perkiraan')->select('id', 'nama')->get();
        $komponen = DB::table('setting_coa_payroll')->select('komponen')->groupBy('komponen')->get();
        $setting = DB::table('setting_coa_payroll')
        ->selectRaw('setting_coa_payroll.id, perkiraan.nama as rekening, komponen, flag_aktif')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa_payroll.id_perkiraan')
        ->limit(50)
        ->get();

        return view('setting-coa-payroll/index', compact('perkiraan', 'setting', 'komponen'));
    }

    public function pencarian (Request $request)
    {

        $status = $request->status;

        $perkiraan = DB::table('perkiraan')->select('id', 'nama')->get();
        $komponen = DB::table('setting_coa_payroll')->select('komponen')->groupBy('komponen')->get();
        $setting = DB::table('setting_coa_payroll')
        ->selectRaw('setting_coa_payroll.id, perkiraan.nama as rekening, komponen, flag_aktif')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa_payroll.id_perkiraan')
        ->where('komponen', $request->komponen)
        ->where('id_perkiraan', $request->id_perkiraan)
        ->where(function($query) use($status){
            if ($status == 'Y'){
                $query->where('flag_aktif', $status);
            } else if($status == 'N'){
                $query->where('flag_aktif', $status);
            }
        })
        ->limit(50)
        ->get();

        return view('setting-coa-payroll/index', compact('perkiraan', 'setting', 'komponen'));
    }

    public function setCoa()
    {
        $settingCoa = SettingCoaPayrollDua::selectRaw('setting_coa_payroll_dua.id, setting_coa_payroll_dua.nama,
        perkiraan.kode_rekening, perkiraan.nama as perkiraan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa_payroll_dua.id_perkiraan')
        ->paginate(50);

        return view ('setting-coa-payroll/pajak-dan-biaya-adm', compact('settingCoa'));
    }

    public function edit(Request $request)
    {
        $data = SettingCoaPayroll::select('setting_coa_payroll.id', 'komponen', 'id_perkiraan', 'flag_aktif')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa_payroll.id_perkiraan')
        ->where('setting_coa_payroll.id', $request->id)
        ->first();

        echo json_encode($data);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'id_perkiraan'=>'required',
            ]);

            if (isset($request->id_perkiraan) && isset($request->id))
            {
                $act = SettingCoaPayroll::where('id', $request->id)->update([
                    'id_perkiraan' =>$request->id_perkiraan,
                    'flag_aktif'=>$request->flag_aktif]);

                DB::commit();
                message($act, 'Setting COA Payroll berhasil diupate', 'Setting COA Payroll gagal di update');
                return redirect('setting-coa-payroll/index');
            }
        }
        catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Setting COA Payroll gagal update');
            return redirect('setting-coa-payroll/index');
        }
    }

    public function editDua(Request $request)
    {
        $data = SettingCoaPayrollDua::find($request->id);

        echo json_encode($data);
    }

    public function updateDua(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'id_perkiraan'=>'required',
            ]);

            if (isset($request->id_perkiraan) && isset($request->id))
            {
                $act = SettingCoaPayrollDua::where('id', $request->id)->update(['id_perkiraan' =>$request->id_perkiraan]);

                DB::commit();
                message($act, 'Setting COA Payroll berhasil diupate', 'Setting COA Payroll gagal di update');
                return redirect('setting-coa-payroll/pajak-dan-biaya-adm');
            }
        }
        catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Setting COA Payroll gagal update');
            return redirect('setting-coa-payroll/pajak-dan-biaya-adm');
        }
    }
}
