<?php
namespace App\Http\Controllers;

use App\Models\SetLapEkuita;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SetLapEkuitaController extends Controller
{
    public $viewDir = "set_lap_ekuita";
    public $breadcrumbs = array('permissions'=>array('title'=>'Set-lap-ekuitas','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-set-lap-ekuitas');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $generate = SetLapEkuita::selectRaw('max(urutan)+1 as urutan, max(kode)+1 as kode')->where('level', '0')->first();
        $kode_induk = (isset($generate->kode)) ? $generate->kode : '1';
        $urutan = (isset($generate->urutan)) ? $generate->urutan :'1';
        $aksi = "create";
        $induk = SetLapEkuita::select('id', 'nama')->get();
        $setLapEkuita = new SetLapEkuita;
        return $this->view("form", compact('setLapEkuita', 'induk', 'aksi', 'urutan', 'kode_induk'));
    }

    public function isi($induk)
    {
        $data = SetLapEkuita::select('kode', 'level')->where('id', $induk)->first();
        $jumlah = SetLapEkuita::selectRaw('id, urutan, level')->where('induk', $induk)->orderByDesc('id')->first();
        $master_level = SetLapEkuita::selectRaw('level')->where('id', $induk)->first();
        $master_urutan = SetLapEkuita::selectRaw('max(urutan) as urutan')->where('induk', $induk)->first();
        $max_kode = SetLapEkuita::selectRaw("CONCAT((SELECT kode FROM set_lap_ekuitas WHERE id='$induk' ),'.', '',
        MAX(SUBSTRING_INDEX(kode, '.',-1)+1)) AS kode")
        ->where('induk', $induk)
        ->first();

        $kode = (isset($jumlah->id)) ? $max_kode->kode : $data->kode.'.'.'1' ;
        $level = isset($master_level) ? $master_level->level +1 : '1';
        $urutan = (isset($data)) ? $master_urutan->urutan + 1 : '1';

        return response()->json(['level'=>$level, 'urutan'=>$urutan, 'kode'=>$kode]);
    }

    public function store( Request $request )
    {
        $this->validate($request, SetLapEkuita::validationRules());

        $act=SetLapEkuita::create($request->except('tipe'));
        message($act,'Data Set Lap Ekuitas berhasil ditambahkan','Data Set Lap Ekuitas gagal ditambahkan');
        return redirect('set-lap-ekuita');
    }

    public function show(Request $request, $kode)
    {
        $setLapEkuita=SetLapEkuita::find($kode);
        return $this->view("show",['setLapEkuita' => $setLapEkuita]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi = "update";
        $induk = SetLapEkuita::select('id', 'nama')->get();
        $setLapEkuita=SetLapEkuita::find($kode);
        $generate = SetLapEkuita::selectRaw('urutan')->where('id', $kode)->first();

        $kode_induk = (isset($generate->kode)) ? $generate->kode + 1 : '1';
        $urutan = (isset($generate->urutan)) ? $generate->urutan +1 :'1';
        return $this->view( "form", compact('setLapEkuita', 'induk', 'aksi','kode_induk', 'urutan'));
    }

    public function update(Request $request, $kode)
    {
        $setLapEkuita=SetLapEkuita::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SetLapEkuita::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $setLapEkuita->update($data);
                return "Record updated";
        }
        $this->validate($request, SetLapEkuita::validationRules());

        $act=$setLapEkuita->update($request->except('jenis_data', 'tipe'));
        message($act,'Data Set Lap Ekuitas berhasil diupdate','Data Set Lap Ekuitas gagal diupdate');

        return redirect('/set-lap-ekuita');
    }

    public function destroy(Request $request, $kode)
    {
        $setLapEkuita=SetLapEkuita::find($kode);
        $act=false;
        try {
            $act=$setLapEkuita->forceDelete();
        } catch (\Exception $e) {
            $setLapEkuita=SetLapEkuita::find($setLapEkuita->pk());
            $act=$setLapEkuita->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SetLapEkuita::selectRaw('set_lap_ekuitas.id, set_lap_ekuitas.nama as nama, set_lap_ekuitas.kode as kode, dua.nama as induk,
        set_lap_ekuitas.jenis_data as jenis_data, set_lap_ekuitas.level as level, case when set_lap_ekuitas.jenis = "-1" then "Pengurang"
        when set_lap_ekuitas.jenis= "1" then "Penambah" else ""end as jenis')
        ->leftJoin('set_lap_ekuitas as dua', 'dua.id', 'set_lap_ekuitas.induk');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn ('jenis_data', function($data){

            if(isset($data->jenis_data)){
                return array('id'=>$data->pk(), 'jenis_data'=>$data->jenis_data);

            } else {
                return 0;
            }
        })

        ->addColumn('action', function ($data) {

            $edit=url("set-lap-ekuita/".$data->pk())."/edit";
            $delete=url("set-lap-ekuita/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
