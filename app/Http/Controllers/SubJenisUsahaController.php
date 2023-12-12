<?php
namespace App\Http\Controllers;
use DB;
use App\Models\SubJenisUsaha;
use Illuminate\Http\Request;
use App\Models\JenisUsaha;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SubJenisUsahaController extends Controller
{
    public $viewDir = "sub_jenis_usaha";
    public $breadcrumbs = array('permissions'=>array('title'=>'Sub-jenis-usaha','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-sub-jenis-usaha');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $unit = JenisUsaha::all();
        return $this->view("form",['subJenisUsaha' => new SubJenisUsaha])->with('unit', $unit);
    }

    public function cekKode ($kode)
    {
        $data = SubJenisUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, SubJenisUsaha::validationRules());
            $data = SubJenisUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('kode', $request->kode)
            ->first();

            if ($data->status == 'Ada')
            {
                message(false,'','Data Sub Jenis Usaha gagal ditambahkan karena kode sudah ada');
            }

            if ($data->status == 'Tidak Ada')
            {
                $act=SubJenisUsaha::create($request->except('status'));
                DB::commit();
                message($act,'Data Sub Jenis Usaha berhasil ditambahkan','Data Sub Jenis Usaha gagal ditambahkan');
               
            }
            return redirect('sub-jenis-usaha');

        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Sub Jenis Usaha berhasil ditambahkan','Data Sub Jenis Usaha gagal ditambahkan');
            return redirect('sub-jenis-usaha');
        }
    }
  
    public function show(Request $request, $kode)
    {
        $subJenisUsaha=SubJenisUsaha::find($kode);
        return $this->view("show",['subJenisUsaha' => $subJenisUsaha]);
    }
  
    public function edit(Request $request, $kode)
    {
        $unit = JenisUsaha::all();
        $jenis = SubJenisUsaha::select('id_jenis_usaha')->where('id', $kode)->first();
        $subJenisUsaha=SubJenisUsaha::find($kode);
        return $this->view( "form", ['subJenisUsaha' => $subJenisUsaha] )->with('unit', $unit)->with('jenis', $jenis);
    }

    public function activate(Request $request, $kode)
    { 
        $SubJenisUsaha= SubJenisUsaha::find($kode);
        $data=array('flag_aktif'=>'Y',);
         
        $status=$SubJenisUsaha->update($data);
        message($status,'Sub Jenis Usaha Berhasil Diaktifkan Kembali','Sub Jenis Usaha Gagal Diaktifkan Kembali');
         
        return redirect('/sub-jenis-usaha');
    } 
 
    public function deactivate(Request $request, $kode)
    { 
        $SubJenisUsaha=SubJenisUsaha::find($kode);
        $data=array('flag_aktif'=>'N',);
        $status=$SubJenisUsaha->update($data);
        message($status,'Sub Jenis Usaha Berhasil Dinonaktifkan','Sub Jenis Usaha Gagal Dinonaktifkan');
         
        return redirect('/sub-jenis-usaha');
    }

    public function update(Request $request, $kode)
    {
        $subJenisUsaha=SubJenisUsaha::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SubJenisUsaha::validationRules( $request->name ) );
                if($validator->fails())
                    return response($validator->errors()->first( $request->name),403);
            $subJenisUsaha->update($data);
            return "Record updated";
        }
        $this->validate($request, SubJenisUsaha::validationRules());
        $cek = SubJenisUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->where('id', '<>', $kode)
        ->first();
        
        if ($cek->status == 'Ada')
        {
            message(false,'','Data Sub Jenis Usaha gagal ditambahkan karena kode sudah ada');
            echo "Data Sub Jenis Usaha gagal diupdate karena kode sudah ada.";
        }

        if ($cek->status == 'Tidak Ada')
        {
            $act=$subJenisUsaha->update($request->except('status'));
            message($act,'Data Sub Jenis Usaha berhasil diupdate','Data Sub Jenis Usaha gagal diupdate');
            
        }
        return redirect('/sub-jenis-usaha');
    }

    public function destroy(Request $request, $kode)
    {
        $subJenisUsaha=SubJenisUsaha::find($kode);
        $act=false;
        try {
            $act=$subJenisUsaha->forceDelete();
        } catch (\Exception $e) {
            $subJenisUsaha=SubJenisUsaha::find($subJenisUsaha->pk());
            $act=$subJenisUsaha->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
    
    public function loadData()
    {
        $kode = request()->get('kode');
        $nama = request()->get('nama');

        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SubJenisUsaha::select('sub_jenis_usaha.id', 'sub_jenis_usaha.kode', 'sub_jenis_usaha.nama', 
        'sub_jenis_usaha.flag_aktif', 'jenis_usaha.nama as nama_usaha')
        ->leftJoin('jenis_usaha', 'jenis_usaha.id', 'sub_jenis_usaha.id_jenis_usaha');
           
        if ($kode) {
            $dataList->where('sub_jenis_usaha.kode', 'like', $kode.'%');
        }

        if ($nama) {
            $dataList->where('sub_jenis_usaha.nama', 'like', $nama.'%');
        }

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
            
            $edit=url("sub-jenis-usaha/".$data->pk())."/edit";
            $delete=url("sub-jenis-usaha/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
