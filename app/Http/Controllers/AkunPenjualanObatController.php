<?php

namespace App\Http\Controllers;
use App\tarif;
use App\kelas;
use App\SettingCoa;
use App\Models\Perkiraan;
use DB;
use Illuminate\Http\Request;

class AkunPenjualanObatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-akun-pendapatan-obat');
    }

    public function index ()
    {
        $perkiraan = Perkiraan::toBase()->get(['id', 'nama']);
        $kelas = kelas::toBase()->get(['id', 'nama']);
        $settingCoa = DB::table('setting_coa')
        ->selectRaw('setting_coa.id, setting_coa.type_obat, keterangan, perkiraan.nama as perkiraan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('jenis', 'Pendapatan Obat')
        ->get();

        return view ('akun-penjualan-obat/index', compact('perkiraan', 'kelas', 'settingCoa'));
    }

    public function edit (Request $request)
    {
        $data = SettingCoa::select('setting_coa.id', 'setting_coa.keterangan as nama_obat', 'perkiraan.nama as perkiraan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('setting_coa.id', $request->id)
        ->firstOrFail();

        echo json_encode($data);
    }

    public function simpanObat (Request $request)
    {
        $data= $request->all();
        DB::beginTransaction();

        try {

            $request->validate(['id_perkiraan'=>'required',]);

            for ($i=0; $i<count($data['id_perkiraan']); $i++)
            {
                $setting = array (
                    'keterangan'=>$request->keterangan,
                    'type'=>$request->type,
                    'type_obat'=>$request->type_obat,
                    'type_bayar'=>$request->type_bayar,
                    'tipe_pasien'=>$request->type_pasien,
                    'id_kelas'=>$data['id_kelas'][$i],
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'user_input'=>$request->user_input,);
                $act = SettingCoa::insert($setting);
            }

            DB::commit();
            message($act, 'Setting Akun Penjualan Obat berhasil disimpan', 'Setting akun penjualan Obat gagal disimpan');
            return redirect('akun-penjualan-obat/index');
        }
        catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
        }
    }

    public function update (Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();

        try {

            $request->validate([
                'id'=>'required',
                'id_perkiraan'=>'required',
            ]);

            $act = SettingCoa::find($request->id)->update([
                'id_perkiraan'=>$request->id_perkiraan,
                'user_update'=>$request->user_update]);

            DB::commit();
            message($act, 'Setting Akun Penjualan Obat berhasil di update ', 'Setting akun penjualan Obat gagal di update');
            return redirect ('akun-penjualan-obat/index');
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
        }
    }
}
