<?php

namespace App\Http\Controllers;
use App\SettingCoa;
use App\Models\Perkiraan;
use Illuminate\Http\Request;
use DB;

class SettingCoaPembayaranOlehPasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-coa-pembayaran-oleh-pasien');
    }

    public function index ()
    {
        $setting_coa = SettingCoa::selectRaw('setting_coa.id, keterangan as nama, perkiraan.nama as perkiraan')
        ->join('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('jenis', 'Pembayaran')
        ->get();

        return view ('setting-coa-pembayaran-oleh-pasien/index', compact('setting_coa'));
    }

    public function edit (Request $request)
    {
        $data = SettingCoa::select('setting_coa.id', 'keterangan as nama', 'perkiraan.nama as perkiraan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('setting_coa.id', $request->id)
        ->firstOrFail();

        echo json_encode($data);
    }

    public function update (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->id_perkiraan == null)
            {
                message(false, '', 'Setting COA Pendapatan Jasa gagal disimpan');
                return redirect('akun-pendapatan-jasa');
            }

            if (isset($request->id_perkiraan))
            {
                $act = SettingCoa::where('id', $request->id)->update(['id_perkiraan' =>$request->id_perkiraan, 'user_update'=>$request->user_update]);

                DB::commit();
                message($act, 'Setting COA Pembayaran Oleh Pasien Berhasil disimpan', 'Setting COA Pembayaran oleh pasien gagal disimpan');
                return redirect('setting-coa-pembayaran-oleh-pasien/index');
            }
        }
        catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Setting COA Pembayaran oleh pasien gagal disimpan');
            return redirect('setting-coa-pembayaran-oleh-pasien/index');
        }
    }
}
