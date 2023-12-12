<?php
namespace App\Http\Controllers;
use DB;
use App\Models\CabangUser;
use Illuminate\Http\Request;
use App\User;
use App\Models\Perusahaan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class CabangUserController extends Controller
{
    public $viewDir = "cabang_user";
    public $breadcrumbs = array('permissions'=>array('title'=>'Cabang-user','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-cabang-user');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $user = User::all();
        $Perusahaan = Perusahaan::all();

        return $this->view("form",['cabangUser' => new CabangUser])->with('user', $user)->with('Perusahaan', $Perusahaan);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();
        try {
            $this->validate($request, CabangUser::validationRules());

            $act=CabangUser::create($request->all());
            DB::commit();
            message($act,'Data Cabang User berhasil ditambahkan','Data Cabang User gagal ditambahkan');
            return redirect('cabang-user');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Cabang User berhasil ditambahkan','Data Cabang User gagal ditambahkan');
            return redirect('cabang-user');
        }
    }

    public function show(Request $request, $kode)
    {
        $cabangUser=CabangUser::find($kode);
        return $this->view("show",['cabangUser' => $cabangUser]);
    }

    public function edit(Request $request, $kode)
    {
        $cabangUser=CabangUser::find($kode);
        return $this->view( "form", ['cabangUser' => $cabangUser] );
    }

    public function update(Request $request, $kode)
    {
        $cabangUser=CabangUser::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, CabangUser::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $cabangUser->update($data);
            return "Record updated";
        }
        $this->validate($request, CabangUser::validationRules());

        $act=$cabangUser->update($request->all());
        message($act,'Data Cabang User berhasil diupdate','Data Cabang User gagal diupdate');

        return redirect('/cabang-user');
    }

    public function destroy(Request $request, $kode)
    {
        $cabangUser=CabangUser::find($kode);
        $act=false;
        try {
            $act=$cabangUser->forceDelete();
        } catch (\Exception $e) {
            $cabangUser=CabangUser::find($cabangUser->pk());
            $act=$cabangUser->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $nama = request()->get('nama');
        $perusahaan = request()->get('perusahaan');

        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = CabangUser::select('cabang_user.id', 'cabang_user.nama', 'perusahaan.nama_badan_usaha')
        ->leftJoin('perusahaan', 'perusahaan.id', 'cabang_user.id_perusahaan');

        if ($nama) {
            $dataList->where('cabang_user.nama', 'like', $nama.'%');
        }

        if ($perusahaan) {
            $dataList->where('perusahaan.nama_badan_usaha', 'like', $perusahaan.'%');
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
           
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            
            $edit=url("cabang-user/".$data->pk())."/edit";
            $delete=url("cabang-user/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
