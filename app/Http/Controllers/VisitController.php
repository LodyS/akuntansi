<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class VisitController extends Controller
{
    public $viewDir = "visit";
    public $breadcrumbs = array('permissions' => array('title' => 'Visit', 'link' => "#", 'active' => false, 'display' => true),);

    public function __construct()
    {
        $this->middleware('permission:read-visit');
    }

    public function index()
    {
        return $this->view("index");
    }

    public function create()
    {
        return $this->view("form", ['visit' => new Visit, 'pelanggan' => new Pelanggan]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, Visit::validationRules());

            $act = Visit::create($request->all());
            DB::commit();
            message($act, 'Data Visit berhasil ditambahkan', 'Data Visit gagal ditambahkan');
            return redirect('visit');
        } catch (Exception $e){
            DB::rollback();
            message(false, 'Data Visit berhasil ditambahkan', 'Data Visit gagal ditambahkan');
            return redirect('visit');
        }
    }

    public function show(Request $request, $kode)
    {
        $visit = Visit::find($kode);
        return $this->view("show", ['visit' => $visit]);
    }

    public function edit(Request $request, $kode)
    {
        $visit = Visit::find($kode);
        $pelanggan = Pelanggan::find($visit->id_pelanggan);
        return $this->view("form", ['visit' => $visit, 'pelanggan' => $pelanggan]);
    }

    public function update(Request $request, $kode)
    {
        $visit = Visit::find($kode);
        if ($request->isXmlHttpRequest()) {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make($data, Visit::validationRules($request->name));
            if ($validator->fails())
                return response($validator->errors()->first($request->name), 403);
            $visit->update($data);
            return "Record updated";
        }
        $this->validate($request, Visit::validationRules());

        $act = $visit->update($request->all());
        message($act, 'Data Visit berhasil diupdate', 'Data Visit gagal diupdate');

        return redirect('/visit');
    }

    public function destroy(Request $request, $kode)
    {
        $visit = Visit::find($kode);
        $act = false;
        try {
            $act = $visit->forceDelete();
        } catch (\Exception $e) {
            $visit = Visit::find($visit->pk());
            $act = $visit->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }
    public function loadData()
    {
        $no_kunjungan = request()->get('no_kunjungan');
        $tanggal = request()->get('tanggal');
        $nama = request()->get('nama');

        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = Visit::select('*')->orderBydesc('waktu');

        if ($no_kunjungan) {
            $dataList->where('visit.id', $no_kunjungan);
        }

        if ($tanggal) {
            $dataList->where('visit.waktu', $tanggal);
        }
        
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)
            ->addColumn('nomor', function ($kategori) {
                return $GLOBALS['nomor']++;
            })
            ->addColumn('pelanggan',function($data){
                return isset($data->pelanggan->nama) ? $data->pelanggan->nama : null;
           })
            ->addColumn('kode',function($data){
                return isset($data->pelanggan->kode) ? $data->pelanggan->kode : null;
           })
            ->addColumn('action', function ($data) {
                $edit = url("visit/" . $data->pk()) . "/edit";
                $delete = url("visit/" . $data->pk());
                $content = '';
                $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
                data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
                data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
