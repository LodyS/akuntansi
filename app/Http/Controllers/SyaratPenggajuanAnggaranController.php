<?php

namespace App\Http\Controllers;
use App\SyaratAnggaran;
use App\DetailSyaratAnggaran;
use DB;
use App\Http\Requests\SyaratPenggajuanAnggaran;
use Illuminate\Http\Request;

class SyaratPenggajuanAnggaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-syarat-penggajuan-anggaran');
    }

    public function index ()
    {
        return view ('syarat-penggajuan-anggaran/index');
    }

    public function simpan (SyaratPenggajuanAnggaran $request)
    {
        $data = $request->all();

        try {

            DB::beginTransaction();

            $act = new SyaratAnggaran;
            $act->nama = $request->nama;
            $act->keterangan = $request->keterangan;
            $act->save();

            for ($i=0; $i<count($data['syarat']); $i++)
            {
				$insert = array (
					'id_syarat_anggaran'=>$act->id,
					'syarat'=>$data['syarat'][$i],);

				DetailSyaratAnggaran::create($insert);
			}

            DB::commit();
            message($act, 'Syarat Penggajuan Anggaran Berhasil disimpan', '');
			return redirect ('syarat-penggajuan-anggaran/index');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false,'', 'Gagal simpan');
            return redirect('syarat-penggajuan-anggaran/index');
        }
    }
}
