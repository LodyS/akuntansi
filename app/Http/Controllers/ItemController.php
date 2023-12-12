<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class ItemController extends Controller
{
    public $viewDir = "item";
    public $breadcrumbs = array('permissions'=>array('title'=>'Item','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-item');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['item' => new Item]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, Item::validationRules());
            $act = new Item;
            $act->nama = $request->nama;
            $act->harga = currencyToFloat($request->harga);
            $act->save();

            DB::commit();
            message($act,'Data Item berhasil ditambahkan','Data Item gagal ditambahkan');
            return redirect('item');
        } catch(Exception $e){
            DB::rollback();
            message(false,'Data Item berhasil ditambahkan','Data Item gagal ditambahkan');
            return redirect('item');
        }
    }

    public function show(Request $request, $kode)
    {
        $item=Item::find($kode);
        return $this->view("show",['item' => $item]);
    }

    public function edit(Request $request, $kode)
    {
        $item=Item::find($kode);
        $item->harga = str_replace('Rp ','', nominalTitik($item->harga));
        return $this->view( "form", ['item' => $item] );
    }

    public function update(Request $request, $kode)
    {
        $request->merge([
            'harga' => currencyToFloat($request->harga)
        ]);

        $item=Item::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Item::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $item->update($data);
                return "Record updated";
        }
        $this->validate($request, Item::validationRules());

        $act=$item->update($request->all());
        message($act,'Data Item berhasil diupdate','Data Item gagal diupdate');

        return redirect('/item');
    }

    public function destroy(Request $request, $kode)
    {
        $item=Item::find($kode);
        $act=false;

        try {
            $act=$item->forceDelete();
        } catch (\Exception $e) {
            $item=Item::find($item->pk());
            $act=$item->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Item::select('*');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('harga', function ($data) {

        if (isset($data->harga)){
            $harga = nominalTitik($data->harga);
            return $harga;
        }
        else {
            return 0;
        }
        })->addColumn('action', function ($data) {

            $edit=url("item/".$data->pk())."/edit";
            $delete=url("item/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
