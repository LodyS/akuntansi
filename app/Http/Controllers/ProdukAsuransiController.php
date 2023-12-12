<?php
namespace App\Http\Controllers;

use App\Models\ProdukAsuransi;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class ProdukAsuransiController extends Controller
{
    public $viewDir = "produk_asuransi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Produk-asuransi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-produk-asuransi');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $data = [
            'produkAsuransi'=>new ProdukAsuransi,
            'lastCode'=>ProdukAsuransi::kode(),
            'kode'=>null,
            'aksi'=>"create"
        ];

        return $this->view("form")->with($data);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, ProdukAsuransi::validationRules());

            $act=ProdukAsuransi::create($request->all());
            DB::commit();
            message($act,'Data Produk Asuransi berhasil ditambahkan','Data Produk Asuransi gagal ditambahkan');
            return redirect('produk-asuransi');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Produk Asuransi berhasil ditambahkan','Data Produk Asuransi gagal ditambahkan');
            return redirect('produk-asuransi');
        }
    }

    public function show(Request $request, $kode)
    {
        $produkAsuransi=ProdukAsuransi::find($kode);
        return $this->view("show",['produkAsuransi' => $produkAsuransi]);
    }

    public function edit(Request $request, $kode)
    {
        $data = [
            'kode'=>$kode,
            'produkAsuransi'=>ProdukAsuransi::find($kode),
            'lastCode'=>ProdukAsuransi::kode(),
            'aksi'=>"update"
        ];

        return $this->view( "form")->with($data);
    }

    public function update(Request $request, $kode)
    {
        $produkAsuransi=ProdukAsuransi::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, ProdukAsuransi::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $produkAsuransi->update($data);
                return "Record updated";
        }
        $this->validate($request, ProdukAsuransi::validationRules());

        $act=$produkAsuransi->update($request->all());
        message($act,'Data Produk Asuransi berhasil diupdate','Data Produk Asuransi gagal diupdate');

        return redirect('/produk-asuransi');
    }

    public function destroy(Request $request, $kode)
    {
        $produkAsuransi=ProdukAsuransi::find($kode);
        $act=false;
        try {
            $act=$produkAsuransi->forceDelete();
        } catch (\Exception $e) {
            $produkAsuransi=ProdukAsuransi::find($produkAsuransi->pk());
            $act=$produkAsuransi->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = ProdukAsuransi::select('*');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

        $edit=url("produk-asuransi/".$data->pk())."/edit";
        $delete=url("produk-asuransi/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
