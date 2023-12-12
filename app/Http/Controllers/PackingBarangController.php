<?php
namespace App\Http\Controllers;

use App\Models\PackingBarang;
use App\Models\Barang;
use App\Stok;
use App\Models\Unit;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class PackingBarangController extends Controller
{
    public $viewDir = "packing_barang";
    public $breadcrumbs = array('permissions'=>array('title'=>'Packing-barang','link'=>"#",'active'=>false,'display'=>true));

    public function __construct()
    {
        $this->middleware('permission:read-packing-barang');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $Barang = Barang::select('id', 'nama')->get();
        $Unit = Unit::select('id', 'nama')->get();

        return $this->view("form",['packingBarang' => new PackingBarang])->with('Barang', $Barang)->with('Unit', $Unit);
    }

    public function store( Request $request )
    {
        $this->validate($request, PackingBarang::validationRules());
        
        DB::beginTransaction();

        try {
            $hnaa = str_replace('.', '', $request->hna);
            $hna = str_replace(',', '.', $hnaa);
            $act= new PackingBarang;
            $act->barcode = $request->barcode;
            $act->satuan = $request->satuan;
            $act->id_barang = $request->id_barang;
            $act->save();

            $id_packing_barang = $act->id;

            $stok = new Stok;
            $stok->id_packing_barang = $id_packing_barang;
            $stok->id_unit = $request->id_unit;
            $stok->hpp = $request->hpp;
            $stok->hna = $hna;
            $stok->jumlah_stok = 0;
            $stok->save();

            DB::commit();
            message($act,'Data Packing Barang berhasil ditambahkan','Data Packing Barang gagal ditambahkan');
            return redirect('packing-barang');

        } catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Data Packing Barang gagal di simpan');
            return redirect('packing-barang');
        }
    }
     
    public function show(Request $request, $kode)
    {
        $packingBarang=PackingBarang::find($kode);
        return $this->view("show",['packingBarang' => $packingBarang]);
    }
     
    public function edit(Request $request, $kode)
    {
        $Barang = Barang::select('id', 'nama')->get();
        $Unit = Unit::select('id', 'nama')->get();
        $packingBarang= PackingBarang::selectRaw('packing_barang.id, id_barang, id_unit, barcode, satuan, hpp, hna, jumlah_stok')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->leftJoin('stok', 'stok.id_packing_barang', 'packing_barang.id')
        ->leftJoin('unit', 'unit.id', 'stok.id_unit')
        ->where('packing_barang.id', $kode)
        ->first();

        return $this->view( "form", compact('packingBarang', 'Barang', 'Unit'));
    }
      
    public function update(Request $request, $kode)
    {
        $packingBarang=PackingBarang::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, PackingBarang::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $packingBarang->update($data);
                return "Record updated";
        }
        
        $this->validate($request, PackingBarang::validationRules());
        $hnaa = str_replace('.', '', $request->hna);
        $hna = str_replace(',', '.', $hnaa);
        $act= PackingBarang::where('id', $kode)->update(['barcode'=>$request->barcode, 'satuan'=>$request->satuan, 'id_barang'=>$request->id_barang]);
        Stok::where('id_packing_barang', $kode)->update(['id_unit'=>$request->id_unit, 'hpp'=>$request->hpp, 'hna'=>$hna]);

        message($act,'Data Packing Barang berhasil diupdate','Data Packing Barang gagal diupdate');
        return redirect('/packing-barang');
    }

    public function destroy(Request $request, $kode)
    {
        $packingBarang=PackingBarang::find($kode);
        $act=false;
        
        try {
            $act=$packingBarang->forceDelete();
        } 
        catch (\Exception $e) {
            $packingBarang=PackingBarang::find($packingBarang->pk());
            $act=$packingBarang->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $barcode = request()->get('barcode');
        $barang = request()->get('barang');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = PackingBarang::selectRaw('packing_barang.id, barang.nama AS persediaan, unit.nama AS departemen, 
        barcode, satuan, hpp, hna, jumlah_stok')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->leftJoin('stok', 'stok.id_packing_barang', 'packing_barang.id')
        ->leftJoin('unit', 'unit.id', 'stok.id_unit');

        if($barcode):
            $dataList->where('barcode', 'like', $barcode.'%');
        endif;

        if($barang):
            $dataList->where('barang.nama', 'like', $barang.'%');
        endif;
        
        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('hna', function($data){
                
            if (isset($data->hna)):
                $hna = nominalKoma($data->hna);
                return $hna;
            endif;

        })->addColumn('action', function ($data) {
            $edit=url("packing-barang/".$data->pk())."/edit";
            $delete=url("packing-barang/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
