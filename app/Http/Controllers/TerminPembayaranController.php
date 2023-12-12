<?php
namespace App\Http\Controllers;

use App\Models\TerminPembayaran;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class TerminPembayaranController extends Controller
{
    public $viewDir = "termin_pembayaran";
    public $breadcrumbs = array('permissions'=>array('title'=>'Termin-pembayaran','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-termin-pembayaran');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['terminPembayaran' => new TerminPembayaran]);
    }

    public function cekKode ($kode)
    {
        $data = TerminPembayaran::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();
        try {

            $data = TerminPembayaran::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('kode', $request->kode)
            ->first();
            
            if ($data->status == 'Ada'):
                message(false,'','Data Termin Pembayaran gagal ditambahkan karena kode sudah ada');
                echo "Termin Pembayaran gagal ditambah karena kode sudah ada.";
                echo "<br/>";
                echo "<a href='termin_pembayaran/index.blade.php'>Kembali</a>";
            endif;

            $this->validate($request, TerminPembayaran::validationRules());

            $act=TerminPembayaran::create($request->all());
            DB::commit();
            message($act,'Data Termin Pembayaran berhasil ditambahkan','Data Termin Pembayaran gagal ditambahkan');
            return redirect('termin-pembayaran');
        } catch (Exception $e){
            DB::rollback();
            message($act,'Data Termin Pembayaran berhasil ditambahkan','Data Termin Pembayaran gagal ditambahkan');
            return redirect('termin-pembayaran');
        }
    }

    public function show(Request $request, $kode)
    {
        $terminPembayaran=TerminPembayaran::find($kode);
        return $this->view("show",['terminPembayaran' => $terminPembayaran]);
    }

    public function edit(Request $request, $kode)
    {
        $terminPembayaran=TerminPembayaran::find($kode);
        return $this->view( "form", ['terminPembayaran' => $terminPembayaran] );
    }

    public function update(Request $request, $kode)
    {
        $cek = TerminPembayaran::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->where('id', '<>', $kode)
        ->first();
        
        if ($cek->status == 'Ada'):
            message(false,'','Data Termin Pembayaran gagal ditambahkan karena kode sudah ada');
            echo "Data Termin Pembayaran gagal diupdate karena kode sudah ada.";
            echo "<br/>";
            echo "<a href='termin_pembayaran/index.blade.php'>Kembali</a>";
        endif;

        $terminPembayaran=TerminPembayaran::find($kode);
        if( $request->isXmlHttpRequest()):
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, TerminPembayaran::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $terminPembayaran->update($data);
                return "Record updated";
        endif;

        $this->validate($request, TerminPembayaran::validationRules());

        $act=$terminPembayaran->update($request->all());
        message($act,'Data Termin Pembayaran berhasil diupdate','Data Termin Pembayaran gagal diupdate');

        return redirect('/termin-pembayaran');
    }

    public function destroy(Request $request, $kode)
    {
        $terminPembayaran=TerminPembayaran::find($kode);
        $act=false;
        try {
            $act=$terminPembayaran->forceDelete();
        } catch (\Exception $e) {
            $terminPembayaran=TerminPembayaran::find($terminPembayaran->pk());
            $act=$terminPembayaran->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = TerminPembayaran::select('*');

        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;
        
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })
        ->addColumn('action', function ($data) {
                   
        $edit=url("termin-pembayaran/".$data->pk())."/edit";
        $delete=url("termin-pembayaran/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
