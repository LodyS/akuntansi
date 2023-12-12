<?php

namespace App\Http\Controllers;
use App\Models\Perkiraan;
use App\Models\TipeJurnal;
use App\Jurnal;
use App\Models\PeriodeKeuangan;
use Auth;
use DB;
use App\DetailJurnal;
use App\transaksi;
use App\Http\Requests\JurnalUmum;
use Illuminate\Http\Request;

class JurnalUmumController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-umum');
    }

    public function index()
    {
        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);
        $perkiraan = Perkiraan::selectRaw("id,
            CASE
                WHEN kode_rekening LIKE '4%' THEN CONCAT(kode_rekening, '-', nama)
                WHEN kode_rekening LIKE '5%' THEN CONCAT(kode_rekening, '-', nama)
                WHEN kode_rekening LIKE '6%' THEN CONCAT(kode_rekening, '-', nama)
                WHEN kode_rekening LIKE '7%' THEN CONCAT(kode_rekening, '-', nama)
           ELSE
                nama END AS perkiraan")
        ->get();

        $periodeKeuangan = PeriodeKeuangan::select('id')->where('status_aktif', 'Y')->first();
        $tipe_jurnal = TipeJurnal::pluck('tipe_jurnal', 'id');

        return view('jurnal-umum/index', compact('perkiraan', 'tipe_jurnal', 'periodeKeuangan', 'unit'));
    }

    public function isiPerkiraan ($perkiraan)
    {
        $data = Perkiraan::select('perkiraan.nama as perkiraan')->where('id', $perkiraan)->first();

        echo json_encode($data);
        exit;
    }

    public function isiKodeJurnal ($tipe_jurnal)
    {
        $jurnal = TipeJurnal::selectRaw('concat(kode_jurnal, "-") as kode_jurnal, LENGTH (CONCAT(kode_jurnal, "-")) +1 AS panjang_kode')
        ->where('id', $tipe_jurnal)
        ->first(); //mencari kode jurnal berdasarkan id tipe jurnal yang dipilih, dan panjang karakter kode jurnal

        $id = Jurnal::where('kode_jurnal', 'like', $jurnal->kode_jurnal.'%')->max('id');
        // untuk mencari id terakhir dari kode jurnal (yang dipilih) yang telah di input

        $data = Jurnal::selectRaw("case when count(jurnal.id) >0 then CONCAT('$jurnal->kode_jurnal', SUBSTR(kode_jurnal, $jurnal->panjang_kode)+1)
        else concat('$jurnal->kode_jurnal',1) end AS kode")
        ->where('kode_jurnal', 'like', $jurnal->kode_jurnal.'%')
        ->where('id', $id)
        ->first(); // untuk menghasilkan kode jurnal

        echo json_encode($data);
        exit;
    }

    public function simpan (JurnalUmum $request)
    {
		$id_user = Auth::user()->id;
		DB::beginTransaction();

		try {

            $balance = str_replace(',', '', $request->balance);

            if ($balance == 0)
            {
                $success = true;
			    $act = new Jurnal;
			    $act->kode_jurnal = $request->kode_jurnal;
			    $act->tanggal_posting = $request->tanggal;
			    $act->keterangan = $request->keterangan;
			    $act->id_tipe_jurnal = $request->tipe_jurnal;
			    $act->id_user = $id_user;
			    $act->save();

			    $id_jurnal = $act->id;
			    $data = $request->all();

			    for ($i=0; $i<count($data['id_perkiraan']); $i++)
                {
                    $debet = str_replace('.', '', $data['debet'][$i]);
                    $kredit = str_replace('.', '', $data['kredit'][$i]);

                    $insert = array (
					    'id_jurnal'=>$id_jurnal,
					    'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'id_unit'=>$data['id_unit'][$i],
					    'debet'=>$debet,
					    'kredit'=>$kredit,
					    'ref'=>'N',);

				    DetailJurnal::create($insert);
                }
			}
			DB::commit();
		} catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            $success =false;
            return back()->withError('Invalid data');
		}

        if ($request->balance > 0 || $request->balance == null || $request->balance < 0) {
			return redirect('jurnal-umum/index')->with('error', 'Maaf tidak bisa input jurnal umum karena tidak balance');
        } else if ($success == true){
            return redirect('jurnal-umum/index')->with('success', 'Jurnal Umum Berhasil disimpan');
        } else if ($success == false){
            return redirect('jurnal-umum/index')->with('danger', 'Jurnal Umum Gagal simpan karena error sistem');
        }
	}
}
