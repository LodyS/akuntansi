<?php

namespace App\Http\Controllers;
use App\SettingCoa;
use App\Models\Perkiraan;
use DB;
use App\Http\Requests\SettingCoa as SetingCoa;
use Illuminate\Http\Request;

class SettingAkunHutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-akun-hutang');
    }

    public function index ()
    {
        $query = SettingCoa::where('keterangan', 'Hutang Jangka Pendek');

        $data = [
            'perkiraan'=>Perkiraan::get(['id', 'nama']),
            'setting'=>(clone $query)->first(),
            'hutangSupplierObat'=>(clone $query)->where('jenis', 'Hutang Supplier Obat')->first(),
            'hutangSupplierLogistik'=>(clone $query)->where('jenis', 'Hutang Supplier Logistik')->first(),
            'deposito'=>(clone $query)->where('jenis', 'Deposito')->first(),
            'honorDokter'=>(clone $query)->where('jenis', 'Honor Dokter')->first(),
            'hutangGajiKaryawan'=>(clone $query)->where('jenis', 'Hutang Gaji Karyawan')->first(),
            'iuranAstek'=>(clone $query)->where('jenis', 'Iuran Astek')->first(),
            'biayaListrik'=>(clone $query)->where('jenis', 'Hutang Biaya Listrik')->first(),
            'lainLain'=>(clone $query)->where('jenis', 'Lain-Lain')->first()
        ];

        return view ('setting-akun-hutang/index')->with($data);
    }

    public function JangkaPanjang ()
    {
        $query = SettingCoa::where('Keterangan', 'Hutang Jangka Panjang');

        $data = [
            'perkiraan'=>Perkiraan::get(['id', 'nama']),
            'setting'=>(clone $query)->first(),
            'hutangBank'=>(clone $query)->where('jenis', 'Hutang Bank')->first(),
            'hutangLeasing'=>(clone $query)->where('jenis', 'Hutang Leasing')->first(),
            'hutangJangkaPanjang'=>(clone $query)->where('jenis', 'Hutang Jangka Panjang Lainnya')->first(),
        ];

        return view ('setting-akun-hutang.hutang-jangka-panjang')->with($data);
    }

    public function store (SetingCoa $request)
    {
        $data = $request->all();
        DB::beginTransaction();

        try {

            for ($i=0; $i<count($data['id_perkiraan']); $i++)
            {
                $act = array(
                    'keterangan'=>$request->keterangan,
                    'jenis'=>$data['jenis'][$i],
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'user_input'=>$request->user_input,);

                SettingCoa::insert($act);
            }
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            message($act, '', 'Setting Akun Hutang Gagal disimpan');
            return redirect ('setting-akun-hutang/index');
        }
        DB::commit();

        switch ($data['keterangan'])
        {
            case 'Hutang Jangka Pendek':
            message($act, 'Setting Akun Hutang Berhasil disimpan', 'Setting Akun Hutang Gagal disimpan');
            return redirect ('setting-akun-hutang/index');
            break;

            case 'Hutang Jangka Panjang':
            message($act, 'Setting Akun Hutang Berhasil disimpan', 'Setting Akun Hutang Gagal disimpan');
            return redirect ('setting-akun-hutang/hutang-jangka-panjang');
            break;
        }
    }

    public function update (SetingCoa $request)
    {

        DB::beginTransaction();

        try {

            $jumlah = count($request->id);
            for ($i=0; $i<$jumlah; $i++)
            {
                DB::table('setting_coa')->where('id', $request->id[$i])->update([
                    'id_perkiraan'=>$request->id_perkiraan[$i],
                    'user_update'=>$request->user_update
                ]);
            }

            DB::commit();
            switch ($request->keterangan)
            {
                case 'Hutang Jangka Pendek':
                message(true, 'Setting Akun Hutang Berhasil di update', 'Setting Akun Hutang Gagal di update');
                return redirect ('setting-akun-hutang/index');
                break;

                case 'Hutang Jangka Panjang':
                message(true, 'Setting Akun Hutang Berhasil di update', 'Setting Akun Hutang Gagal di update');
                return redirect ('setting-akun-hutang/hutang-jangka-panjang');
                break;
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Invalid data');
            DB::rollback();
        }
    }
}
