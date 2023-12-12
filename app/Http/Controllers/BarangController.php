<?php
namespace App\Http\Controllers;
use DB;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\SubKategoriBarang;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class BarangController extends Controller
{
    public $viewDir = "barang";
    public $breadcrumbs = array('permissions'=>array('title'=>'Barang','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-barang');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $SubKategoriBarang = SubKategoriBarang::select('id', 'nama')->get();
        return $this->view("form",['barang' => new Barang, 'SubKategoriBarang'=>$SubKategoriBarang]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, Barang::validationRules());

            $act=Barang::create($request->all());
            DB::commit();
            message($act,'Data Barang berhasil ditambahkan','Data Barang gagal ditambahkan');
            return redirect('barang');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Barang berhasil ditambahkan','Data Barang gagal ditambahkan');
            return redirect('barang');
        }
    }
        
    public function show(Request $request, $kode)
    {
        $barang=Barang::find($kode);
        return $this->view("show",['barang' => $barang]);
    }

    public function edit(Request $request, $kode)
    {
        $barang=Barang::find($kode);
        $SubKategoriBarang = SubKategoriBarang::select('id', 'nama')->get();
        return $this->view( "form", ['barang' => $barang])->with('SubKategoriBarang', $SubKategoriBarang);
    }

    public function update(Request $request, $kode)
    {
        $barang=Barang::find($kode);
        if($request->isXmlHttpRequest()):
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Barang::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $barang->update($data);
                return "Record updated";
        endif;

        $this->validate($request, Barang::validationRules());

        $act=$barang->update($request->all());
        message($act,'Data Barang berhasil diupdate','Data Barang gagal diupdate');

        return redirect('/barang');
    }

    public function destroy(Request $request, $kode)
    {
        $barang=Barang::find($kode);
        $act=false;
        try {
            $act=$barang->forceDelete();
        } catch (\Exception $e) {
            $barang=Barang::find($barang->pk());
            $act=$barang->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $barang = request()->get('nama');
        $sub = request()->get('sub');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Barang::select('barang.id', 'barang.nama', 'sub_kategori_barang.nama as sub_kategori_barang')
        ->leftJoin('sub_kategori_barang', 'sub_kategori_barang.id', 'barang.id_sub_kategori_barang');
           
        if ($barang):
            $dataList->where('barang.nama', 'like', $barang.'%');
        endif;

        if ($sub):
            $dataList->where('sub_kategori_barang.nama', 'like', $sub.'%');
        endif;

        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;
           
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })
        ->addColumn('action', function ($data) {
                   
            $edit=url("barang/".$data->pk())."/edit";       
            $delete=url("barang/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}