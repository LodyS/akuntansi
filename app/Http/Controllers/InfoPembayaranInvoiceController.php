<?php
namespace App\Http\Controllers;

use App\Models\InfoPembayaranInvoice;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\KasBank;
use Datatables;
use Illuminate\Support\Facades\Redirect;

class InfoPembayaranInvoiceController extends Controller
{
    public $viewDir = "info_pembayaran_invoice";
    public $breadcrumbs = array('permissions'=>array('title'=>'Info-pembayaran-invoice','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-info-pembayaran-invoice');
    }

    public function index()
    {
        $bank = KasBank::select('id', 'nama')->get();
        return $this->view( "index", compact('bank'));
    }
   
    public function create()
    {
        return $this->view("form",['bank' => KasBank::all()]);
    }
  
    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $messages = ['unique' => 'Maaf bank yang dipilih sudah pernah diinputkan.',];
           
            $this->validate($request, InfoPembayaranInvoice::validationRules(), $messages);

            $act=InfoPembayaranInvoice::create($request->all());
            DB::commit();
            message($act,'Data Informasi Pembayaran berhasil ditambahkan','Data Informasi Pembayaran gagal ditambahkan');
            return redirect('info-pembayaran-invoice');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Informasi Pembayaran berhasil ditambahkan','Data Informasi Pembayaran gagal ditambahkan');
            return redirect('info-pembayaran-invoice');
        }
    }

    public function show(Request $request, $kode)
    {
        $infoPembayaranInvoice=InfoPembayaranInvoice::find($kode);
        return $this->view("show",['infoPembayaranInvoice' => $infoPembayaranInvoice]);
    }

    public function edit(Request $request, $kode)
    {
        //$infoPembayaranInvoice=InfoPembayaranInvoice::find($kode);
        //return $this->view( "form", ['infoPembayaranInvoice' => $infoPembayaranInvoice] );
        return redirect()->back();
    }

    public function update(Request $request, $kode)
    {
        $infoPembayaranInvoice=InfoPembayaranInvoice::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, InfoPembayaranInvoice::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $infoPembayaranInvoice->update($data);
                return "Record updated";
            }
        $this->validate($request, InfoPembayaranInvoice::validationRules());

        $act=$infoPembayaranInvoice->update($request->all());
        message($act,'Data Info Pembayaran Invoice berhasil diupdate','Data Info Pembayaran Invoice gagal diupdate');

        return redirect('/info-pembayaran-invoice');
    }

    public function destroy(Request $request, $kode)
    {
        $infoPembayaranInvoice=InfoPembayaranInvoice::find($kode);
        $act=false;
        try {
            $act=$infoPembayaranInvoice->forceDelete();
        } catch (\Exception $e) {
            $infoPembayaranInvoice=InfoPembayaranInvoice::find($infoPembayaranInvoice->pk());
            $act=$infoPembayaranInvoice->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $id_bank = request()->get('id_bank');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = InfoPembayaranInvoice::select('info_pembayaran_invoice.id', 'kas_bank.nama as bank', 'kas_bank.rekening', 
        'info_pembayaran_invoice.created_at')
        ->leftJoin('kas_bank', 'kas_bank.id', 'info_pembayaran_invoice.id_bank');

        if($id_bank){
            $dataList->where('id_bank', $id_bank);
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        /*})->addColumn('bank', function ($data) {
            return $data->bank->nama;
        })->addColumn('rekening', function ($data) {
            return $data->bank->rekening;*/
        })->addColumn('action', function ($data) {
            $edit=url("info-pembayaran-invoice/".$data->pk())."/edit";
            $delete=url("info-pembayaran-invoice/".$data->pk());
            $content = '';
            //$content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            //data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
