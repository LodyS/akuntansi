<?php
namespace App\Http\Controllers;
use DB;
use App\Models\SubUnitUsaha;
use Illuminate\Http\Request;
use App\sub_jenis_usaha;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SubUnitUsahaController extends Controller
{
    public $viewDir = "sub_unit_usaha";
    public $breadcrumbs = array('permissions'=>array('title'=>'Sub-unit-usaha','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-sub-unit-usaha');
    }

    public function index()
    {
        return $this->view("index");
    }
    
    public function create()
    {
        $unit = sub_jenis_usaha::select('id', 'nama')->get();
        //$jenis = SubUnitUsaha::select('id_sub_jenis_usaha')->first();
        $subUnitUsaha = new SubUnitUsaha;

        return $this->view("form", compact('subUnitUsaha', 'unit'));
    }

    public function cekKode ($kode)
    {
        $data = SubUnitUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        $data = SubUnitUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->first();
            
        if ($data->status == 'Ada')
        {
            message(false,'','Data Kelompok Bisnis gagal ditambahkan karena kode sudah ada');
            //return redirect('kelompok-bisnis');
            return 'Data Sub Unit Usaha gagal ditambah karena kode sudah ada';
        }

        try {
            $this->validate($request, SubUnitUsaha::validationRules());
            $act=SubUnitUsaha::create($request->all());
            DB::commit();
            message($act,'Data Sub Unit Usaha berhasil ditambahkan','Data Sub Unit Usaha gagal ditambahkan');
            return redirect('sub-unit-usaha');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Sub Unit Usaha berhasil ditambahkan','Data Sub Unit Usaha gagal ditambahkan');
            return redirect('sub-unit-usaha');
        }
    }

    public function show(Request $request, $kode)
    {
        $subUnitUsaha=SubUnitUsaha::find($kode);
        return $this->view("show",['subUnitUsaha' => $subUnitUsaha]);
    }

    public function activate(Request $request, $kode)
    {
        $SubUnitUsaha= SubUnitUsaha::find($kode);
        $data=array('flag_aktif'=>'Y',);
        $status=$SubUnitUsaha->update($data);
        message($status,'Sub Unit Usaha Berhasil Diaktifkan Kembali','Sub Unit Usaha Gagal Diaktifkan Kembali');
         
        return redirect('/sub-unit-usaha');
    } 
 
    public function deactivate(Request $request, $kode)
    {     
        $SubUnitUsaha=SubUnitUsaha::find($kode);
        $data=array('flag_aktif'=>'N',);
        
        $status=$SubUnitUsaha->update($data);
        message($status,'Sub Unit Usaha Berhasil Dinonaktifkan','Sub Unit Usaha Gagal Dinonaktifkan');
         
        return redirect('/sub-unit-usaha');
    }

    public function edit(Request $request, $kode)
    {
        $unit = sub_jenis_usaha::all();
        $jenis = SubUnitUsaha::select('id_sub_jenis_usaha')->where('id', $kode)->first();
        $subUnitUsaha=SubUnitUsaha::find($kode);
        return $this->view( "form", ['subUnitUsaha' => $subUnitUsaha] )->with('unit', $unit)->with('jenis', $jenis);
    }

    public function update(Request $request, $kode)
    {
        $cek = SubUnitusaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->where('id', '<>', $kode)
        ->first();
        
        if ($cek->status == 'Ada')
        {
            message(false,'','Data Kelompok Bisnis gagal ditambahkan karena kode sudah ada');
            //return redirect('kelompok-bisnis');
            return 'Data Sub Unit Usaha gagal diupdate karena kode sudah ada';
        }

        $subUnitUsaha=SubUnitUsaha::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SubUnitUsaha::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $subUnitUsaha->update($data);
            return "Record updated";
        }
        $this->validate($request, SubUnitUsaha::validationRules());
        $act=$subUnitUsaha->update($request->all());
        message($act,'Data Sub Unit Usaha berhasil diupdate','Data Sub Unit Usaha gagal diupdate');

        return redirect('/sub-unit-usaha');
    }

    public function destroy(Request $request, $kode)
    {
        $subUnitUsaha=SubUnitUsaha::find($kode);
        $act=false;
        
        try {
            $act=$subUnitUsaha->forceDelete();
            } catch (\Exception $e) {
            $subUnitUsaha=SubUnitUsaha::find($subUnitUsaha->pk());
            $act=$subUnitUsaha->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $kode = request()->get('kode');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SubUnitUsaha::select('sub_unit_usaha.id', 'sub_unit_usaha.kode', 'sub_unit_usaha.nama', 'sub_unit_usaha.flag_aktif', 
        'sub_jenis_usaha.nama as unit_usaha')
        ->join('sub_jenis_usaha', 'sub_jenis_usaha.id', 'sub_unit_usaha.id_sub_jenis_usaha');

        if ($nama) {
            $dataList->where('sub_unit_usaha.nama', 'like', $nama.'%');
        }

        if ($kode) {
            $dataList->where('sub_unit_usaha.kode', 'like', $kode.'%');
        }

        if (request()->get('status') == 'trash') 
        {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori)
        {
            return $GLOBALS['nomor']++;
        })->addColumn('flag_aktif', function($data){
                
        if(isset($data->flag_aktif))
        {
            return array ('id'=>$data->pk(), 'flag_aktif'=>$data->flag_aktif);
        } else {
            return null;
        }
            
        })->addColumn('action', function ($data) 
        {       
            $edit=url("sub-unit-usaha/".$data->pk())."/edit";
            $delete=url("sub-unit-usaha/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
