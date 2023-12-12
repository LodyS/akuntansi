<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Perkiraan;
use App\SettingCoa;
use App\tarif;
use Illuminate\Http\Request;

class SettingCoaTarifController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-coa-tarif');
    }

    public function index ()
    {
        return view ('setting-coa-tarif/index');
    }

    public function RawatJalan ()
    {
        $tariff = DB::table('tarif')->selectRaw('tarif.id, layanan.nama as layanan')->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $tarif = DB::table('tarif')
        ->selectRaw('tarif.id, id_kelas, kelas.nama as kelas, layanan.nama as nama_tarif, tarif.total')
        ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
        ->leftJoin('kelas', 'kelas.id', 'tarif.id_kelas')
        ->where('flag_setting_rj', '<>', 'Y')
        ->paginate(20);

        $status = DB::table('tarif')
        ->selectRaw('id')
        ->where('flag_setting_rj', '<>', 'Y')
        ->first();

        return view ('setting-coa-tarif/rawat-jalan', compact('tarif', 'perkiraan', 'tariff', 'status'));
    }

    public function RawatInap ()
    {
        $tariff = DB::table('tarif')->selectRaw('tarif.id, layanan.nama as layanan')->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $tarif = DB::table('tarif')
        ->selectRaw('tarif.id, id_kelas, kelas.nama as kelas, layanan.nama as nama_tarif, tarif.total')
        ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
        ->leftJoin('kelas', 'kelas.id', 'tarif.id_kelas')
        ->where('flag_setting_ri', '<>', 'Y')
        ->paginate(20);

        $status = DB::table('tarif')
        ->selectRaw('id')
        ->where('flag_setting_ri', '<>', 'Y')
        ->first();

        return view ('setting-coa-tarif/rawat-inap', compact('tarif', 'perkiraan', 'tariff', 'status'));
    }

    public function cari (Request $request)
    {
        $perkiraan = $request->id_perkiraan;
        $tarif = $request->id_tarif;
        $asal = $request->asal;

        if ($perkiraan == null && $tarif == null)
        {
            switch($asal)
            {
                case 'RI';
                message($asal,'Parameter pencarian kosong'. 'Parameter pencarian kosong');
                return redirect('setting-coa-tarif/rawat-inap');
                break;

                case 'RJ';
                message($asal, 'Parameter pencarian kosong'. 'Parameter pencarian kosong');
                return redirect('setting-coa-tarif/rawat-jalan');
                break;
            }
        }

        if (isset($tarif) || isset($perkiraan))
        {
            $data = DB::table('setting_coa')
            ->selectRaw('setting_coa.id, setting_coa.keterangan, perkiraan.nama as perkiraan, tarif.total')
            ->selectRaw('layanan.nama as tarif, kelas.nama as kelas, tipe_pasien.tipe_pasien')
            ->leftJoin('tarif', 'setting_coa.id_tarif', 'tarif.id')
            ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
            ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
            ->leftJoin('kelas', 'kelas.id', 'tarif.id_kelas')
            ->leftJoin('tipe_pasien', 'tipe_pasien.id', 'setting_coa.tipe_pasien')
            ->where('keterangan', 'Pendapatan Jasa')
            ->when($tarif, function($query, $tarif){
                return $query->where('id_tarif', $tarif);
            })
            ->when($id_perkiraan, function($query, $id_perkiraan){
                return $query->where('id_perkiraan', $id_perkiraan);
            })
            ->paginate(15);

            return view('setting-coa-tarif/cari-setting-tarif', compact('data'));
        }
    }

    public function edit (Request $request)
    {
        $data = SettingCoa::selectRaw('setting_coa.id, perkiraan.nama as perkiraan, layanan.nama as layanan, kelas.nama as kelas, tarif.total')
        ->leftJoin('tarif', 'tarif.id', 'setting_coa.id_tarif')
        ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
        ->leftJoin('kelas', 'kelas.id', 'tarif.id_kelas')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('setting_coa.id', $request->id)
        ->firstOrFail();

        echo json_encode($data);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'id'=>'required',
                'id_perkiraan'=>'required',
            ]);

            $act = SettingCoa::where('id', $request->id)->update(['id_perkiraan'=>$request->id_perkiraan]);
            DB::commit();
            message($act, 'Setting COA Tarif berhasil di update', 'Setting COA Tarif gagal di updtae');
            return redirect ('setting-coa-tarif/index');
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            message(false, 'Setting COA Tarif berhasil di update', 'Setting COA Tarif gagal di updtae');
            return redirect ('setting-coa-tarif/index');
        }
    }

    public function SimpanSettingTarif (Request $request)
    {
        $data = $request->all();

        try {

            DB::beginTransaction();

            $request->validate([
                'id_tarif'=>'required',
                'id_perkiraan'=>'required',
            ]);


            if ($request->status == 'Ada')
            {
                for ($i=0; $i<count($data['id_tarif']); $i++)
                {
                    $satu = array(
                        'keterangan'=>'Pendapatan Jasa',
                        'id_tarif'=>$data['id_tarif'][$i],
                        'type'=>$request->type,
                        'tipe_pasien'=>1,
                        'type_bayar'=>'Kredit',
                        'type_obat'=>$data['centang'][$i],
                        'id_kelas'=>$data['id_kelas'][$i],
                        'id_perkiraan'=>$request->id_perkiraan,
                        'user_input'=>$request->user_input,);

                    SettingCoa::insert($satu);
                }

                for ($i=0; $i<count($data['id_tarif']); $i++)
                {
                    $dua = array(
                        'keterangan'=>'Pendapatan Jasa',
                        'id_tarif'=>$data['id_tarif'][$i],
                        'type'=>$request->type,
                        'tipe_pasien'=>2,
                        'type_bayar'=>'Kredit',
                        'type_obat'=>$data['centang'][$i],
                        'id_kelas'=>$data['id_kelas'][$i],
                        'id_perkiraan'=>$request->id_perkiraan,
                        'user_input'=>$request->user_input,);

                    SettingCoa::insert($dua);
                }

                for ($i=0; $i<count($data['id_tarif']); $i++)
                {
                    $tiga = array(
                        'keterangan'=>'Pendapatan Jasa',
                        'id_tarif'=>$data['id_tarif'][$i],
                        'type'=>$request->type,
                        'tipe_pasien'=>2,
                        'type_bayar'=>'Tunai',
                        'type_obat'=>$data['centang'][$i],
                        'id_kelas'=>$data['id_kelas'][$i],
                        'id_perkiraan'=>$request->id_perkiraan,
                        'user_input'=>$request->user_input,);

                    SettingCoa::insert($tiga);
                }

                DB::table('setting_coa')
                ->where('type_obat', 'N')
                ->where('keterangan', 'Pendapatan Jasa')
                ->where('type', $request->type)
                ->delete();

                if ($request->type == 'RJ')
                {
                    $update_visit = "UPDATE tarif SET flag_setting_rj='Y' WHERE id IN (SELECT id_tarif FROM setting_coa WHERE type_obat='Y')";
                    DB::statement($update_visit);

                } else {

                    $update_visit = "UPDATE tarif SET flag_setting_ri='Y' WHERE id IN (SELECT id_tarif FROM setting_coa WHERE type_obat='Y')";
                    DB::statement($update_visit);
                }
            }
            DB::commit();

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
        }

        if ($request->status == 'Ada')
        {
            switch($request->type)
            {
                case 'RI';
                return redirect('setting-coa-tarif/rawat-inap')->with('success', 'Setting COA Tarif berhasil disimpan');
                break;

                case 'RJ';
                return redirect('setting-coa-tarif/rawat-jalan')->with('success', 'Setting COA Tarif berhasil disimpan');
                break;
            }
        } else {
            switch($request->type)
            {
                case 'RI';
                message(false, '', 'Gagal simpan Setting COA Tarif karena Tarif Kosong');
                return redirect('setting-coa-tarif/rawat-inap')->with('danger', 'Gagal simpan Setting COA Tarif karena Tarif Kosong');
                break;

                case 'RJ';
                message(false, '', 'Gagal simpan Setting COA Tarif karena Tarif Kosong');
                return redirect('setting-coa-tarif/rawat-jalan')->with('danger', 'Gagal simpan Setting COA Tarif Karena Tarif Kosong');
                break;
            }
        }
    }
}
