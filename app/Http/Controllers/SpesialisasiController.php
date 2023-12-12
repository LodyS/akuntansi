<?php
namespace App\Http\Controllers;

use App\Models\Spesialisasi;
use Illuminate\Http\Request;
//use App\Support\Helpers;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SpesialisasiController extends Controller
{
    public $viewDir = "spesialisasi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Spesialisasi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-spesialisasi');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $kode = Spesialisasi::select(DB::raw("concat('S-', substr(kode, 3) +1) as lastCode"))->orderByDesc('id')->first();
        $lastCode = $kode->lastCode;
           
        return $this->view("form",['spesialisasi' => new Spesialisasi, 'lastCode'=>$lastCode]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, Spesialisasi::validationRules());

            $act=Spesialisasi::create($request->all());
            DB::commit();
            message($act,'Data Spesialisasi berhasil ditambahkan','Data Spesialisasi gagal ditambahkan');
            return redirect('spesialisasi');
        } catch (Exception $e) {
            DB::rollback();
            message(false,'Data Spesialisasi berhasil ditambahkan','Data Spesialisasi gagal ditambahkan');
            return redirect('spesialisasi');
        }
    }

    public function show(Request $request, $kode)
    {
        $spesialisasi=Spesialisasi::find($kode);
        return $this->view("show",['spesialisasi' => $spesialisasi]);
    }

    public function edit(Request $request, $kode)
    {
        $spesialisasi=Spesialisasi::find($kode);
        $lastCode = $spesialisasi->kode;
        return $this->view( "form", ['spesialisasi' => $spesialisasi, 'lastCode'=>$lastCode] );
    }

    public function update(Request $request, $kode)
    {
        $spesialisasi=Spesialisasi::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Spesialisasi::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $spesialisasi->update($data);
            return "Record updated";
        }
        $this->validate($request, Spesialisasi::validationRules());

        $act=$spesialisasi->update($request->all());
        message($act,'Data Spesialisasi berhasil diupdate','Data Spesialisasi gagal diupdate');

        return redirect('/spesialisasi');
    }

    public function editData (Request $request, $kode)
    {
        $spesialisasi = DB::table('spesialisasi')->select('id', 'nama')->where('id', $kode)->first();
        return $this->view('edit-data', ['spesialisasi'=>$spesialisasi]);
    }

    public function updateDataSpesialisasi (Request $request)
    {
        $spesialisasi = Spesialisasi::where('id', '=', $request->id)->update(['nama'   => $request->nama]);
            
        message($spesialisasi, 'Data Spesialisasi berhasil diupdate', 'Data Spesialisasi gagal di update');
        return redirect ('/spesialisasi');
    }

    public function destroy(Request $request, $kode)
    {
        $spesialisasi=Spesialisasi::find($kode);
        $act=false;
        try {
            $act=$spesialisasi->forceDelete();
        } catch (\Exception $e) {
            $spesialisasi=Spesialisasi::find($spesialisasi->pk());
            $act=$spesialisasi->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Spesialisasi::select('*');
        if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

        $edit=url("spesialisasi/".$data->pk())."/edit";
        $delete=url("spesialisasi/".$data->pk());
        $content = '';
        //$content .= "<a href='spesialisasi/edit-data/".$data->pk()."' class='btn btn-warning btn-sm'>Edit</a>"; 
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
