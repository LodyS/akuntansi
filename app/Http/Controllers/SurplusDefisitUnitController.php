<?php
namespace App\Http\Controllers;
use App\Models\SurplusDefisitDetail;
use App\Models\SurplusDefisitUnit;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Http\Requests;
use DB;
use App\Http\Controllers\Controller;
use Datatables;

class SurplusDefisitUnitController extends Controller
{
    public $viewDir = "surplus_defisit_unit";
    public $breadcrumbs = array('permissions'=>array('title'=>'Surplus-defisit-unit','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-surplus-defisit-unit');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $surplusDefisitDetail = SurplusDefisitDetail::all();
        $surplusDefisitUnit = new SurplusDefisitUnit;
        $unit = Unit::all(['id', 'nama', 'code_cost_centre']);

        return $this->view("form", compact('surplusDefisitUnit', 'surplusDefisitDetail','unit'));
    }

    public function store( Request $request )
    {
        $this->validate($request, SurplusDefisitUnit::validationRules());

        $act=SurplusDefisitUnit::create($request->all());
        message($act,'Data Surplus Defisit Unit berhasil ditambahkan','Data Surplus Defisit Unit gagal ditambahkan');
        return redirect('surplus-defisit-unit');
    }

    public function show(Request $request, $kode)
    {
        $surplusDefisitUnit=SurplusDefisitUnit::find($kode);
        return $this->view("show",['surplusDefisitUnit' => $surplusDefisitUnit]);
    }

    public function detail ($id)
    {
        $surplusUnit = SurplusDefisitUnit::select('surplus_defisit_detail.nama', 'id_surplus_defisit_detail')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_unit.id_surplus_defisit_detail')
        ->where('surplus_defisit_unit.id', $id)
        ->firstOrFail();

        $data = SurplusDefisitUnit::select('surplus_defisit_unit.id as id', 'unit.nama')
        ->leftJoin('unit', 'unit.id', 'surplus_defisit_unit.id_unit')
        ->where('id_surplus_defisit_detail', $surplusUnit->id_surplus_defisit_detail)
        ->paginate(100);

        return $this->view('detail', compact('surplusUnit', 'data'));
    }

    public function edit(Request $request, $kode)
    {
        $unit = Unit::all(['id', 'nama', 'code_cost_centre']);
        $surplusDefisitDetail = SurplusDefisitDetail::all();
        $surplusDefisitUnit=SurplusDefisitUnit::find($kode);
        return $this->view( "form", compact('surplusDefisitUnit', 'surplusDefisitDetail','unit') );
    }

    public function editt (Request $request)
    {
        $data = SurplusDefisitUnit::selectRaw
        ('surplus_defisit_unit.id, concat(surplus_defisit_detail.nama, " - ", unit.nama) as nama')
        ->leftJoin('unit', 'unit.id', 'surplus_defisit_unit.id_unit')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_unit.id_surplus_defisit_detail')
        ->where('surplus_defisit_unit.id', $request->id)
        ->first();

        echo json_encode($data);
    }

    public function apdet(Request $request)
    {
        SurplusDefisitUnit::where('id', $request->id)->update(['id_unit' =>$request->id_unit]);
        message(true, 'Berhasil di update', 'Gagal Di update');
        return redirect('/surplus-defisit-unit');
    }

    public function update(Request $request, $kode)
    {
        $surplusDefisitUnit=SurplusDefisitUnit::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SurplusDefisitUnit::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $surplusDefisitUnit->update($data);
                return "Record updated";
        }

        $this->validate($request, SurplusDefisitUnit::validationRules());

        $act=$surplusDefisitUnit->update($request->all());
        message($act,'Data Surplus Defisit Unit berhasil diupdate','Data Surplus Defisit Unit gagal diupdate');

        return redirect('/surplus-defisit-unit');
    }

    public function destroy(Request $request, $kode)
    {
        $surplusDefisitUnit=SurplusDefisitUnit::find($kode);
        $act=false;
        try {
            $act=$surplusDefisitUnit->forceDelete();
        } catch (\Exception $e) {
            $surplusDefisitUnit=SurplusDefisitUnit::find($surplusDefisitUnit->pk());
            $act=$surplusDefisitUnit->delete();
        }
    }

    public function delete (Request $request)
    {
        $data = SurplusDefisitUnit::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function hapus(Request $request)
    {
        DB::beginTransaction();

        try {

            $act = SurplusDefisitUnit::find($request->id)->delete();
            DB::commit();
            message($act, "Berhasil hapus data", "Gagal hapus data");
            return redirect('/surplus-defisit-unit');
        }
        catch (Exception $e){
            DB::rollback();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SurplusDefisitUnit::select('surplus_defisit_unit.id as id', 'surplus_defisit_detail.nama')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_unit.id_surplus_defisit_detail')
        ->groupBy('id_surplus_defisit_detail');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)
            ->addColumn('nomor',function($kategori){
                return $GLOBALS['nomor']++;
            })
            ->addColumn('action', function ($data) {
                $edit=url("surplus-defisit-unit/".$data->pk())."/edit";
                $delete=url("surplus-defisit-unit/".$data->pk());
                $content = '';
                $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
                data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
                data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";
                $content .= "<a href='surplus-defisit-unit/detail/".$data->pk()."' class='btn btn-outline-primary btn-sm'>Detail</a>";

            return $content;
        })->make(true);
    }
}
