<?php

namespace App\Http\Controllers;
use App\tarif;
use App\Models\Kelas;
use App\SettingCoa;
use App\layanan;
use App\Models\Perkiraan;
use DB;
use Illuminate\Http\Request;

class AkunPendapatanJasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-akun-pendapatan-jasa');
    }

    public function index ()
    {
        $tipe_pasien = DB::table('tipe_pasien')->get(['id', 'tipe_pasien']);

        return view ('akun-pendapatan-jasa/index', compact('tipe_pasien'));
    }

    public function pencarian (Request $request)
    {
        $coa = DB::table('setting_coa')
        ->select('setting_coa.id', 'perkiraan.nama as perkiraan', 'kelas.nama as kelas', 'layanan.nama as layanan')
        ->leftJoin('kelas', 'kelas.id', 'setting_coa.id_kelas')
        ->leftJoin('tarif', 'tarif.id', 'setting_coa.id_tarif')
        ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('Keterangan', 'Pendapatan Jasa')
        ->where('tipe_pasien', $request->tipe_pasien)
        ->where('setting_coa.type', $request->tipe_kunjungan)
        ->where('type_bayar', $request->tipe_bayar)
        ->paginate(25); // untuk menampilkan data yang akan di update

        return view ('akun-pendapatan-jasa/pencarian', compact('coa'));
    }

    public function edit (Request $request)
    {
        $data = SettingCoa::selectRaw('setting_coa.id, layanan.nama as layanan, perkiraan.nama as perkiraan')
        ->where('setting_coa.id', $request->id)
        ->leftJoin('tarif', 'tarif.id', 'setting_coa.id_tarif')
        ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->firstOrFail();

        echo json_encode($data);
    }

    public function UpdateJasa (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->id_perkiraan == null && $request->id == null)
            {
                message(false, '', 'Setting COA Pendapatan Jasa gagal disimpan');
                return redirect('akun-pendapatan-jasa');
            }

            if (isset($request->id_perkiraan) && isset($request->id))
            {
                $act = SettingCoa::where('id', $request->id)->update([
                    'id_perkiraan' =>$request->id_perkiraan,
                    'user_update'  =>$request->user_update]);

                DB::commit();
                message($act, 'Setting COA Pendapatan Jasa Berhasil disimpan', 'Setting COA Pendapatan Jasa gagal disimpan');
                return redirect('akun-pendapatan-jasa');
            }
        }
        catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Setting COA Pendapatan Jasa gagal disimpan');
            return redirect('akun-pendapatan-jasa');
        }
    }
}
