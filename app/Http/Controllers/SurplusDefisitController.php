<?php
namespace App\Http\Controllers;

use App\Models\SurplusDefisit;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SurplusDefisitController extends Controller
{
    public $viewDir = "surplus_defisit";
    public $breadcrumbs = array('permissions'=>array('title'=>'Surplus-defisit','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-surplus-defisit');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $aksi = 'create';
        return $this->view("form",['surplusDefisit' => new SurplusDefisit, 'aksi'=>$aksi]);
    }

    public function cek ($nama)
    {
        $data = SurplusDefisit::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('nama', $nama)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        $this->validate($request, SurplusDefisit::validationRules());

        $data = SurplusDefisit::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('nama', $request->nama)
        ->first();

        if ($data->status == 'Tidak Ada')
        {
            SurplusDefisit::where('urutan', '>=', $request->urutan)->increment('urutan',1);

            $act=SurplusDefisit::create($request->all());
            message($act,'Data Surplus Defisit berhasil ditambahkan','Data Surplus Defisit gagal ditambahkan');
            return redirect('surplus-defisit');
        }

        if ($data->status == 'Ada')
        {
            message(false, '','Gagal simpan karena Surplus Defisit sudah ada');
            return redirect('surplus-defisit')->with('danger', 'Gagal simpan karena Surplus Defisit sudah ada');
        } else {
            return redirect('surplus-defisit')->with('success', 'Surplus Defisit berhasil simpan');
        }
    }

    public function show(Request $request, $kode)
    {
        $surplusDefisit=SurplusDefisit::find($kode);
        return $this->view("show",['surplusDefisit' => $surplusDefisit]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi = 'update';
        $surplusDefisit=SurplusDefisit::find($kode);
        return $this->view( "form", ['surplusDefisit' => $surplusDefisit, 'aksi'=>$aksi] );
    }

    public function update(Request $request, $kode)
    {
        $surplusDefisit=SurplusDefisit::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SurplusDefisit::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $surplusDefisit->update($data);
                return "Record updated";
            }

        $this->validate($request, SurplusDefisit::validationRules());
        SurplusDefisit::where('urutan', '>=', $request->urutan)->increment('urutan',1);
        $act=$surplusDefisit->update($request->all());
        message($act,'Data Surplus Defisit berhasil diupdate','Data Surplus Defisit gagal diupdate');

        return redirect('/surplus-defisit');
    }

    public function destroy(Request $request, $kode)
    {
        $surplusDefisit=SurplusDefisit::find($kode);

        $urutan = SurplusDefisit::select('urutan')->where('id', $kode)->first();

        $act=false;
        try {

            SurplusDefisit::where('urutan', '>=', $urutan->urutan)->decrement('urutan',1);
            $act=$surplusDefisit->forceDelete();
        } catch (\Exception $e) {
            SurplusDefisit::where('urutan', '>=', $urutan->urutan)->decrement('urutan',1);
            $surplusDefisit=SurplusDefisit::find($surplusDefisit->pk());
            $act=$surplusDefisit->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SurplusDefisit::select('*')->orderBy('urutan');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

            $edit=url("surplus-defisit/".$data->pk())."/edit";
            $delete=url("surplus-defisit/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
