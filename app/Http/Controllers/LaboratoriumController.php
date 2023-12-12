<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Layanan;
use App\Models\MasterDataPemeriksaanLab;
use Illuminate\Http\Request;
use Datatables;

class LaboratoriumController extends Controller
{
    public $viewDir = "laboratorium";
    public $breadcrumbs = array('permissions' => array('title' => 'Laboratotium', 'link' => "#", 'active' => false, 'display' => true),);

    public function __construct()
    {
        $this->middleware('permission:read-laboratorium');
    }
  
    public function index()
    {
        return $this->view("index");
    }

    public function create()
    {
        $layanan = Layanan::select('id', 'nama')->get();

        return $this->view("form", ['laboratorium' => new MasterDataPemeriksaanLab(), 'layanan' => $layanan]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, MasterDataPemeriksaanLab::validationRules());

            $act = MasterDataPemeriksaanLab::create($request->all());
            DB::commit();
            message($act, 'Data Laboratorium berhasil ditambahkan', 'Data Laboratorium gagal ditambahkan');
            return redirect('laboratorium');
        } catch (Exception $e){
            DB::rollback();
            message(false, 'Data Laboratorium berhasil ditambahkan', 'Data Laboratorium gagal ditambahkan');
            return redirect('laboratorium');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $kode)
    {
        $laboratorium = MasterDataPemeriksaanLab::find($kode);
        $act = false;
        try {
            $act = $laboratorium->forceDelete();
        } catch (\Exception $e) {
            $laboratorium = MasterDataPemeriksaanLab::find($laboratorium->pk());
            $act = $laboratorium->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = MasterDataPemeriksaanLab::select('master_data_pemeriksaan_lab.id', 'layanan.nama as layanan')
        ->leftJoin('layanan', 'layanan.id', 'master_data_pemeriksaan_lab.id_layanan');

        if ($nama) {
            $dataList->where('layanan.nama', 'like', $nama.'%');
        }

        return Datatables::of($dataList)->addColumn('nomor', function ($kategori) {
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            $delete = url("laboratorium/" . $data->pk());
            $content = '';
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
