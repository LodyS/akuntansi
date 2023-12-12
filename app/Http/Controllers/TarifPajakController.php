<?php
namespace App\Http\Controllers;
use App\Models\Perkiraan;
use App\Models\TarifPajak;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class TarifPajakController extends Controller
{
    public $viewDir = "tarif_pajak";
    public $breadcrumbs = array('permissions'=>array('title'=>'Tarif-pajak','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-tarif-pajak');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $tarifPajak = new TarifPajak;
        $perkiraan = Perkiraan::select('id', 'nama', 'kode_rekening')->get();
        return $this->view("form", compact('tarifPajak', 'perkiraan'));
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, TarifPajak::validationRules());
            $act=TarifPajak::create($request->all());
            DB::commit();
            message($act,'Data Tarif Pajak berhasil ditambahkan','Data Tarif Pajak gagal ditambahkan');
            return redirect('tarif-pajak');

        } catch (Exception $e){

            DB::rollback();
            message(false, 'Data Tarif Pajak gagal disimpan', 'Data Tarif Pajak Gagal disimpan');
			return redirect('/tarif-pajak');
        }
    }

    public function show(Request $request, $kode)
    {
        $tarifPajak=TarifPajak::find($kode);
        return $this->view("show",['tarifPajak' => $tarifPajak]);
    }

    public function edit(Request $request, $kode)
    {
        $tarifPajak=TarifPajak::find($kode);
        $perkiraan = Perkiraan::select('id', 'nama', 'kode_rekening')->get();
        return $this->view( "form", compact('tarifPajak', 'perkiraan'));
    }

    public function update(Request $request, $kode)
    {
        $tarifPajak=TarifPajak::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, TarifPajak::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $tarifPajak->update($data);
                return "Record updated";
        }

        $this->validate($request, TarifPajak::validationRules());
        $act=$tarifPajak->update($request->all());
        message($act,'Data Tarif Pajak berhasil diupdate','Data Tarif Pajak gagal diupdate');
        return redirect('/tarif-pajak');
    }

    public function activate(Request $request, $kode)
    {
        // dd($kode);
        $tarifPajak=TarifPajak::find($kode);
        $data=array('status_aktif'=>'Y',);

        $status=$tarifPajak->update($data);
        message($status,'Tarif Pajak Berhasil Diaktifkan Kembali','Tarif Pajak Gagal Diaktifkan Kembali');
        return redirect('tarif-pajak');
    }

    public function deactivate(Request $request, $kode)
    {

        $tarifPajak=TarifPajak::find($kode);
        $data=array('status_aktif'=>'N',);

        $status=$tarifPajak->update($data);
        message($status,'Tarif Pajak Berhasil Dinonaktifkan','Tarif Pajak Gagal Dinonaktifkan');

        return redirect('tarif-pajak');
    }

    public function destroy(Request $request, $kode)
    {

        $tarifPajak=TarifPajak::find($kode);
        $act=false;

        try {
            $act=$tarifPajak->forceDelete();
        } catch (\Exception $e) {
            $tarifPajak=TarifPajak::find($tarifPajak->pk());
            $act=$tarifPajak->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = TarifPajak::selectRaw("tarif_pajak.id, nama_pajak, persentase_pajak, status_aktif, concat(perkiraan.kode_rekening,'', ' - ','',
        perkiraan.nama) as rekening")
        ->leftJoin('perkiraan', 'perkiraan.id', 'tarif_pajak.id_perkiraan');

        if (request()->get('status') == 'trash') {

            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){

            return $GLOBALS['nomor']++;
        })->addColumn('status_aktif', function ($data) {

        if(isset($data->status_aktif))
        {
            return array('id'=>$data->pk(),'status_aktif'=>$data->status_aktif);

        } else {

            return null;
        }

        })->addColumn('action', function ($data) {

        $edit=url("tarif-pajak/".$data->pk())."/edit";
        $delete=url("tarif-pajak/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' ><span class='btn btn-sm btn-outline-success'>Edit</span></a>";
        //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
        //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
