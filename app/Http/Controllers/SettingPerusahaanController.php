<?php
namespace App\Http\Controllers;

use App\Models\SettingPerusahaan;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SettingPerusahaanController extends Controller
{
    public $viewDir = "setting_perusahaan";
    public $breadcrumbs = array('permissions'=>array('title'=>'Setting-perusahaan','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-setting-perusahaan');
    }

    public function index()
    {
        $cek = DB::table('setting_perusahaan')->select('*')->first();
        return $this->view( "index", compact('cek'));
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, SettingPerusahaan::validationRules());

            $act= SettingPerusahaan::updateOrCreate(['id'=>$request->id],[
                'kode'=>$request->kode,
                'nama'=>$request->nama,
                'alamat'=>$request->alamat,
                'email'=>$request->email,
                'website'=>$request->website,
                'telepon'=>$request->telepon,
                'fax'=>$request->fax,
                'tanggal_berdiri'=>$request->tanggal_berdiri,
                'url'=>$request->url,
                'kode_pos'=>$request->kode_pos
            ]);

            DB::commit();
            message($act,'Data Setting Perusahaan berhasil di simpan','Data Setting Perusahaan gagal di simpan');
            return redirect('setting-perusahaan');
        } catch (Exception $e){
            DB::rollback();
            return 'Gagal simpan karena ada error sistem';
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
}
