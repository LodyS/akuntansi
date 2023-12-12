<?php
namespace App\Http\Controllers;

use App\Models\ArusKa;
use App\ArusKasDetail;
use App\ArusKasRumus;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class ArusKasDetailController extends Controller
{
    public $viewDir = "arus_kas_detail";
    public $breadcrumbs = array('permissions'=>array('title'=>'Arus-kas-detail','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-arus-kas-detail');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $arusKas = ArusKa::selectRaw('arus_kas.nama as nama, dua.nama AS induk')
        ->leftJoin('arus_kas as dua', 'dua.id', 'arus_kas.id_induk')
        ->where('arus_kas.id', $id)
        ->firstOrFail();

        $detail = ArusKasDetail::selectRaw('arus_kas_detail.id, arus_kas.kode, perkiraan.kode_rekening, perkiraan.nama')
        ->leftJoin('arus_kas', 'arus_kas.id', 'arus_kas_detail.id_arus_kas')
        ->leftJoin('perkiraan', 'perkiraan.id', 'arus_kas_detail.id_perkiraan')
        ->where('id_arus_kas', $id)
        ->paginate(50);

        return $this->view("detail", compact('detail', 'id', 'arusKas'));
    }

    public function tambah(Request $request)
    {
        $aksi = "create";
        $perkiraan = DB::table('perkiraan')->selectRaw('id, kode_rekening, nama')->get();
        $data = ArusKa::selectRaw('arus_kas.id as id_arus_kas, dua.nama as induk, arus_kas.kode, arus_kas.nama')
        ->leftJoin('arus_kas as dua', 'dua.id', 'arus_kas.id_induk')
        ->where('arus_kas.id', $request->id)
        ->firstOrFail();

        return $this->view("form", compact('data', 'perkiraan', 'aksi'));
    }

    public function edit(Request $request)
    {
        $aksi = "edit";
        $perkiraan = DB::table('perkiraan')->selectRaw('id, kode_rekening, nama')->get();
        $data = ArusKasDetail::selectRaw('arus_kas_detail.id as id_arus_kas_detail, id_perkiraan, dua.nama as induk,
        id_arus_kas, arus_kas.kode, arus_kas.nama')
        ->leftJoin('arus_kas', 'arus_kas_detail.id_arus_kas', 'arus_kas.id')
        ->leftJoin('arus_kas as dua', 'dua.id', 'arus_kas.id_induk')
        ->where('arus_kas_detail.id', $request->id)
        ->firstOrFail();

        return $this->view("edit", compact('data', 'perkiraan', 'aksi'));
    }

    public function store (Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_arus_kas;
            $act=ArusKasDetail::create($request->all());
            DB::commit();
            message($act,'Setting Arus Kas berhasil ditambahkan','Data Arus Kas gagal ditambahkan');
            return redirect('arus-kas-detail/detail/'.$id);

        } catch (\Illuminate\Database\QueryException $e){

            DB::rollback();
            message(false, 'Setting Arus Kas gagal disimpan', 'Data Arus Kas Gagal disimpan');
			return redirect('/arus-kas-detail');
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_arus_kas;
            $act=ArusKasDetail::find($request->id)->update($request->except('id_arus_kas'));
            DB::commit();
            message($act,'Setting Arus Kas berhasil di update','Data Arus Kas gagal di update');
            return redirect('arus-kas-detail/detail/'.$id);

        } catch (\Illuminate\Database\QueryException $e){

            DB::rollback();
            message(false, 'Setting Arus Kas gagal disimpan', 'Data Arus Kas Gagal disimpan');
			return redirect('/arus-kas-detail');
        }
    }

    public function delete (Request $request)
    {
        $data = ArusKasDetail::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        $act = ArusKasDetail::where('id', $request->id)->delete();
        message($act, "Berhasil hapus data", "Gagal hapus data");
        return redirect('arus-kas-detail');
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = ArusKa::selectRaw('arus_kas.id, arus_kas.nama, CASE
        WHEN arus_kas.tipe = 1 THEN "Header"
        WHEN arus_kas.tipe = 2 THEN "Detail" END AS tipe,
        dua.nama AS id_induk,
        CASE
        WHEN arus_kas.jenis =1 THEN "Penerimaan"
        WHEN arus_kas.jenis=2 THEN "Pengeluaran" END jenis')
        ->leftJoin('arus_kas as dua', 'dua.id', 'arus_kas.id_induk');

        if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)
        ->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

            $edit=url("arus-kas-detail/".$data->pk())."/edit";
            $delete=url("arus-kas-detail/".$data->pk());
            $content = '';
            $content .= "<a href='arus-kas-detail/detail/".$data->pk()."' class='btn btn-info btn-round btn-sm'
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
