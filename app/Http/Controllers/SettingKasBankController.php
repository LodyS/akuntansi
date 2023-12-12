<?php

namespace App\Http\Controllers;

use App\Models\KasBank;
use Auth;
use DB;
use App\Models\Perkiraan;
use App\Http\Requests\SettingCoa;
use Illuminate\Http\Request;

class SettingKasBankController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-kas-bank');
    }

    public function index()
    {
        $setting_coa = KasBank::leftJoin('perkiraan', 'perkiraan.id', 'kas_bank.id_perkiraan')
        ->get(['kas_bank.id', 'kas_bank.nama as nama', 'perkiraan.nama as perkiraan', 'kode_bank']);

        $array = [];
        $data = $setting_coa->map(function($items){
            $array['id'] = $items['id'];
            $array['nama'] = strtoupper($items['nama']);
            $array['perkiraan'] = $items['perkiraan'];
            $array['kode_bank'] = 'BK-'.$items['kode_bank'];

            return $array;
        });

        return view('setting-kas-bank/index', compact('data'));
    }

    public function edit (Request $request)
    {
        $data = KasBank::select('kas_bank.id', 'kas_bank.nama', 'id_perkiraan', 'perkiraan.nama as perkiraan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kas_bank.id_perkiraan')
        ->where('kas_bank.id', $request->id)
        ->first();

        echo json_encode($data);
    }

    public function update (SettingCoa $request)
    {
        DB::beginTransaction();

        try {

            $act = KasBank::where('id', $request->id)->update(['id_perkiraan' =>$request->id_perkiraan]);

            DB::commit();
            message($act, 'Setting COA Kas Bank Berhasil disimpan', 'Setting COA Kas Bank gagal disimpan');
            return redirect('setting-kas-bank/index');

        }
        catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false, '', 'Setting COA Kas Bank gagal disimpan');
            return redirect('setting-kas-bank/index');
        }
    }
}
