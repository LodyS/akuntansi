<?php
namespace App\Http\Controllers;
use DB;
use App\Models\JenisUsaha;
use Illuminate\Http\Request;
use App\Models\KelompokBisnis;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class JenisUsahaController extends Controller
{
    public $viewDir = "jenis_usaha";
    public $breadcrumbs = array('permissions'=>array('title'=>'Jenis-usaha','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-jenis-usaha');
    }

    public function index()
    {
        $kelompokBisnis = KelompokBisnis::select('id', 'nama')->get();
        return $this->view( "index", compact('kelompokBisnis'));
    }

    public function create()
    {
        $kelompokBisnis = KelompokBisnis::select('id', 'nama')->get();
        return $this->view("form",['jenisUsaha' => new JenisUsaha])->with('kelompokBisnis', $kelompokBisnis);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, JenisUsaha::validationRules());

            $cek = JenisUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $request->kode)->first();

            if ($cek->status == 'Tidak Ada')
            {
                $act=JenisUsaha::create($request->all());
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }

        if ($cek->status == 'Ada')
        {
            return redirect('jenis-usaha')->with('error', 'Kode Jenis usaha sudah ada');
        } else if ($cek->status == 'Tidak Ada'){
            return redirect('jenis-usaha')->with('success', 'Jenis usaha berhasil disimpan');
        }
    }

    public function cekKodeBadanUsaha ($kode)
    {
        $data = JenisUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();

        echo json_encode($data);
        exit;
    }

    public function show(Request $request, $kode)
    {
        $jenisUsaha=JenisUsaha::find($kode);
        return $this->view("show",['jenisUsaha' => $jenisUsaha]);
    }

    public function edit(Request $request, $kode)
    {
        $jenisUsaha=JenisUsaha::find($kode);
        $kelompokBisnis = KelompokBisnis::all();
        $jenis = JenisUsaha::select('id_kelompok_bisnis')->where('id', $kode)->first();
        return $this->view( "form", ['jenisUsaha' => $jenisUsaha] )->with('kelompokBisnis', $kelompokBisnis)->with('jenis', $jenis);
    }

    public function activate(Request $request, $kode)
    {
        $jenisUsaha= JenisUsaha::find($kode);
        $data=array('flag_aktif'=>'Y',);
         
         $status=$jenisUsaha->update($data);
         message($status,'Jenis Usaha Berhasil Diaktifkan Kembali','Jenis Usaha Gagal Diaktifkan Kembali');
         
        return redirect('/jenis-usaha');
    } 
 
    public function deactivate(Request $request, $kode)
    {     
        $jenisUsaha=JenisUsaha::find($kode);
        $data=array('flag_aktif'=>'N',);
        
        $status=$jenisUsaha->update($data);
        message($status,'Jenis Usaha Berhasil Dinonaktifkan','Jenis Usaha Gagal Dinonaktifkan');
         
        return redirect('/jenis-usaha');
    }

    public function update(Request $request, $kode)
    {
        $jenisUsaha=JenisUsaha::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, JenisUsaha::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $jenisUsaha->update($data);
               return "Record updated";
        }
        $this->validate($request, JenisUsaha::validationRules());

        $cek = JenisUsaha::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->where('id', '<>', $kode)
        ->first();

        if ($cek->status == 'Ada')
        {
            message(false, 'Gagal simpan karena kode sudah ada', 'Gagal Simpan karena kode Jenis Usaha sudah ada');
        }

        if ($cek->status == 'Tidak Ada')
        {
            $act=$jenisUsaha->update($request->all());
            message($act,'Data Jenis Usaha berhasil diupdate','Data Jenis Usaha gagal diupdate');
        }
        return redirect('/jenis-usaha');
    }

    public function destroy(Request $request, $kode)
    {
        $jenisUsaha=JenisUsaha::find($kode);
        $act=false;
        try {
            $act=$jenisUsaha->forceDelete();
        } catch (\Exception $e) {
            $jenisUsaha=JenisUsaha::find($jenisUsaha->pk());
            $act=$jenisUsaha->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $kelompok = request()->get('kelompok_bisnis');
        $kode = request()->get('kode');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = JenisUsaha::select('jenis_usaha.id','jenis_usaha.kode', 'jenis_usaha.nama', 'jenis_usaha.flag_aktif', 
        'kelompok_bisnis.nama as kelompok_bisnis')
        ->leftJoin('kelompok_bisnis', 'kelompok_bisnis.id', '=', 'jenis_usaha.id_kelompok_bisnis');

        if ($nama){
            $dataList->where('jenis_usaha.nama', 'like', $nama.'%');
        }

        if($kode){
            $dataList->where('jenis_usaha.kode', 'like', $kode.'%');
        }

        if ($kelompok){
            $dataList->where('kelompok_bisnis.id', $kelompok);
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
            $edit=url("jenis-usaha/".$data->pk())."/edit";
            $delete=url("jenis-usaha/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
