<?php
namespace App\Http\Controllers;
use App\Jurnal;
use App\Models\TipeJurnal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class TipeJurnalController extends Controller
{
    public $viewDir = "tipe_jurnal";
    public $breadcrumbs = array('permissions'=>array('title'=>'Tipe-jurnal','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-tipe-jurnal');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['tipeJurnal' => new TipeJurnal]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, TipeJurnal::validationRules());

            $act=TipeJurnal::create($request->all());
            DB::commit();
            message($act,'Data Tipe Jurnal berhasil ditambahkan','Data Tipe Jurnal gagal ditambahkan');
            return redirect('tipe-jurnal');
        }
        catch (Exception $e){
            DB::rollback();
            message($act,'Data Tipe Jurnal berhasil ditambahkan','Data Tipe Jurnal gagal ditambahkan');
            return redirect('tipe-jurnal');
        }
    }

    public function show(Request $request, $kode)
    {
        $tipeJurnal=TipeJurnal::find($kode);
        return $this->view("show",['tipeJurnal' => $tipeJurnal]);
    }

    public function edit(Request $request, $kode)
    {
        $tipeJurnal=TipeJurnal::find($kode);
        return $this->view( "form", ['tipeJurnal' => $tipeJurnal] );
    }

    public function update(Request $request, $kode)
    {
        $tipeJurnal=TipeJurnal::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, TipeJurnal::validationRules( $request->name ) );

            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $tipeJurnal->update($data);
            return "Record updated";
        }

        $this->validate($request, TipeJurnal::validationRules());

        $act=$tipeJurnal->update($request->all());
        message($act,'Data Tipe Jurnal berhasil diupdate','Data Tipe Jurnal gagal diupdate');

        return redirect('/tipe-jurnal');
    }

    public function destroy(Request $request, $kode)
    {
        $tipeJurnal=TipeJurnal::find($kode);
        $act=false;

        try {
            $act=$tipeJurnal->forceDelete();
           }
           catch (\Exception $e) {
            $tipeJurnal=TipeJurnal::find($tipeJurnal->pk());
            $act=$tipeJurnal->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $query = TipeJurnal::select('id','kode_jurnal', 'jenis_jurnal as macam_jurnal', 'kode_jurnal as kode_terakhir','tipe_jurnal', 'jenis_jurnal')
        ->whereNotIn('id', Jurnal::select('id_tipe_jurnal'));

        $dataList = Jurnal::select('jurnal.id', 'tipe_jurnal.kode_jurnal', 'jenis_jurnal as macam_jurnal', 'jurnal.kode_jurnal as kode_terakhir',
        'tipe_jurnal.tipe_jurnal', 'tipe_jurnal.jenis_jurnal')
        ->join('tipe_jurnal', 'tipe_jurnal.id', 'jurnal.id_tipe_jurnal')
        ->whereIn('jurnal.id', Jurnal::selectRaw('MAX(id)')
        ->groupBy('id_tipe_jurnal'))
        ->unionAll($query)
        ->get();

        if (request()->get('status') == 'trash')
        {
             $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)
            ->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('jenis_jurnal', function($data){
            if(isset($data->jenis_jurnal)){
            return array ('id'=>$data->pk(), 'jenis_jurnal'=>$data->jenis_jurnal);

        } else {
             return null;
        }

        })->addColumn('action', function ($data) {

            $edit = url("tipe-jurnal/".$data->pk())."/edit";
            $delete = url("tipe-jurnal/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= "<a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
