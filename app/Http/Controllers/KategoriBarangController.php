<?php
namespace App\Http\Controllers;
use DB;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use App\Models\Perkiraan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KategoriBarangController extends Controller
{
    public $viewDir = "kategori_barang";
    public $breadcrumbs = array('permissions'=>array('title'=>'Kategori-barang','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-kategori-barang');
    }

    public function index()
    {
        $kategori = KategoriBarang::select('id', 'nama')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        return $this->view( "index", compact('perkiraan', 'kategori'));
    }

    public function create()
    {
        $perkiraan = Perkiraan::select('id', 'nama')->get();

        return $this->view("form",['kategoriBarang' => new KategoriBarang])->with('perkiraan', $perkiraan);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, KategoriBarang::validationRules());

            $act=KategoriBarang::create($request->all());
            DB::commit();
            message($act,'Data Kategori Barang berhasil ditambahkan','Data Kategori Barang gagal ditambahkan');
            return redirect('kategori-barang');
        } catch(Exception $e){
            DB::rollback();
            message(false,'Data Kategori Barang berhasil ditambahkan','Data Kategori Barang gagal ditambahkan');
            return redirect('kategori-barang');
        }
    }

    public function show(Request $request, $kode)
    {
        $kategoriBarang=KategoriBarang::find($kode);
        return $this->view("show",['kategoriBarang' => $kategoriBarang]);
    }

    public function edit(Request $request, $kode)
    {
        $kategoriBarang=KategoriBarang::find($kode);
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        return $this->view( "form", ['kategoriBarang' => $kategoriBarang])->with('perkiraan', $perkiraan);
    }

    public function update(Request $request, $kode)
    {
        $kategoriBarang=KategoriBarang::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, KategoriBarang::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $kategoriBarang->update($data);
            return "Record updated";
        }
        $this->validate($request, KategoriBarang::validationRules());

        $act=$kategoriBarang->update($request->all());
        message($act,'Data Kategori Barang berhasil diupdate','Data Kategori Barang gagal diupdate');

        return redirect('/kategori-barang');
    }

    public function destroy(Request $request, $kode)
    {
        $kategoriBarang=KategoriBarang::find($kode);
        $act=false;
        try {
            $act=$kategoriBarang->forceDelete();
        } catch (\Exception $e) {
            $kategoriBarang=KategoriBarang::find($kategoriBarang->pk());
            $act=$kategoriBarang->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $perkiraan = request()->get('id_perkiraan');
        $nama = request()->get('nama');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = KategoriBarang::select('kategori_barang.id', 'kategori_barang.nama', 'perkiraan.nama as coa_persediaan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kategori_barang.id_perkiraan');

        if ($nama)
        {
            $dataList->where('kategori_barang.id', $nama);
        }

        if ($perkiraan)
        {
            $dataList->where('kategori_barang.id_perkiraan', $perkiraan);
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
           
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            $edit=url("kategori-barang/".$data->pk())."/edit";
            $delete=url("kategori-barang/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
