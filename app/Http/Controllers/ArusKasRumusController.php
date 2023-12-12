<?php
namespace App\Http\Controllers;

use App\Models\ArusKa;
use App\ArusKasRumus;
use Illuminate\Http\Request;
use DB;
use App\JenisTransaksi;
use App\TransaksiJurnal;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class ArusKasRumusController extends Controller
{
    public $viewDir = "arus_kas_rumus";
    public $breadcrumbs = array('permissions'=>array('title'=>'Arus-kas-rumus','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-arus-kas-rumus');
    }

    public function index()
    {
        $data = TransaksiJurnal::selectRaw('jenis_transaksi.kode, transaksi_jurnal.id, transaksi_jurnal.nama, tiga.nama AS induk,
        CASE
        WHEN jenis_transaksi.tipe = 1 THEN "Penambah"
        WHEN jenis_transaksi.tipe = -1 THEN "Pengurang" END AS tipe')
        ->leftJoin('jenis_transaksi', 'jenis_transaksi.id_transaksi_jurnal', 'transaksi_jurnal.id')
        ->leftJoin('jenis_transaksi as dua', 'dua.id', 'jenis_transaksi.id_induk')
        ->leftJoin('transaksi_jurnal as tiga', 'tiga.id', 'dua.id_transaksi_jurnal')
        ->paginate(100);

        return $this->view( "index", compact('data'));
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $arusKas = JenisTransaksi::selectRaw('jenis_transaksi.kode,tiga.nama as induk, transaksi_jurnal.nama')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->leftJoin('jenis_transaksi as dua', 'dua.id', 'jenis_transaksi.id_induk')
        ->leftJoin('transaksi_jurnal as tiga', 'tiga.id', 'dua.id_transaksi_jurnal')
        ->leftJoin('arus_kas_rumus', 'arus_kas_rumus.id_rumus_arus_kas', 'transaksi_jurnal.id')
        ->where('arus_kas_rumus.id_rumus_arus_kas', $id)
        ->first();

        $detail = ArusKasRumus::selectRaw('arus_kas_rumus.id, jenis_transaksi.kode, transaksi_jurnal.nama')
        ->selectRaw('CASE WHEN jenis_transaksi.tipe =1 THEN "Penerimaan" WHEN jenis_transaksi.tipe =-1 THEN "Pengeluaran" END jenis')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'arus_kas_rumus.id_transaksi_jurnal')
        ->leftJoin('jenis_transaksi', 'jenis_transaksi.id_transaksi_jurnal', 'transaksi_jurnal.id')
        ->where('arus_kas_rumus.id_rumus_arus_kas', $id)
        ->orderBy('jenis_transaksi.kode')
        ->paginate(50);

        return $this->view("detail", compact('detail', 'id', 'arusKas'));
    }

    public function tambah(Request $request)
    {
        $aksi = "create";
        $id = $request->id;
        $transaksi = DB::table('transaksi_jurnal')->get(['id','nama']);
        $data = JenisTransaksi::selectRaw('transaksi_jurnal.id, jenis_transaksi.kode, nama')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->leftJoin('arus_kas_rumus', 'arus_kas_rumus.id_rumus_arus_kas', 'transaksi_jurnal.id')
        ->where('id_rumus_arus_kas', $request->id)
        ->first();

        return $this->view("form", compact('data', 'transaksi', 'aksi', 'id'));
    }

    public function edit(Request $request)
    {
        $aksi = "edit";

        $transaksi = DB::table('transaksi_jurnal')->get(['id','nama']);
        $data = ArusKasRumus::selectRaw('arus_kas_rumus.id, jenis_transaksi.id as id_jenis_transaksi, tiga.nama, arus_kas_rumus.id_rumus_arus_kas')
        ->selectRaw('arus_kas_rumus.id_transaksi_jurnal, jenis_transaksi.kode')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'arus_kas_rumus.id_transaksi_jurnal')
        ->leftJoin('transaksi_jurnal as tiga', 'tiga.id', 'arus_kas_rumus.id_rumus_arus_kas')
        ->leftJoin('jenis_transaksi', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->where('arus_kas_rumus.id', $request->id)
        ->firstOrFail();

        $id_rumus_arus_kas = $data->id_rumus_arus_kas;

        return $this->view("edit", compact('data', 'transaksi', 'id_rumus_arus_kas','aksi'));
    }

    public function store (Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_rumus_arus_kas;
            $act=ArusKasRumus::create($request->except('id'));
            DB::commit();
            message($act,'Setting Arus Kas berhasil ditambahkan','Setting Rumus Arus Kas gagal ditambahkan');
            return redirect('arus-kas-rumus/detail/'.$id);
            //return redirect('arus-kas-rumus');

        } catch (Exception $e){

            DB::rollback();
            message(false, 'Setting Rumus Arus Kas gagal disimpan', 'Setting Rumus Arus Kas Gagal disimpan');
			return redirect('/arus-kas-rumus/'.$id);
            //return redirect('arus-kas-rumus');
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_rumus_arus_kas;
            $act=ArusKasRumus::find($request->id)->update($request->except('id_rumus_arus_kas'));
            DB::commit();
            message($act,'Setting Rumus Arus Kas berhasil di update','Data Rumus Arus Kas gagal di update');
            //return redirect('arus-kas-rumus/detail/'.$id);
            return redirect('arus-kas-rumus');

        } catch (Exception $e){

            DB::rollback();
            message(false, 'Setting Rumus Arus Kas gagal disimpan', 'Setting Rumus Arus Kas Gagal disimpan');
			return redirect('/arus-kas-rumus');
            //return redirect('arus-kas-rumus/detail/'.$id);
        }
    }

    public function delete (Request $request)
    {
        $data = ArusKasRumus::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        $act = ArusKasRumus::where('id', $request->id)->delete();
        message($act, "Berhasil hapus data", "Gagal hapus data");
        return redirect('arus-kas-rumus');
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = JenisTransaksi::selectRaw('transaksi_jurnal.id, transaksi_jurnal.nama, CASE
        WHEN jenis_transaksi.tipe = 1 THEN "Penambah"
        WHEN jenis_transaksi.tipe = -1 THEN "Pengurang" END AS tipe,
        tiga.nama AS id_induk')
        ->leftJoin('transaksi_jurnal', 'jenis_transaksi.id_rumus_arus_kas', 'transaksi_jurnal.id')
        ->leftJoin('jenis_transaksi as dua', 'dua.id', 'jenis_transaksi.id_induk')
        ->leftJoin('transaksi_jurnal as tiga', 'tiga.id', 'dua.id_transaksi_jurnal')
        ->orderBy('jenis_transaksi.kode');

        if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)
        ->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

            $edit=url("arus-kas-rumus/".$data->pk())."/edit";
            $delete=url("arus-kas-rumus/".$data->pk());
            $content = '';
            $content .= "<a href='arus-kas-rumus/detail/".$data->pk()."' class='btn btn-info btn-round btn-sm'
            data-toggle='tooltip' data-original-title='Detail'>
            <i class='icon glyphicon glyphicon-info-sign' aria-hidden=true'></i></a>";
            /*$content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";*/

            return $content;
        })->make(true);
    }
}
