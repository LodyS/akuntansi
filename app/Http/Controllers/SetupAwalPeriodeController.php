<?php
namespace App\Http\Controllers;

use App\Models\SetupAwalPeriode;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SetupAwalPeriodeController extends Controller
{
    public $viewDir = "setup_awal_periode";
    public $breadcrumbs = array('permissions'=>array('title'=>'Setup-awal-periode','link'=>"#",'active'=>false,'display'=>true), );

    public function __construct()
    {
        $this->middleware('permission:read-setup-awal-periode');
    }

    public function index()
    {
        $setup = SetupAwalPeriode::select('*')->orderByDesc('id')->first();
        return $this->view( "index")->with('setup', $setup);
    }

    public function create()
    {
        return $this->view("form",['setupAwalPeriode' => new SetupAwalPeriode]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();
           
        try {
            $this->validate($request, SetupAwalPeriode::validationRules());

            $act=SetupAwalPeriode::create($request->all());
            DB::commit();
            message($act,'Data Setup Awal Periode berhasil ditambahkan','Data Setup Awal Periode gagal ditambahkan');
            return redirect('setup-awal-periode');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Setup Awal Periode berhasil ditambahkan','Data Setup Awal Periode gagal ditambahkan');
            return redirect('setup-awal-periode');
        }
    }
   
    public function show(Request $request, $kode)
    {
        $setupAwalPeriode=SetupAwalPeriode::find($kode);
        return $this->view("show",['setupAwalPeriode' => $setupAwalPeriode]);
    }

    public function edit(Request $request, $kode)
    {
        $setupAwalPeriode=SetupAwalPeriode::find($kode);
        return $this->view( "form", ['setupAwalPeriode' => $setupAwalPeriode] );
    }

    public function update(Request $request, $kode)
    {
        $setupAwalPeriode=SetupAwalPeriode::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SetupAwalPeriode::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $setupAwalPeriode->update($data);
                return "Record updated";
        }
        $this->validate($request, SetupAwalPeriode::validationRules());

        $act=$setupAwalPeriode->update($request->all());
        message($act,'Data Setup Awal Periode berhasil diupdate','Data Setup Awal Periode gagal diupdate');

        return redirect('/setup-awal-periode');
    }
   
    public function destroy(Request $request, $kode)
    {
        $setupAwalPeriode=SetupAwalPeriode::find($kode);
        $act=false;
        try {
            $act=$setupAwalPeriode->forceDelete();
        } catch (\Exception $e) {
            $setupAwalPeriode=SetupAwalPeriode::find($setupAwalPeriode->pk());
            $act=$setupAwalPeriode->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SetupAwalPeriode::select('*')->orderBy('tanggal_setup', 'desc')->limit(1);

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
                   
            $edit=url("setup-awal-periode/".$data->pk())."/edit";
            $delete=url("setup-awal-periode/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
