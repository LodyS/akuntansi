<?php
namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KelasController extends Controller
{
    public $viewDir = "kelas";
    public $breadcrumbs = array('permissions'=>array('title'=>'Kelas','link'=>"#",'active'=>false,'display'=>true),);
    public function __construct()
    {
        $this->middleware('permission:read-kelas');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['kelas' => new Kelas]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, Kelas::validationRules());

            $act=Kelas::create($request->all());
            DB::commit();
            message($act,'Data Kelas berhasil ditambahkan','Data Kelas gagal ditambahkan');
            return redirect('kelas');
        } catch (Exception $e){
            DB::rollback();
            message($false,'Data Kelas berhasil ditambahkan','Data Kelas gagal ditambahkan');
            return redirect('kelas');
        }
    }

    public function show(Request $request, $kode)
    {
        $kelas=Kelas::find($kode);
        return $this->view("show",['kelas' => $kelas]);
    }

    public function edit(Request $request, $kode)
    {
        $kelas=Kelas::find($kode);
        return $this->view( "form", ['kelas' => $kelas] );
    }

    public function update(Request $request, $kode)
    {
        $kelas=Kelas::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Kelas::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $kelas->update($data);
            return "Record updated";
        }
        $this->validate($request, Kelas::validationRules());
        $act=$kelas->update($request->all());
        message($act,'Data Kelas berhasil diupdate','Data Kelas gagal diupdate');

        return redirect('/kelas');
    }

    public function destroy(Request $request, $kode)
    {
        $kelas=Kelas::find($kode);
        $act=false;
        try {
            $act=$kelas->forceDelete();
        } catch (\Exception $e) {
            $kela=Kelas::find($kelas->pk());
            $act=$kelas->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
    
    public function loadData()   
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Kelas::select('*');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
        
        $edit=url("kelas/".$data->pk())."/edit";
        $delete=url("kelas/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
