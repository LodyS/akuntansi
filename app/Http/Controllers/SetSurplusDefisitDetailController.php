<?php
namespace App\Http\Controllers;

use App\Models\SetSurplusDefisitDetail;
use App\Models\SettingSurplusDefisit;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SetSurplusDefisitDetailController extends Controller
{
    public $viewDir = "set_surplus_defisit_detail";
    public $breadcrumbs = array('permissions'=>array('title'=>'Set-surplus-defisit-detail','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-set-surplus-defisit-detail');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function detail ($id)
    {
        $setting = SettingSurplusDefisit::selectRaw('case when setting_surplus_defisit.jenis = "-1" then "Pengurang"
        when setting_surplus_defisit.jenis = "1" then "Penambah" else ""end as jenis,
        setting_surplus_defisit.nama, dua.nama as induk')
        ->leftJoin('setting_surplus_defisit as dua', 'dua.id', 'setting_surplus_defisit.induk')
        ->where('setting_surplus_defisit.id', $id)
        ->firstOrFail();

        $detail = DB::table('set_surplus_defisit_detail')
        ->selectRaw('set_surplus_defisit_detail.id, unit.nama as unit, unit.code_cost_centre as kode')
        ->leftJoin('unit', 'unit.id', 'set_surplus_defisit_detail.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->where('setting_surplus_defisit.id', $id)
        ->paginate(25);

        return $this->view('detail', compact('setting', 'detail', 'id'));
    }

    public function create()
    {
        $aksi = "create";
        $setSurplusDefisitDetail = new SetSurplusDefisitDetail;
        $settingSurplusDefisit = DB::table('setting_surplus_defisit')->select('id', 'nama')->get();
        $unit = DB::table('unit')->select('id', 'nama')->get();
        return $this->view("form", compact('setSurplusDefisitDetail', 'settingSurplusDefisit', 'unit', 'aksi'));
    }

    public function tambah(Request $request)
    {
        $unit = DB::table('unit')->selectRaw('id, code_cost_centre as kode')->where('level', '<>', '0')->get();
        $data = SettingSurplusDefisit::selectRaw('setting_surplus_defisit.id, dua.nama as induk, setting_surplus_defisit.kode,
        setting_surplus_defisit.level, setting_surplus_defisit.nama')
        ->leftJoin('setting_surplus_defisit as dua', 'dua.id', 'setting_surplus_defisit.induk')
        ->where('setting_surplus_defisit.id', $request->id)
        ->firstOrFail();

        return $this->view("form-tambah", compact('data', 'unit'));
    }

    public function edit(Request $request)
    {
        $aksi = "Update";
        $unit = DB::table('unit')->selectRaw('id, code_cost_centre as kode')->where('level', '<>', '0')->get();
        $data = SetSurplusDefisitDetail::selectRaw('set_surplus_defisit_detail.id, setting_surplus_defisit.id as id_setting_surplus_defisit,
        dua.nama as induk, setting_surplus_defisit.kode,
        setting_surplus_defisit.level, setting_surplus_defisit.nama, set_surplus_defisit_detail.id_unit')
        ->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id', 'set_surplus_defisit_detail.id_set_surplus_defisit')
        ->leftJoin('setting_surplus_defisit as dua', 'dua.id', 'setting_surplus_defisit.induk')
        ->where('set_surplus_defisit_detail.id', $request->id)
        ->firstOrFail();

        return $this->view("form-edit", compact('data', 'unit', 'data', 'aksi'));
    }

    public function isiUnit ($id_unit)
    {
        $data = DB::table('unit')->select('nama')->where('id', $id_unit)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, SetSurplusDefisitDetail::validationRules());
            $id = $request->id_set_surplus_defisit;
            $act=SetSurplusDefisitDetail::create($request->all());
            DB::commit();
            message($act,'Data Set Surplus Defisit Detail berhasil ditambahkan','Data Set Surplus Defisit Detail gagal ditambahkan');
            return redirect('set-surplus-defisit-detail/detail/'.$id);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    public function show(Request $request, $kode)
    {
        $setSurplusDefisitDetail=SetSurplusDefisitDetail::find($kode);
        return $this->view("show",['setSurplusDefisitDetail' => $setSurplusDefisitDetail]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_unit'=>'required',
        ]);

        $id = $request->id_set_surplus_defisit;

        $act = SetSurplusDefisitDetail::where('id', $request->id)->update([
            'id_unit'=>$request->id_unit,
        ]);
        message($act,'Data Set Surplus Defisit Detail berhasil diupdate','Data Set Surplus Defisit Detail gagal diupdate');
        return redirect('/set-surplus-defisit-detail/detail/'.$id);
    }

    public function delete (Request $request)
    {
        $data = SetSurplusDefisitDetail::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function remove(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id_set_surplus_defisit;
            SetSurplusDefisitDetail::where('id', $request->id)->delete();
            DB::commit();
            message(true, 'Setting Surplus Defisit Detail Berhasil dihapus', 'Setting Surplus Defisit Detail gagal dihapus');
            return redirect('set-surplus-defisit-detail/detail/'.$id);
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SettingSurplusDefisit::selectRaw('setting_surplus_defisit.id, setting_surplus_defisit.level, setting_surplus_defisit.nama,
        dua.nama as induk, case when setting_surplus_defisit.jenis = "-1" then "Pengurang"
        when setting_surplus_defisit.jenis= "1" then "Penambah" else ""end as jenis ')
        //->leftJoin('setting_surplus_defisit', 'setting_surplus_defisit.id',  'set_surplus_defisit_detail.id_set_surplus_defisit')
        //->leftJoin('unit', 'unit.id', 'set_surplus_defisit_detail.id_unit')
        ->leftJoin('setting_surplus_defisit as dua', 'dua.id', 'setting_surplus_defisit.induk')
        ->groupBy('setting_surplus_defisit.nama')
        ->orderBy('setting_surplus_defisit.kode', 'asc');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn ('level', function($data){

            if(isset($data->level)){
                return array('id'=>$data->pk(), 'level'=>$data->level);

            } else {
                return 0;
            }
        })->addColumn('action', function ($data) {
            $edit=url("set-surplus-defisit-detail/".$data->pk())."/edit";
            $delete=url("set-surplus-defisit-detail/".$data->pk());
            $content = '';
            $content .= "<a href='set-surplus-defisit-detail/detail/".$data->pk()."' class='btn btn-info btn-round btn-sm'
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
