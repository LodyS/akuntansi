<?php

namespace App\Http\Controllers;
use DB;
use App\Models\GolonganRadiologi;
use App\Models\JenisRadiologi;
use App\Models\Layanan;
use App\Models\PemeriksaanRadiologi;
use Illuminate\Http\Request;
use Datatables;

class RadiologiController extends Controller
{
    public $viewDir = "radiologi";
    public $breadcrumbs = array(
        'permissions' => array('title' => 'Radiologi', 'link' => "#", 'active' => false, 'display' => true),
    );

    public function __construct()
    {
        $this->middleware('permission:read-radiologi');
    }
 
    public function index()
    {
        $golongan_radiologi = GolonganRadiologi::all();
        $jenis_radiologi = JenisRadiologi::all();
        $layanan = Layanan::all();

        return $this->view("index", compact('golongan_radiologi', 'jenis_radiologi', 'layanan'));
    }

    public function jenis_radiologi()
    {
        return $this->view("jenis_radiologi");
    }

    public function golongan_radiologi()
    {
        return $this->view("golongan_radiologi");
    }

    public function create()
    {
        $golongan_radiologi = GolonganRadiologi::all();
        $jenis_radiologi = JenisRadiologi::all();
        $layanan = Layanan::all();
        $radiologi = new PemeriksaanRadiologi;

        return $this->view("form", compact('radiologi', 'golongan_radiologi', 'jenis_radiologi', 'layanan'));
    }

    public function createGolonganRadiologi()
    {
        return $this->view("form-golongan", ['golongan_radiologi' => new GolonganRadiologi()]);
    }

    public function createJenisRadiologi()
    {
        return $this->view("form-jenis", ['jenis_radiologi' => new JenisRadiologi()]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, PemeriksaanRadiologi::validationRules());

            $act = PemeriksaanRadiologi::create($request->all());
            DB::commit();
            message($act, 'Data Radiologi berhasil ditambahkan', 'Data Radiologi gagal ditambahkan');
            return redirect('radiologi');
        } catch (Exception $e){
            DB::rollback();
            message(false, 'Data Radiologi berhasil ditambahkan', 'Data Radiologi gagal ditambahkan');
            return redirect('radiologi');
        }
    }

    public function storeGolonganRadiologi(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, GolonganRadiologi::validationRules());

            $act = GolonganRadiologi::create($request->all());
            DB::commit();
            message($act, 'Data Golongan Radiologi berhasil ditambahkan', 'Data Golongan Radiologi gagal ditambahkan');
            return redirect('radiologi/golongan_radiologi');
        } catch (Exception $e){
            DB::rollback();
            message(false, 'Data Golongan Radiologi berhasil ditambahkan', 'Data Golongan Radiologi gagal ditambahkan');
            return redirect('radiologi/golongan_radiologi');
        }
    }

    public function storeJenisRadiologi(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, JenisRadiologi::validationRules());

            $act = JenisRadiologi::create($request->all());
            DB::commit();
            message($act, 'Data Jenis Radiologi berhasil ditambahkan', 'Data Jenis Radiologi gagal ditambahkan');
            return redirect('radiologi/jenis_radiologi');
        } catch (Exception $e){
            DB::rollback();
            message($false, 'Data Jenis Radiologi berhasil ditambahkan', 'Data Jenis Radiologi gagal ditambahkan');
            return redirect('radiologi/jenis_radiologi');
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
        $pemeriksaan_radiologi = PemeriksaanRadiologi::find($kode);
        $act = false;
        try {
            $act = $pemeriksaan_radiologi->forceDelete();
        } catch (\Exception $e) {
            $pemeriksaan_radiologi = PemeriksaanRadiologi::find($pemeriksaan_radiologi->pk());
            $act = $pemeriksaan_radiologi->delete();
        }
    }

    public function destroyGolonganRadiologi(Request $request, $kode)
    {
        $golongan_radiologi = GolonganRadiologi::find($kode);
        $act = false;
        try {
            $act = $golongan_radiologi->forceDelete();
        } catch (\Exception $e) {
            $golongan_radiologi = GolonganRadiologi::find($golongan_radiologi->pk());
            $act = $golongan_radiologi->delete();
        }
    }

    public function destroyJenisRadiologi(Request $request, $kode)
    {
        $jenis_radiologi = JenisRadiologi::find($kode);
        $act = false;
        try {
            $act = $jenis_radiologi->forceDelete();
        } catch (\Exception $e) {
            $jenis_radiologi = JenisRadiologi::find($jenis_radiologi->pk());
            $act = $jenis_radiologi->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }

    public function loadData()
    {
        $layanan = request()->get('layanan');
        $jenis = request()->get('jenis');
        $golongan = request()->get('golongan');

        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = PemeriksaanRadiologi::select('pemeriksaan_radiologi.id', 'layanan.nama as layanan', 'jenis_radiologi.nama as jenis',
        'golongan_radiologi.nama as golongan')
        ->leftJoin('layanan', 'layanan.id', 'pemeriksaan_radiologi.id_layanan')
        ->leftJoin('jenis_radiologi', 'jenis_radiologi.id', 'pemeriksaan_radiologi.id_jenis_radiologi')
        ->leftJoin('golongan_radiologi', 'golongan_radiologi.id', 'pemeriksaan_radiologi.id_golongan_radiologi');

        if ($layanan)
        {
            $dataList->where('id_layanan', $layanan);
        }

        if ($jenis)
        {
            $dataList->where('id_jenis_radiologi', $jenis);
        }

        if ($golongan)
        {
            $dataList->where('id_golongan_radiologi', $golongan);
        }

        return Datatables::of($dataList)->addColumn('nomor', function () {
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
        
            $delete = url("radiologi/" . $data->pk());
            $content = '';
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }

    public function loadDataGolonganRadiologi()
    {
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = GolonganRadiologi::select('*');

        return Datatables::of($dataList)->addColumn('nomor', function () {
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
                
            $delete = url("radiologi/golongan_radiologi/" . $data->pk());
            $content = '';
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }

    public function loadDataJenisRadiologi()
    {
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = JenisRadiologi::select('*');

        return Datatables::of($dataList)->addColumn('nomor', function () {
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
                
            $delete = url("radiologi/jenis_radiologi/" . $data->pk());    
            $content = '';
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
