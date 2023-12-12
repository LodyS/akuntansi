<?php
namespace App\Http\Controllers;
use App\Models\SurplusDefisitDetail;
use App\Models\SurplusDefisitRek;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SurplusDefisitRekController extends Controller
{
    public $viewDir = "surplus_defisit_rek";
    public $breadcrumbs = array('permissions'=>array('title'=>'Surplus-defisit-rek','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-surplus-defisit-rek');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $surplusDefisitDetail = SurplusDefisitDetail::all();
        $perkiraan = DB::table('perkiraan')->selectRaw('id,nama')->get();
        $surplusDefisitRek = new SurplusDefisitRek;
        return $this->view("form", compact('surplusDefisitRek', 'perkiraan', 'surplusDefisitDetail'));
    }

    public function tambah (Request $request)
    {
        $data = SurplusDefisitRek::select('nama', 'surplus_defisit_detail.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_rek.id_surplus_defisit_detail')
        ->where('id_surplus_defisit_detail', $request->id)
        ->firstOrFail();

        echo json_encode($data);
    }

    public function store( Request $request )
    {
        $cek = SurplusDefisitRek::select('id_surplus_defisit_detail', 'id_perkiraan')
        ->where('id_surplus_defisit_detail', $request->id_surplus_defisit_detail)
        ->where('id_perkiraan', $request->id_perkiraan)
        ->first();

        $id_surplus_defisit_detail = ($cek->id_surplus_defisit_detail == null) ? 0: $cek->id_surplus_defisit_detail;
        $id_perkiraan = ($cek->id_perkiraan == null) ? 0 : $cek->id_perkiraan;

        if ($id_surplus_defisit_detail !== $request->id_surplus_defisit_detail && $id_perkiraan !== $request->id_perkiraan){
            $this->validate($request, SurplusDefisitRek::validationRules());
            $act=SurplusDefisitRek::create($request->all());
        }

        if ($cek->id_surplus_defisit_detail == $request->id_surplus_defisit_detail && $cek->id_perkiraan == $request->id_perkiraan){
            message(false,'','Data Surplus Defisit Rek gagal ditambahkan karena sudah ada');
            return redirect('surplus-defisit-rek');
        } else {
            message($act,'Data Surplus Defisit Rek berhasil ditambahkan','Data Surplus Defisit Rek gagal ditambahkan');
            return redirect('surplus-defisit-rek');
        }
    }

    public function show(Request $request, $kode)
    {
        $surplusDefisitRek=SurplusDefisitRek::find($kode);
        return $this->view("show",['surplusDefisitRek' => $surplusDefisitRek]);
    }

    public function edit(Request $request, $kode)
    {
        $surplusDefisitDetail = SurplusDefisitDetail::all();
        $perkiraan = DB::table('perkiraan')->selectRaw('id,nama')->get();
        $surplusDefisitRek=SurplusDefisitRek::find($kode);
        return $this->view( "form", compact('surplusDefisitRek', 'surplusDefisitDetail', 'perkiraan') );
    }

    public function detail ($id)
    {
        $id = $id;
        $rek = SurplusDefisitRek::select('surplus_defisit_detail.nama', 'surplus_defisit_detail.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_rek.id_surplus_defisit_detail')
        ->where('id_surplus_defisit_detail', $id)
        ->firstOrFail();

        $data = SurplusDefisitRek::select('surplus_defisit_rek.id', 'perkiraan.nama', 'perkiraan.kode_rekening')
        ->leftJoin('perkiraan', 'perkiraan.id', 'surplus_defisit_rek.id_perkiraan')
        ->where('id_surplus_defisit_detail', $id)
        ->paginate(100);

        return $this->view('detail', compact('data', 'rek', 'id'));
    }

    public function cari (Request $request)
    {
        $id = $request->id;

        $rek = SurplusDefisitRek::select('surplus_defisit_detail.nama', 'surplus_defisit_detail.id')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_rek.id_surplus_defisit_detail')
        ->where('id_surplus_defisit_detail', $request->id)
        ->first();

        $data = SurplusDefisitRek::select('surplus_defisit_rek.id', 'perkiraan.nama', 'perkiraan.kode_rekening')
        ->leftJoin('perkiraan', 'perkiraan.id', 'surplus_defisit_rek.id_perkiraan')
        ->where('id_surplus_defisit_detail', $request->id)
        ->where('perkiraan.nama', 'like', '%'.$request->search.'%')
        ->Orwhere('perkiraan.kode_rekening', 'like', '%'.$request->search.'%')
        ->paginate(100);

        return $this->view('detail', compact('data', 'rek', 'id'));
    }

    public function update(Request $request, $kode)
    {
        $surplusDefisitRek=SurplusDefisitRek::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SurplusDefisitRek::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $surplusDefisitRek->update($data);
                return "Record updated";
            }
        $this->validate($request, SurplusDefisitRek::validationRules());

        $act=$surplusDefisitRek->update($request->all());
        message($act,'Data Surplus Defisit Rek berhasil diupdate','Data Surplus Defisit Rek gagal diupdate');

        return redirect('/surplus-defisit-rek');
    }

    public function destroy(Request $request, $kode)
    {
        $surplusDefisitRek=SurplusDefisitRek::find($kode);
        $act=false;
        try {
            $act=$surplusDefisitRek->forceDelete();
        } catch (\Exception $e) {
            $surplusDefisitRek=SurplusDefisitRek::find($surplusDefisitRek->pk());
            $act=$surplusDefisitRek->delete();
        }
    }

    public function delete (Request $request)
    {
        $data = SurplusDefisitRek::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function hapus(Request $request)
    {
        $act = SurplusDefisitRek::where('id', $request->id)->delete();
        message($act, "Berhasil hapus data", "Gagal hapus data");
        return redirect('/surplus-defisit-rek');
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SurplusDefisitRek::select('surplus_defisit_detail.id', 'surplus_defisit_detail.nama')
        ->leftJoin('surplus_defisit_detail', 'surplus_defisit_detail.id', 'surplus_defisit_rek.id_surplus_defisit_detail')
        ->groupBy('surplus_defisit_detail.nama');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
                return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

            $edit=url("surplus-defisit-rek/".$data->pk())."/edit";
            $delete=url("surplus-defisit-rek/".$data->id);
            $content = '';
            /*$content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>"; */
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-danger btn-sm'>Hapus</a>";
            $content .= "<a href='surplus-defisit-rek/detail/".$data->id."' class='btn btn-primary btn-sm'>Detail</a>";

            return $content;
        })->make(true);
    }
}
