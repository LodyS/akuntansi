<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class UnitController extends Controller
{
    public $viewDir = "unit";
    public $breadcrumbs = array('permissions'=>array('title'=>'Unit','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-unit');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['unit' => new Unit]);
    }

    public function cekKode ($kode)
    {
        $data = Unit::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {  
        DB::beginTransaction();

        try {

            $cek = Unit::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $request->kode)->first();

            if ($cek->status == 'Tidak Ada')
            {
                $this->validate($request, Unit::validationRules());
                $act=Unit::create($request->all());
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }

        if ($cek->status == 'Ada')
        {
            return redirect('unit')->with('error', 'Gagal simpan karena kode departemen sudah ada');
        } else {
            return redirect('unit')->with('success', 'Data Departemen berhasil disimpan');
        }
    }

    public function show(Request $request, $kode)
    {
        $unit=Unit::find($kode);
        return $this->view("show",['unit' => $unit]);
    }

    public function edit(Request $request, $kode)
    {
        $unit=Unit::find($kode);
        return $this->view( "form", ['unit' => $unit] );
    }

    public function activate(Request $request, $kode)
    {
        $unit= Unit::find($kode);
        $data=array('flag_aktif'=>'Y',);

        $status=$unit->update($data);
        message($status,'Unit Berhasil Diaktifkan Kembali','Unit Gagal Diaktifkan Kembali');
        return redirect('unit');
    }

    public function deactivate(Request $request, $kode)
    {
        $unit=Unit::find($kode);
        $data=array('flag_aktif'=>'N',);

        $status=$unit->update($data);
        message($status,'Unit Berhasil Dinonaktifkan','Unit Gagal Dinonaktifkan');

        return redirect('unit');
    }

    public function update(Request $request, $kode)
    {
        $unit=Unit::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Unit::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $unit->update($data);
                return "Record updated";
        }
        $this->validate($request, Unit::validationRules());
        $act=$unit->update($request->all());
        message($act,'Data Departemen berhasil diupdate','Data Departemen gagal diupdate');

        return redirect('/unit');
    }
 
    public function destroy(Request $request, $kode)
    {
        $unit=Unit::find($kode);
        $act=false;
        try {
            $act=$unit->forceDelete();
        } catch (\Exception $e) {
            $unit=Unit::find($unit->pk());
            $act=$unit->delete();
        }
    }
		
    public function hapus (Request $request)
    {
	    return $this->view ("hapus");
	}

	public function editStatus (Request $request, $kode)
    {
		$status = Unit::where('id', '=', $kode)->update(['flag_aktif ' => 'N']);
	}

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $kode = request()->get('code_cost_centre');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Unit::select('unit.nama', 'unit.profit as profit', 
        'unit.code_cost_centre', 'dua.nama as induk_cost_centre', 'unit.level', 'unit.urutan')
        ->leftJoin('unit as dua', 'dua.id', 'unit.induk_cost_centre')
        ->where('unit.code_cost_centre', '<>', null);

        if ($nama){
            $dataList->where('unit.nama', 'like', $nama.'%');
        }

        if ($kode){
            $dataList->where('unit.code_cost_centre', 'like', $kode.'%');
        }
        
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('kode', function ($data) {
            
            if (isset($data->kode)){
                return $data->kode;
            } else {
                return '-';
            }
        })->addColumn('nama', function ($data) {
                
            if(isset($data->nama)) {
                return $data->nama;
            } else {
                return '-';
            }
        })->addColumn('keterangan', function ($data) {
                
            if(isset($data->keterangan)) {
                return $data->keterangan;
            } else {
                return '-';
            }
        })->addColumn('status', function ($data) {
                   
            if(isset($data->flag_aktif)) {
                return array('id'=>$data->pk(),'flag_aktif'=>$data->flag_aktif);
            } else {
                return null;
            }
        })->addColumn('action', function ($data) {
            $edit=url("unit/".$data->pk())."/edit";
            $delete=url("unit/".$data->pk());
            $content = '';
            //$content .= "<a onclick='show_modal(\"$edit\")'  ><span class='btn btn-sm btn-warning'
            //style='color:white;font-size:12px'>Edit</span></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
