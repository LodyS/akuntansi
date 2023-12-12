<?php
namespace App\Http\Controllers;

use App\Models\SettingSurplusDefisit;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SettingSurplusDefisitController extends Controller
{
    public $viewDir = "setting_surplus_defisit";
    public $breadcrumbs = array('permissions'=>array('title'=>'Setting-surplus-defisit','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-setting-surplus-defisit');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $aksi = "create";
        $induk = SettingSurplusDefisit::select('id', 'nama')->get();
        $settingSurplusDefisit = new SettingSurplusDefisit;
        $kodes = SettingSurplusDefisit::select('kode')->whereNull('induk')->orderByDesc('id')->first();
        $kodeS = ($kodes) ? $kodes->kode+1 : 1;

        return $this->view("form", compact('settingSurplusDefisit', 'induk', 'aksi', 'kodeS'));
    }

    public function isi($induk)
    {
        $data = SettingSurplusDefisit::select('kode', 'level')->where('id', $induk)->first();
        $jumlah = SettingSurplusDefisit::selectRaw('id, urutan, level')->where('induk', $induk)->orderByDesc('id')->first();
        $master_level = SettingSurplusDefisit::selectRaw('level')->where('id', $induk)->first();
        $master_urutan = SettingSurplusDefisit::selectRaw('max(urutan) as urutan')->where('induk', $induk)->first();
        $max_kode = SettingSurplusDefisit::selectRaw("CONCAT((SELECT kode FROM setting_surplus_defisit WHERE id='$induk' ),'.', '',
        MAX(SUBSTRING_INDEX(kode, '.',-1))+1) AS kode")
        ->where('induk', $induk)
        ->first();

        $kode = (isset($jumlah->id)) ? $max_kode->kode : $data->kode.'.'.'1' ;
        $level = isset($master_level) ? $master_level->level +1 : '1';
        $urutan = (isset($jumlah->id)) ? $master_urutan->urutan + 1 : '1';

        return response()->json(['level'=>$level, 'urutan'=>$urutan, 'kode'=>$kode]);
    }

    public function store (Request $request)
    {
        $this->validate($request, SettingSurplusDefisit::validationRules());

        $act=SettingSurplusDefisit::create($request->except('tipe'));
        message($act,'Data Setting Surplus Defisit berhasil ditambahkan','Data Setting Surplus Defisit gagal ditambahkan');
        return redirect('setting-surplus-defisit');
    }

    public function show(Request $request, $kode)
    {
        $settingSurplusDefisit=SettingSurplusDefisit::find($kode);
        return $this->view("show",['settingSurplusDefisit' => $settingSurplusDefisit]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi = "update";
        $induk = SettingSurplusDefisit::select('id', 'nama')->get();
        $settingSurplusDefisit=SettingSurplusDefisit::find($kode);
        $kodes = SettingSurplusDefisit::select('kode')->whereNull('induk')->orderByDesc('id')->first();
        $kodeS = ($kodes) ? $kodes->kode+1 : 1;

        return $this->view("form", compact('settingSurplusDefisit', 'induk', 'aksi', 'kodeS'));
    }

    public function update(Request $request, $kode)
    {
        $settingSurplusDefisit=SettingSurplusDefisit::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SettingSurplusDefisit::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $settingSurplusDefisit->update($data);
                return "Record updated";
        }
        $this->validate($request, SettingSurplusDefisit::validationRules());
        $act=$settingSurplusDefisit->update($request->except('urutan', 'tipe'));
        message($act,'Data Setting Surplus Defisit berhasil diupdate','Data Setting Surplus Defisit gagal diupdate');

        return redirect('/setting-surplus-defisit');
    }

    public function destroy(Request $request, $kode)
    {
        $settingSurplusDefisit=SettingSurplusDefisit::find($kode);
        $act=false;
        try {
            $act=$settingSurplusDefisit->forceDelete();
        } catch (\Exception $e) {
            $settingSurplusDefisit=SettingSurplusDefisit::find($settingSurplusDefisit->pk());
            $act=$settingSurplusDefisit->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SettingSurplusDefisit::selectRaw('setting_surplus_defisit.id, setting_surplus_defisit.kode,
        setting_surplus_defisit.nama, dua.nama as induk,
        setting_surplus_defisit.level, case when setting_surplus_defisit.jenis = "-1" then "Pengurang"
        when setting_surplus_defisit.jenis= "1" then "Penambah" else ""end as jenis')
        ->leftJoin('setting_surplus_defisit as dua', 'dua.id', 'setting_surplus_defisit.induk')
        ->orderBy('setting_surplus_defisit.kode', 'asc');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            $edit=url("setting-surplus-defisit/".$data->pk())."/edit";
            $delete=url("setting-surplus-defisit/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
