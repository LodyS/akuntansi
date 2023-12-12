<?php
namespace App\Http\Controllers;

use App\Models\KelompokBisnis;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KelompokBisnisController extends Controller
{
    public $viewDir = "kelompok_bisnis";
    public $breadcrumbs = array('permissions'=>array('title'=>'Kelompok-bisnis','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-kelompok-bisnis');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['kelompokBisnis' => new KelompokBisnis]);
    }

    public function cekKode ($kode)
    {
        $data = KelompokBisnis::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();
        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            
            $data = KelompokBisnis::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('kode', $request->kode)
            ->first();
            
            if ($data->status == 'Tidak Ada')
            {
                $this->validate($request, KelompokBisnis::validationRules());
                $act=KelompokBisnis::create($request->except('status'));
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }
        if ($data->status == 'Ada'){
            return redirect('kelompok-bisnis')->with('error', 'Kode Kelompok Bisnis Sudah dipakai');
        } else if ($data->status == 'Tidak Ada'){
            return redirect('kelompok-bisnis')->with('success', 'Kelompok Bisnis Sudah di simpan');
        }
    }

    public function show(Request $request, $kode)
    {
        $kelompokBisni=KelompokBisnis::find($kode);
        return $this->view("show",['kelompokBisnis' => $kelompokBisnis]);
    }

    public function edit(Request $request, $kode)
    {
        $kelompokBisnis=KelompokBisnis::find($kode);
        return $this->view( "form", ['kelompokBisnis' => $kelompokBisnis] );
    }
	   
	public function activate(Request $request, $kode)
    {
        // dd($kode);
        $KelompokBisnis= KelompokBisnis::find($kode);
        $data=array('flag_aktif'=>'Y',);
         
        $status=$KelompokBisnis->update($data);
        message($status,'Kelompok Bisnis Berhasil Diaktifkan Kembali','Kelompok Bisnis Gagal Diaktifkan Kembali');
         
        return redirect('/kelompok-bisnis');
    } 
 
    public function deactivate(Request $request, $kode)
    { 
        $kelompokBisnis=KelompokBisnis::find($kode);
        $data=array('flag_aktif'=>'N',);
        
        $status=$kelompokBisnis->update($data);
        message($status,'Kelompok Bisnis Berhasil Dinonaktifkan','Kelompok Bisnis Gagal Dinonaktifkan');
         
        return redirect('/kelompok-bisnis');
    }

    public function update(Request $request, $kode)
    {
        $cek = KelompokBisnis::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->where('id', '<>', $kode)
        ->first();
        
        if ($cek->status == 'Ada')
        {
            message(false,'','Data Kelompok Bisnis gagal ditambahkan karena kode sudah ada');
            //return redirect('kelompok-bisnis');
            return 'Data Kelompok Bisnis gagal diupdate karena kode sudah ada';
        }

        if ($cek->status == 'Tidak Ada')
        {
            $kelompokBisnis=KelompokBisnis::find($kode);
            if( $request->isXmlHttpRequest() )
            {
                $data = [$request->name  => $request->value];
                $validator = \Validator::make( $data, KelompokBisnis::validationRules( $request->name ) );
                if($validator->fails())
                    return response($validator->errors()->first( $request->name),403);
                    $kelompokBisnis->update($data);
                    return "Record updated";
            }

            $this->validate($request, KelompokBisnis::validationRules());
            $act=$kelompokBisnis->update($request->except('status'));
            message($act,'Data Kelompok Bisnis berhasil diupdate','Data Kelompok Bisnis gagal diupdate');
            return redirect('/kelompok-bisnis');
        }
    }

    public function destroy(Request $request, $kode)
    {
        $kelompokBisni=KelompokBisnis::find($kode);
        $act=false;
        try {
            $act=$kelompokBisnis->forceDelete();
        } catch (\Exception $e) {
            $kelompokBisnis=KelompokBisnis::find($kelompokBisnis->pk());
            $act=$kelompokBisnis->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = KelompokBisnis::select('*');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('flag_aktif', function($data){
					
        if(isset($data->flag_aktif)){
			return array ('id'=>$data->pk(), 'flag_aktif'=>$data->flag_aktif);
		} else {
			return null;
		}
				
		})->addColumn('action', function ($data) {
        
            $edit=url("kelompok-bisnis/".$data->pk())."/edit";
            $delete=url("kelompok-bisnis/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
