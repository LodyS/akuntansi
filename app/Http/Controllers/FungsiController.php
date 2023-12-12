<?php
namespace App\Http\Controllers;

use App\Models\Fungsi;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class FungsiController extends Controller
{
    public $viewDir = "fungsi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Fungsi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-fungsi');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['fungsi' => new Fungsi]);
    }

    public function cekNamaFungsi ($nama_fungsi)
    {
        $data = Fungsi::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('nama_fungsi', $nama_fungsi)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, Fungsi::validationRules());

            $cek = Fungsi::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('nama_fungsi', $request->nama_fungsi)
            ->first();

            if ($cek->status == 'Tidak Ada')
            {
                $act=Fungsi::create($request->all());
            }

            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }

        if ($cek->status == 'Ada'){
            return redirect('fungsi')->with('error', 'Nama Fungsi sudah ada');
        } else if ($cek->status == 'Tidak Ada'){
            return redirect('fungsi')->with('success', 'Fungsi sudah ada disimpan');
        }
    }

    public function show(Request $request, $kode)
    {
        $fungsi=Fungsi::find($kode);
        return $this->view("show",['fungsi' => $fungsi]);
    }

    public function activate(Request $request, $kode)
    {
        // dd($kode);
        $fungsi=Fungsi::find($kode);
        $data=array(
          'status_aktif'=>'Y',
        );
        
        $status=$fungsi->update($data);
        message($status,'Group Berhasil Diaktifkan Kembali','Group Gagal Diaktifkan Kembali');
        return redirect('fungsi');
    } 

    public function deactivate(Request $request, $kode)
    {
        $fungsi=Fungsi::find($kode);
        $data=array('status_aktif'=>'N',);
       
        $status=$fungsi->update($data);
        message($status,'Group Berhasil Dinonaktifkan','Group Gagal Dinonaktifkan');
        
        return redirect('fungsi');
    }

    public function edit(Request $request, $kode)
    {
        $fungsi=Fungsi::find($kode);
        return $this->view( "form", ['fungsi' => $fungsi] );
    }

    public function update(Request $request, $kode)
    {
        $cek = Fungsi::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('nama_fungsi', $request->nama_fungsi)
        ->where('id', '<>', $kode)
        ->first();

        if ($cek->status == 'Ada')
        {
            message(false, 'Gagal simpan karena fungsi sudah ada', 'Gagal Simpan karena Fungsi sudah ada');
        }

        $fungsi=Fungsi::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Fungsi::validationRules( $request->name ) );
            if($validator->fails())
            return response($validator->errors()->first( $request->name),403);
            $fungsi->update($data);
            return "Record updated";
        }
        $this->validate($request, Fungsi::validationRules());
        if ($cek->status == 'Tidak Ada')
        {
            $act=$fungsi->update($request->all());
            message($act,'Data Fungsi berhasil diupdate','Data Fungsi gagal diupdate');
        }
        return redirect('/fungsi');
    }

    public function destroy(Request $request, $kode)
    {
        $fungsi=Fungsi::find($kode);
        $act=false;
        try {
            $act=$fungsi->forceDelete();
        } catch (\Exception $e) {
            $fungsi=Fungsi::find($fungsi->pk());
            $act=$fungsi->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Fungsi::select('*');
           
        if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
        }
           
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn ('status_aktif', function($data){
                   
        if(isset($data->status_aktif)){
            return array('id'=>$data->pk(), 'status_aktif'=>$data->status_aktif);
                      
        } else {
            return 0;
        }
        })->addColumn('action', function ($data) {
                   
        $edit=url("fungsi/".$data->pk())."/edit";
        $delete=url("fungsi/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
