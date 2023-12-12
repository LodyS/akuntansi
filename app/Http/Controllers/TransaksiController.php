<?php
namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\Perkiraan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PeriodeKeuangan;
use Datatables;
use App\DetailJurnal;
use DB;
use Auth;
use Carbon\Carbon;
use App\jurnal;

class TransaksiController extends Controller
{
    public $viewDir = "transaksi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Transaksi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-transaksi');
    }

    public function index(Request $request)
    {
        // dd($request);
        $keyword = $request->input('keyword');
        $tanggal = date('d-m-y');
        $periode_keuangan = PeriodeKeuangan::where('status_aktif', 'Y')->first();
        $perkiraan = DB::table('transaksi')
        ->selectRaw('transaksi.id, id_perkiraan, perkiraan.nama as perkiraan, transaksi.debet, transaksi.kredit')
        ->join('perkiraan', 'perkiraan.id', 'transaksi.id_perkiraan')
        ->where('perkiraan.nama','like',"%$keyword%")
        ->paginate(30);

        return $this->view( "index", compact('perkiraan', 'periode_keuangan', 'tanggal'));
    }

    public function perkiraan ()
    {
        $perkiraan = DB::table('perkiraan')
        ->selectRaw('id as id_perkiraan, perkiraan.nama, perkiraan.type, debet, kredit')
        ->whereIn('id', Transaksi::select('id_perkiraan'))
        ->where('type', '2')
        ->get();

        return $this->view("transaksi/perkiraan-tipe-detail", ['perkiraan'=>$perkiraan]);
    }

    public function insert (Request $request)
    {
        $id_user = Auth::user()->id;
        $id_periode = $request->id_periode;

        DB::beginTransaction();

        try {

            $act = new jurnal; // insert data ke table  jurnal
		    $act->kode_jurnal = $request->kode_jurnal;
		    $act->tanggal_posting = $request->tanggal_posting;
		    $act->keterangan = $request->keterangan;
		    $act->id_user = $id_user;
		    $act->save();

		    $id_jurnal = $act->id;
		    $data = $request->all();

		    // insert data array ke table detail jurnal
		    for ($i=0; $i<count($data['id_perkiraan']); $i++)
            {
                $insert = array (
				    'id_jurnal'=>$id_jurnal,
				    'id_perkiraan'=>$data['id_perkiraan'][$i],
				    'debet'=>$data['debet'][$i],
				    'kredit'=>$data['kredit'][$i],);

                DetailJurnal::create($insert);
            }

            // update data array ke table transaksi
            $jumlah = count($request->id_perkiraan);
            for ($i=0; $i<$jumlah; $i++)
            {
                DB::table('transaksi')->where('id', $request->id_perkiraan[$i])->update([
                    'id_perkiraan'=>$request->id_perkiraan[$i],
                    'debet'=>$request->debet[$i],
                    'kredit'=>$request->kredit[$i],
                    'id_periode'=>$request->id_periode,
                ]);
            } // akhir update table transaksi

            // update data array ke table perkiraan

            $jumlah = count($request->id_perkiraan);
            for ($i=0; $i<$jumlah; $i++)
            {
                DB::table('perkiraan')->where('id', $request->id_perkiraan[$i])->update([
                    'id_perkiraan'=>$request->id_perkiraan[$i],
                    'debet'=>$request->debet[$i],
                    'kredit'=>$request->kredit[$i],
                ]);
            }  // akhir update table perkiraan
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
        }
        DB::commit();

        message($act, 'Saldo Awal Berhasil Disimpan', 'Saldo Awal gagal disimpan');
	    return redirect ('transaksi');
    }

    public function show(Request $request, $kode)
    {
        $transaksi=Transaksi::find($kode);
        return $this->view("show",['transaksi' => $transaksi]);
    }

    public function editData (Request $request)
    {
        $transaksi = Transaksi::selectRaw('transaksi.id, perkiraan.nama as perkiraan, transaksi.debet, transaksi.kredit')
        ->leftJoin('perkiraan', 'perkiraan.id', 'transaksi.id_perkiraan')
        ->where('transaksi.id', $request->id)
        ->firstOrFail();

        return view ('transaksi/edit', compact('transaksi'));
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'id'=>'required',
                'debet'=>'required',
                'kredit'=>'required',
            ]);

            DB::beginTransaction();

            $journal = DetailJurnal::selectRaw('MAX(id_jurnal) as id')
            ->join('jurnal', 'jurnal.id', 'detail_jurnal.id_jurnal')
            ->where('keterangan', 'Saldo Awal')
            ->where('id_perkiraan', $request->id)
            ->first();

            if (isset($journal))
            {
                DB::table('detail_jurnal')->where('id_jurnal', $journal->id)->delete();
                DB::table('jurnal')->where('id', $journal->id)->delete();
            }

            $jurnal = new jurnal;
            $jurnal->tanggal_posting = Carbon::now();
            $jurnal->keterangan = 'Saldo Awal';
            $jurnal->id_user = Auth::user()->id;
            $jurnal->save();

            $debett = str_replace('.', '', $request->debet);
            $debet = str_replace(',', '.', $debett);
            $kreditt = str_replace('.', '', $request->kredit);
            $kredit = str_replace(',', '.', $kreditt);

            $detailJurnal = new DetailJurnal;
            $detailJurnal->id_jurnal = $jurnal->id;
            $detailJurnal->id_perkiraan = $request->id;
            $detailJurnal->debet = $debet;
            $detailJurnal->kredit = $kredit;
            $detailJurnal->save();

            Transaksi::where('id', $request->id)->update(['debet' => $debet, 'kredit' => $kredit]);

            DB::commit();
            message(true, 'Data transaksi berhasil diupdate', 'Data transaksi gagal di update');
            return redirect('transaksi');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false, 'Data transaksi berhasil diupdate', 'Data transaksi gagal di update');
            return redirect('transaksi');
            throw $th;
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
}
