<?php

namespace App\Http\Controllers;
use App\Models\Perkiraan;
use App\Models\Transaksi;
use App\jurnal;
use App\detail_jurnal;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\PeriodeKeuangan;
use Illuminate\Http\Request;

class PindahPerkiraanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-pindah-perkiraan');
    }

    public function index ()
    {
        return view ('pindah-perkiraan/index');
    }

    public function pencarian (Request $request)
    {
        $data = DB::table('jurnal')
        ->selectRaw("perkiraan.kode_rekening,perkiraan.nama as perkiraan, unit.code_cost_centre, unit.nama as unit, id_perkiraan,id_unit,
        CASE
        WHEN sum(IFNULL((detail_jurnal.debet),0)) > sum(IFNULL((detail_jurnal.kredit),0))
        THEN sum(IFNULL((detail_jurnal.debet),0)) - sum(IFNULL((detail_jurnal.kredit),0))
        ELSE '0' END AS debet,
        CASE
        WHEN sum(IFNULL((detail_jurnal.debet),0)) < sum(IFNULL((detail_jurnal.kredit),0))
        THEN sum(IFNULL((detail_jurnal.kredit),0)) - sum(IFNULL((detail_jurnal.debet),0))
        ELSE '0' END AS kredit")
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan','perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'detail_jurnal.id_unit')
        ->whereMonth('tanggal_posting', $request->bulan)
        ->whereYear('tanggal_posting', $request->tahun)
        ->where('id_tipe_jurnal','<>', '8')
        ->where('flag_tutup_buku', 'N')
        ->orderBy('perkiraan.kode_rekening', 'asc')
        ->groupBy('id_perkiraan')
        ->groupBy('id_unit')
        ->get();

        $total_debet = $data->sum('debet');
        $total_kredit = $data->sum('kredit');

        $status = DB::table('jurnal')
        ->selectRaw("id")
        ->whereMonth('tanggal_posting', $request->bulan)
        ->whereYear('tanggal_posting', $request->tahun)
        ->where('flag_tutup_buku', 'N')
        ->where('id_tipe_jurnal','<>', '8')
        ->first();

        return view ('pindah-perkiraan/index', compact('data', 'status', 'total_debet', 'total_kredit'));
    }

    public function konversi (Request $request)
    {
        $sekarang = Carbon::now();
        $tanggalAkhir = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        $tanggal = $sekarang->addMonth(1);
        $id_user = Auth::user()->id;

        if ($request->status == 'Tidak Ada')
        {
            message(false, '', 'Gagal tutup buku karena data kosong');
            return redirect('pindah-perkiraan/index');
        }

        DB::beginTransaction();

        try {

            $data = $request->all();
            $act = new jurnal;
			$act->tanggal_posting = $tanggal;
			$act->keterangan = 'Saldo Awal';
		    $act->id_user = $id_user;
            $act->id_tipe_jurnal =5;
            $act->flag_tutup_buku = 'Y';
			$act->save();

			$id_jurnal = $act->id;

            for ($i=0; $i<count($data['id_perkiraan']); $i++)
            {
                $insert = array (
                    'id_jurnal'=>$id_jurnal,
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'id_unit'=>$data['id_unit'][$i],
                    'debet'=>$data['debet'][$i],
                    'kredit'=>$data['kredit'][$i],
                    'ref'=>'N',);

                detail_jurnal::create($insert);
            }

            for ($i=0; $i<count($data['id_perkiraan']); $i++)
            {
                $setting = array(
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'id_unit'=>$data['id_unit'][$i],
                    'tanggal'=>$tanggal,
                    'keterangan'=>'Saldo Awal',
                    'debet'=>$data['debet'][$i],
                    'kredit'=>$data['kredit'][$i],);

                Transaksi::create($setting);
            }
            DB::commit();
            return redirect('pindah-perkiraan/index')->with('success', 'Berhasil tutup buku');

        } catch (Exception $e){
            DB::rollback();
        }
    }
}
