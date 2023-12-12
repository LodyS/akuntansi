<?php

namespace App\Http\Controllers;
use App\SettingCoa;
use App\Models\Perkiraan;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests\SettingCoa as SetingCoa;
use Illuminate\Support\Facades\Input;

class SettingAkunPajakController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-akun-pajak');
    }

    public function index ()
    {
        $query = SettingCoa::where('keterangan', 'Pajak');

        $data = [
            'perkiraan'=>Perkiraan::get(['id', 'nama']),
            'setting'=>(clone $query)->first(),
            'pajakMasukan'=>(clone $query)->where('jenis', 'Pajak Masukan')->first(),
            'pajakKeluaran'=>(clone $query)->where('jenis', 'Pajak Keluaran')->first(),
            'pphBadan'=>(clone $query)->where('jenis', 'PPh Badan')->first(),
            'pphKaryawan'=>(clone $query)->where('jenis', 'PPh PS 21 Karyawan')->first(),
            'pphDokter'=>(clone $query)->where('jenis', 'PPh 21 Dokter')->first(),
            'pphDuaSatu'=>(clone $query)->where('jenis', 'Hutang PPh PS 21')->first(),
            'pphDuaLima'=>(clone $query)->where('jenis', 'Hutang PPh PS 25')->first(),
            'pphDuaEnam'=>(clone $query)->where('jenis', 'Hutang PPh PS 26')->first(),
            'pphDuaSembilan'=>(clone $query)->where('jenis', 'Hutang PPh PS 29')->first(),
            'hutangPbb'=>(clone $query)->where('jenis', 'Hutang PBB')->first(),
        ];

        return view ('setting-akun-pajak/index')->with($data);
    }

    public function simpanSettingPajak (SetingCoa $request)
    {
        $data = $request->all();
        DB::beginTransaction();

        try {
            for($i=0; $i<count($data['id_perkiraan']); $i++){
                $setting = array (
                    'keterangan'=>$request->keterangan,
                    'jenis'=>$data['jenis'][$i],
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'user_input'=>$request->user_input,);

                $act = SettingCoa::insert($setting);
            }
            DB::commit();
            message($act, 'Setting Akun Pajak Berhasil disimpan', 'Setting Akun pajak gagal disimpan');
            return redirect ('/setting-akun-pajak');
        }
        catch (Exception $e)
        {
            DB::rollback();
            message(false, 'Setting Akun Pajak gagal disimpan', 'Setting Akun pajak gagal disimpan');
            return redirect ('/setting-akun-pajak');
        }
    }

    public function updateSettingPajak (SetingCoa $request)
    {
        DB::beginTransaction();

        try {

            $jumlah = count($request->id);
            for ($i=0; $i<$jumlah; $i++)
            {
                DB::table('setting_coa')->where('id', $request->id[$i])->update([
                    'id_perkiraan'=>$request->id_perkiraan[$i],
                    'user_update'=>$request->user_update,
                ]);
            }

            DB::commit();
            message(true, 'Setting Akun Pajak berhasil simpan', 'Setting Akun Pajak Gagal simpan');
            return redirect ('/setting-akun-pajak');
        }
        catch (Exception $e)
        {
            DB::rollback();
        }
    }
}
