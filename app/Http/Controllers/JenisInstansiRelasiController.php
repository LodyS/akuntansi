<?php
namespace App\Http\Controllers;

use App\Models\JenisInstansiRelasi;
use App\Models\InstansiRelasi;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class JenisInstansiRelasiController extends Controller
{
    public $viewDir = "jenis_instansi_relasi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Jenis-instansi-relasi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-jenis-instansi-relasi');
    }

    public function index()
    {
        return $this->view( "index");
    }

       /**
        * Show the form for creating a new resource.
        *
        * @return  \Illuminate\Http\Response
        */
    public function create()
    {
        return $this->view("form",['jenisInstansiRelasi' => new JenisInstansiRelasi]);
    }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
                
            $this->validate($request, JenisInstansiRelasi::validationRules());

            $act=JenisInstansiRelasi::create($request->all());
            DB::commit();
            message($act,'Data Jenis Instansi Relasi berhasil ditambahkan','Data Jenis Instansi Relasi gagal ditambahkan');
            return redirect('jenis-instansi-relasi');
        } catch (Exception $e){
            DB::rollback();
            message($act,'Data Jenis Instansi Relasi berhasil ditambahkan','Data Jenis Instansi Relasi gagal ditambahkan');
            return redirect('jenis-instansi-relasi');
        }
    }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
    public function show(Request $request, $kode)
    {
        $jenisInstansiRelasi=JenisInstansiRelasi::find($kode);
        return $this->view("show",['jenisInstansiRelasi' => $jenisInstansiRelasi]);
    }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
    public function edit(Request $request, $kode)
    {
        $jenisInstansiRelasi=JenisInstansiRelasi::find($kode);
        return $this->view( "form", ['jenisInstansiRelasi' => $jenisInstansiRelasi] );
    }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
    public function update(Request $request, $kode)
    {
        $jenisInstansiRelasi=JenisInstansiRelasi::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, JenisInstansiRelasi::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $jenisInstansiRelasi->update($data);
                return "Record updated";
        }
        $this->validate($request, JenisInstansiRelasi::validationRules());

        $act=$jenisInstansiRelasi->update($request->all());
        message($act,'Data Jenis Instansi Relasi berhasil diupdate','Data Jenis Instansi Relasi gagal diupdate');

        return redirect('/jenis-instansi-relasi');
    }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
    public function destroy(Request $request, $kode)
    {
        $jenisInstansiRelasi=JenisInstansiRelasi::find($kode);
        $act=false;
        try {
            $act=$jenisInstansiRelasi->forceDelete();
        } catch (\Exception $e) {
            $jenisInstansiRelasi=JenisInstansiRelasi::find($jenisInstansiRelasi->pk());
            $act=$jenisInstansiRelasi->delete();
        }
    }

    public function detail(Request $request, $id)
    {
        $jenis_instansi = JenisInstansiRelasi::find($id);
        $instansi_relasi = $jenis_instansi->instansi_relasi;
        return $this->view( "detail", ['jenis_instansi'=>$jenis_instansi, 'instansi_relasi' => $instansi_relasi] );
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = JenisInstansiRelasi::select('*');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
                   
        $detail=url("jenis-instansi-relasi/".$data->pk())."/detail";
        $edit=url("jenis-instansi-relasi/".$data->pk())."/edit";
        $delete=url("jenis-instansi-relasi/".$data->pk());
        $content = '';
        $content .= "<a href='$detail' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Detail'><i class='icon md-assignment' aria-hidden='true'></i> Detail</a>";
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i> Edit</a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i> Hapus</a>";

        return $content;
        })->make(true);
    }
}