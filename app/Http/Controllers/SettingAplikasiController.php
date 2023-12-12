<?php
namespace App\Http\Controllers;

use App\Models\SettingAplikasi;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SettingAplikasiController extends Controller
{
    public $viewDir = "setting_aplikasi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Setting-aplikasi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-setting-aplikasi');
    }

    public function index()
    {
        $cek = DB::table('setting_aplikasi')->select('*')->first();
        return $this->view( "index", compact('cek'));
    }

    public function create()
    {
        return $this->view("form",['settingAplikasi' => new SettingAplikasi]);
    }

    public function store( Request $request )
    {
        $this->validate($request, SettingAplikasi::validationRules());
        DB::beginTransaction();

        try {
            
            if ($request->file('logo')->getClientOriginalExtension() === 'jpg' || $request->file('logo')->getClientOriginalExtension() === 'png')
            {
                $tambahLogo = $request->file('logo');
    	        $logo = time()."_".$tambahLogo->getClientOriginalName();
    	        $folder = 'logo';
                $tambahLogo->move($folder, $logo);

                $aplikasi = new SettingAplikasi;
                $aplikasi->nama = $request->nama;
                $aplikasi->deskripsi = $request->deskripsi;
                $aplikasi->logo = $logo;
                $aplikasi->base_url = $request->base_url;
                $aplikasi->flag_morbis = 'N';
                $aplikasi->version = $request->version;
                $aplikasi->save();
            }
            DB::commit();

        } catch (Exception $e){
            DB::rollback();
        }

        if ($request->file('logo')->getClientOriginalExtension() === 'jpg' || $request->file('logo')->getClientOriginalExtension() === 'png') {
            return redirect('setting-aplikasi')->with('success', 'Data Setting Aplikasi berhasil disimpan');
        } else {
            return redirect('setting-aplikasi')->with('error', 'Data Setting Aplikasi gagal disimpan karena Logo bukan JPG atau PNG');
        }
    }

    public function show(Request $request, $kode)
    {
        $settingAplikasi=SettingAplikasi::find($kode);
        return $this->view("show",['settingAplikasi' => $settingAplikasi]);
    }

    public function edit(Request $request, $kode)
    {
        $settingAplikasi=SettingAplikasi::find($kode);
        return $this->view( "form", ['settingAplikasi' => $settingAplikasi] );
    }

    public function update(Request $request)
    {
        $settingAplikasi=SettingAplikasi::find($request->id);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SettingAplikasi::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $settingAplikasi->update($data);
                return "Record updated";
        }
        $this->validate($request, SettingAplikasi::validationRules());

        $cek = SettingAplikasi::select('logo')->where('id', $request->id)->first();
        
        if ($request->cek_logo == $cek->file || is_null($request->file('logo')))
        {
            $act= SettingAplikasi::where('id', $request->id)->update([
                'nama'=>$request->nama,
                'deskripsi'=>$request->deskripsi,
                'base_url'=>$request->base_url,
                'flag_morbis'=>isset($request->flag_morbis) ? $request->flag_morbis : 'N',
                'version'=>$request->version,]);

        } else if ($request->file('logo') !== null && $request->file('logo')->getClientOriginalExtension() === 'jpg' || $request->file('logo')->getClientOriginalExtension() === 'png'){
            
            $tambahLogo = $request->file('logo');
    	    $logo = time()."_".$tambahLogo->getClientOriginalName();
    	    $folder = 'logo';
            $tambahLogo->move($folder, $logo);

            $act= SettingAplikasi::where('id', $request->id)->update([
                'nama'=>$request->nama,
                'deskripsi'=>$request->deskripsi,
                'base_url'=>$request->base_url,
                'flag_morbis'=>isset($request->flag_morbis) ? $request->flag_morbis : 'N',
                'logo'=>$logo,
                'version'=>$request->version,]);
        }

        if ($request->cek_logo == $cek->file || is_null($request->file('logo'))){
            return redirect('/setting-aplikasi')->with('success', 'Setting Aplikasi Berhasil di update');
        } else if ($request->file('logo') !== null && $request->file('logo')->getClientOriginalExtension() === 'jpg' || $request->file('logo')->getClientOriginalExtension() === 'png'){
            return redirect('/setting-aplikasi')->with('success', 'Setting Aplikasi Berhasil di update');
        } else {
            return redirect('/setting-aplikasi')->with('error', 'Setting Aplikasi gagal di update karena logo bukan JPG atau PNG');
        }
    }

    public function destroy(Request $request, $kode)
    {
        $settingAplikasi=SettingAplikasi::find($kode);
        $act=false;
           
        try {
            $act=$settingAplikasi->forceDelete();
        } catch (\Exception $e) {
            $settingAplikasi=SettingAplikasi::find($settingAplikasi->pk());
            $act=$settingAplikasi->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SettingAplikasi::select('*');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            
            $edit=url("setting-aplikasi/".$data->pk())."/edit";
            $delete=url("setting-aplikasi/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
