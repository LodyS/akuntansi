<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Nakes;
use App\Models\Spesialisasi;
use Illuminate\Http\Request;
use Datatables;

class NakesController extends Controller
{
    public $viewDir = "nakes";
    public $breadcrumbs = array('permissions' => array('title' => 'Nakes', 'link' => "#", 'active' => false, 'display' => true),);

    public function __construct()
    {
        $this->middleware('permission:read-nakes');
    }

    public function index()
    {
        return $this->view("index");
    }

    public function create()
    {
        $lastNakes = Nakes::select('kode')->orderByDesc('id')->first();
        $spesialisasi = Spesialisasi::all();

        if ($lastNakes) {
            $code = "N-" . str_pad(intval(substr($lastNakes->kode, 2)) + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $code = "N-00001";
        }

        return $this->view("form", ['nakes' => new Nakes, 'code' => $code, 'spesialisasi' => $spesialisasi]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, Nakes::validationRules());

            $act = Nakes::create($request->all());
            DB::commit();
            message($act, 'Data Nakes berhasil ditambahkan', 'Data Nakes gagal ditambahkan');
            return redirect('nakes');
        } catch (Exception $e){
            DB::rollback();
            message(false, 'Data Nakes berhasil ditambahkan', 'Data Nakes gagal ditambahkan');
            return redirect('nakes');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $kode)
    {
        $nakes = Nakes::find($kode);
        $spesialisasi = Spesialisasi::all();
        $code = "N-" . str_pad(intval(substr($nakes->kode, 2)), 5, '0', STR_PAD_LEFT);
        $spesialisasi = Spesialisasi::all();
        return $this->view("form", ['nakes' => $nakes, 'spesialisasi' => $spesialisasi, 'code' => $code]);
    }

    public function update(Request $request, $kode)
    {
        $nakes = Nakes::find($kode);
        if ($request->isXmlHttpRequest()) {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make($data, Nakes::validationRules($request->name));
            if ($validator->fails())
                return response($validator->errors()->first($request->name), 403);
            $nakes->update($data);
            return "Record updated";
        }
        $this->validate($request, Nakes::validationRules());

        $act = $nakes->update($request->all());
        message($act, 'Data Bakes berhasil diupdate', 'Data Bakes gagal diupdate');

        return redirect('/nakes');
    }

    public function destroy(Request $request, $kode)
    {
        $nakes = Nakes::find($kode);
        $act = false;
        try {
            $act = $nakes->forceDelete();
        } catch (\Exception $e) {
            $nakes = Nakes::find($nakes->pk());
            $act = $nakes->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $kode = request()->get('kode');
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = Nakes::select('nakes.id', 'nakes.kode', 'nakes.nama', 'spesialisasi.nama as spesialisasi')
        ->leftJoin('spesialisasi', 'spesialisasi.id', 'nakes.id_spesialisasi');

        if ($nama) {
            $dataList->where('nakes.nama', 'like', $nama.'%');
        }

        if ($kode) {
            $dataList->where('nakes.kode', 'like', $kode.'%');
        }

        return Datatables::of($dataList)->addColumn('nomor', function ($kategori) {
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
        
            $edit = url("nakes/" . $data->pk()) . "/edit";
            $delete = url("nakes/" . $data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
