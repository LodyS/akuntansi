<?php
namespace App\Http\Controllers;

use App\Models\SetNeracaDetail;
use Illuminate\Http\Request;
use App\Models\SetNeraca;
use App\Models\Perkiraan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SetNeracaDetailController extends Controller
{
    public $viewDir = "set_neraca_detail";
    public $breadcrumbs = array('permissions'=>array('title'=>'Set-neraca-detail','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-set-neraca-detail');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function detail ($id)
    {
        $setting = SetNeraca::select('jenis_neraca', 'nama')->where('id', $id)->first();
        $detail = SetNeracaDetail::selectRaw('set_neraca_detail.id as id, perkiraan.kode as kode, set_neraca.jenis_neraca as jenis_neraca,
        set_neraca.nama as nama, perkiraan.nama as rekening')
        ->leftJoin('set_neraca', 'set_neraca_detail.id_set_neraca', 'set_neraca.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'set_neraca_detail.id_perkiraan')
        ->where('id_set_neraca', $id)
        ->paginate(25);

        return $this->view('detail-neraca', compact('setting', 'detail', 'id'));
    }

    public function create ()
    {
        return $this->view("form", ['setNeracaDetail' => new SetNeracaDetail]);
    }

    public function tambah(Request $request)
    {
        $aksi = "Tambah";
        $setNeraca =  SetNeraca::selectRaw('set_neraca.id, set_neraca.jenis_neraca, dua.nama as induk, set_neraca.kode')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk')
        ->where('set_neraca.id', $request->id)
        ->firstOrFail();

        $perkiraan = Perkiraan::select('id', 'nama')->get();
        return $this->view("form-tambah", compact('setNeraca', 'perkiraan', 'aksi'));
    }

    public function store( Request $request )
    {
        $this->validate($request, SetNeracaDetail::validationRules());

        $act=SetNeracaDetail::create($request->all());
        message($act,'Data Set Neraca Detail berhasil ditambahkan','Data Set Neraca Detail gagal ditambahkan');
        return redirect('set-neraca-detail');
    }

    public function show(Request $request, $kode)
    {
        $setNeracaDetail=SetNeracaDetail::find($kode);
        return $this->view("show",['setNeracaDetail' => $setNeracaDetail]);
    }

    public function edit(Request $request)
    {
        $aksi = "Update";
        $setNeracaDetail=SetNeracaDetail::selectRaw('set_neraca_detail.id, set_neraca_detail.id_perkiraan, set_neraca.kode,
        set_neraca.jenis_neraca, dua.nama as induk')
        ->leftJoin('set_neraca', 'set_neraca.id', 'set_neraca_detail.id_set_neraca')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk')
        ->where('set_neraca_detail.id', $request->id)
        ->firstOrFail();

        $perkiraan = Perkiraan::select('id', 'nama')->get();
        return $this->view( "form-edit", compact('setNeracaDetail', 'perkiraan', 'aksi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'id_perkiraan'=>'required',
        ]);
        $act= SetNeracaDetail::where('id', $request->id)->update([
            'id_perkiraan'=>$request->id_perkiraan,
        ]);

        message($act,'Data Set Neraca Detail berhasil diupdate','Data Set Neraca Detail gagal diupdate');
        return redirect('/set-neraca-detail');
    }

    public function delete (Request $request)
    {
        $data = SetNeracaDetail::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        $act =SetNeracaDetail::where('id', $request->id)->delete();
        message($act, "Berhasil hapus data", "Gagal hapus data");
        return redirect('/set-neraca-detail');
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SetNeraca::selectRaw('set_neraca.id as id, set_neraca.kode as kode, set_neraca.jenis_neraca as jenis_neraca,
        set_neraca.nama as nama, dua.nama as induk, set_neraca.level as level, case when set_neraca.jenis = "-1" then "Pengurang"
        when set_neraca.jenis= "1" then "Penambah" else ""end as jenis')
        //->leftJoin('set_neraca', 'set_neraca_detail.id_set_neraca', 'set_neraca.id')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            $edit=url("set-neraca-detail/".$data->pk())."/edit";
            $delete=url("set-neraca-detail/".$data->pk());
            $content = '';
            $content .= "<a href='set-neraca-detail/detail-neraca/".$data->pk()."' class='btn btn-outline-primary btn-sm'>Detail</a>";
            /*$content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";*/

            return $content;
        })->make(true);
    }
}
