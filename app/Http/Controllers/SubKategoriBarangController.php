<?php
namespace App\Http\Controllers;
use DB;
use App\Models\SubKategoriBarang;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SubKategoriBarangController extends Controller
{
    public $viewDir = "sub_kategori_barang";
    public $breadcrumbs = array('permissions'=>array('title'=>'Sub-kategori-barang','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-sub-kategori-barang');
    }

    public function index()
    {
        $kategoriBarang = KategoriBarang::select('id', 'nama')->get();
        return $this->view( "index", compact('kategoriBarang'));
    }

    public function create()
    {
        $kategoriBarang = KategoriBarang::select('id', 'nama')->get();
        return $this->view("form",['subKategoriBarang' => new SubKategoriBarang])->with('kategoriBarang', $kategoriBarang);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, SubKategoriBarang::validationRules());

            $act=SubKategoriBarang::create($request->all());
            DB::commit();
            message($act,'Data Sub Kategori Barang berhasil ditambahkan','Data Sub Kategori Barang gagal ditambahkan');
            return redirect('sub-kategori-barang');
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            message(false,'Data Sub Kategori Barang berhasil ditambahkan','Data Sub Kategori Barang gagal ditambahkan');
            return redirect('sub-kategori-barang');
        }
    }

    public function show(Request $request, $kode)
    {
        $subKategoriBarang=SubKategoriBarang::find($kode);
        return $this->view("show",['subKategoriBarang' => $subKategoriBarang]);
    }

    public function edit(Request $request, $kode)
    {
        $subKategoriBarang=SubKategoriBarang::find($kode);
        $kategoriBarang = KategoriBarang::select('id', 'nama')->get();
        return $this->view( "form", ['subKategoriBarang' => $subKategoriBarang] )->with('kategoriBarang', $kategoriBarang);
    }

    public function update(Request $request, $kode)
    {
        $subKategoriBarang=SubKategoriBarang::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SubKategoriBarang::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $subKategoriBarang->update($data);
                return "Record updated";
        }
        $this->validate($request, SubKategoriBarang::validationRules());

        $act=$subKategoriBarang->update($request->all());
        message($act,'Data Sub Kategori Barang berhasil diupdate','Data Sub Kategori Barang gagal diupdate');

        return redirect('/sub-kategori-barang');
    }


    public function destroy(Request $request, $kode)
    {
        $subKategoriBarang=SubKategoriBarang::find($kode);
        $act=false;
        try {
            $act=$subKategoriBarang->forceDelete();
        } catch (\Exception $e) {
            $subKategoriBarang=SubKategoriBarang::find($subKategoriBarang->pk());
            $act=$subKategoriBarang->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $kategori = request()->get('kategori');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SubKategoriBarang::select('sub_kategori_barang.id', 'sub_kategori_barang.nama', 'permintaan_penjualan',
        'kategori_barang.nama as kategori_barang')
        ->leftJoin('kategori_barang', 'kategori_barang.id', 'sub_kategori_barang.id_kategori_barang');

        if ($nama){
            $dataList->where('sub_kategori_barang.nama', 'like', $nama.'%');
        }

        if ($kategori){
            $dataList->where('sub_kategori_barang.id_kategori_barang', $kategori);
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

            $edit=url("sub-kategori-barang/".$data->pk())."/edit";
            $delete=url("sub-kategori-barang/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
             data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
             data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
