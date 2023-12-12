<?php
namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class LayananController extends Controller
{
    public $viewDir = "layanan";
    public $breadcrumbs = array('permissions'=>array('title'=>'Layanan','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-layanan');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['layanan' => new Layanan]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            
            $this->validate($request, Layanan::validationRules());

            $act=Layanan::create($request->all());
            DB::commit();
            message($act,'Data Layanan berhasil ditambahkan','Data Layanan gagal ditambahkan');
            return redirect('layanan');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Layanan berhasil ditambahkan','Data Layanan gagal ditambahkan');
            return redirect('layanan');
        }
    }

    public function show(Request $request, $kode)
    {
        $layanan=Layanan::find($kode);
        return $this->view("show",['layanan' => $layanan]);
    }

    public function edit(Request $request, $kode)
    {
        $layanan=Layanan::find($kode);
        return $this->view( "form", ['layanan' => $layanan] );
    }

    public function update(Request $request, $kode)
    {
        $layanan=Layanan::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Layanan::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $layanan->update($data);
            return "Record updated";
        }
        $this->validate($request, Layanan::validationRules());

        $act=$layanan->update($request->all());
        message($act,'Data Layanan berhasil diupdate','Data Layanan gagal diupdate');

        return redirect('/layanan');
    }

    public function destroy(Request $request, $kode)
    {
        $layanan=Layanan::find($kode);
        $act=false;
        try {
            $act=$layanan->forceDelete();
        } catch (\Exception $e) {
            $layanan=Layanan::find($layanan->pk());
            $act=$layanan->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Layanan::select('*');
        if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

        $edit=url("layanan/".$data->pk())."/edit";
        $delete=url("layanan/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
