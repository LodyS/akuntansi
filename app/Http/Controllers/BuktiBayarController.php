<?php
namespace App\Http\Controllers;

use App\Models\BuktiBayar;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class BuktiBayarController extends Controller
{
    public $viewDir = "bukti_bayar";
    public $breadcrumbs = array('permissions'=>array('title'=>'Bukti-bayar','link'=>"#",'active'=>false,'display'=>true), );

    public function __construct()
    {
        $this->middleware('permission:read-bukti-bayar');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['buktiBayar' => new BuktiBayar]);
    }

    public function store( Request $request )
    {
        $this->validate($request, BuktiBayar::validationRules());
        DB::beginTransaction();

        try {

            $act=BuktiBayar::create($request->all());
            DB::commit();
            message($act,'Data Bukti Bayar berhasil ditambahkan','Data Bukti Bayar gagal ditambahkan');
            return redirect('bukti-bayar');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Bukti Bayar berhasil ditambahkan','Data Bukti Bayar gagal ditambahkan');
            return redirect('bukti-bayar');
        }
    }

    public function show(Request $request, $kode)
    {
        $buktiBayar=BuktiBayar::find($kode);
        return $this->view("show",['buktiBayar' => $buktiBayar]);
    }

    public function edit(Request $request, $kode)
    {
        $buktiBayar=BuktiBayar::find($kode);
        return $this->view( "form", ['buktiBayar' => $buktiBayar] );
    }

    public function update(Request $request, $kode)
    {
        $buktiBayar=BuktiBayar::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, BuktiBayar::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $buktiBayar->update($data);
                return "Record updated";
        }
        $this->validate($request, BuktiBayar::validationRules());

        $act=$buktiBayar->update($request->all());
        message($act,'Data Bukti Bayar berhasil diupdate','Data Bukti Bayar gagal diupdate');

        return redirect('/bukti-bayar');
    }

    public function destroy(Request $request, $kode)
    {
        $buktiBayar=BuktiBayar::find($kode);
        $act=false;
        try {
            $act=$buktiBayar->forceDelete();
        } catch (\Exception $e) {
            $buktiBayar=BuktiBayar::find($buktiBayar->pk());
            $act=$buktiBayar->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = BuktiBayar::select('*');
        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;
        
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })
        ->addColumn('action', function ($data) {

            $edit=url("bukti-bayar/".$data->pk())."/edit";
            $delete=url("bukti-bayar/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
             data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
             data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
