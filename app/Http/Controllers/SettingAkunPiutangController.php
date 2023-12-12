<?php

namespace App\Http\Controllers;
use App\SettingCoa;
use App\Models\Perkiraan;
use App\tipe_pasien;
use Auth;
use DB;
use Illuminate\Http\Request;

class SettingAkunPiutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-akun-piutang');
    }

    public function index ()
    {
        $tipe_pasien = tipe_pasien::pluck('tipe_pasien', 'id');
        return view ('setting-akun-piutang/index', compact('tipe_pasien'));
    }

    public function rawat(Request $request)
    {
        $query = SettingCoa::where('Keterangan', 'Piutang')->where('type', $request->tipe)->where('tipe_pasien', $request->tipe_pasien);

        if ($request->tipe == 'RJ')
        {
            $data = [
                'tipe'=>$request->tipe,
                'tipe_pasien'=>$request->tipe_pasien,
                'perkiraan'=>Perkiraan::pluck('nama', 'id'),
                'setting'=>(clone $query)->first(),
                'pasien'=>(clone $query)->where('jenis', 'Pasien Masih Dirawat RJ')->first(),
                'penagihan'=>(clone $query)->where('jenis', 'Penagihan Piutang RJ')->first(),
                'pelunasan'=>(clone $query)->where('jenis', 'Pelunasan Piutang RJ')->first()
            ];

            return view('setting-akun-piutang/rawat-jalan')->with($data);

        } else {

            $data = [
                'tipe'=>$request->tipe,
                'tipe_pasien'=>$request->tipe_pasien,
                'perkiraan'=>Perkiraan::pluck('nama', 'id'),
                'setting'=>(clone $query)->first(),
                'piutang'=>(clone $query)->where('jenis', 'Piutang Pasien Masih Dirawat RI')->first(),
                'penagihan'=>(clone $query)->where('jenis', 'Penagihan Piutang RI')->first(),
                'pasienPulang'=>(clone $query)->where('jenis', 'Piutang Pasien Pulang Rawat RI')->first(),
                'pelunasan'=>(clone $query)->where('jenis', 'Pelunasan Piutang RI')->first()
            ];

            return view('setting-akun-piutang/rawat-inap')->with($data);
        }
    }

    public function store (Request $request)
    {
        $id_user = Auth::user()->id;
        $request->validate(['id_perkiraan'=>'required',]);
        $data = $request->all();
        DB::beginTransaction();

        try {

            for ($i=0; $i<count($data['id_perkiraan']); $i++)
            {
                $act = array(
                    'keterangan'=>$request->keterangan,
                    'jenis'=>$data['jenis'][$i],
                    'type'=>$request->type,
                    'tipe_pasien'=>$request->tipe_pasien,
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'user_input'=>$id_user,);

                SettingCoa::insert($act);
            }

            DB::commit();
            message($act, 'Setting Akun Piutang Berhasil Disimpan', 'Setting Akun Piutang Gagal disimpan');
            return redirect ('setting-akun-piutang/index');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    public function update (Request $request)
    {
        $request->validate(['id'=>'required', 'id_perkiraan'=>'required',]);
        $id_user = Auth::user()->id;
        DB::beginTransaction();

        try {

            $jumlah = count($request->id);
            for ($i=0; $i<$jumlah; $i++)
            {
                DB::table('setting_coa')->where('id', $request->id[$i])->update([
                    'id_perkiraan'=>$request->id_perkiraan[$i],
                    'user_update'=>$id_user,
                ]);
            }

            DB::commit();
            message(true, 'Setting Akun Piutang Berhasil Di update', 'Setting Akun Piutang Berhasil Di update');
            return redirect ('setting-akun-piutang/index');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }
}
