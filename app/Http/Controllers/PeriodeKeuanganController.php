<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\PeriodeKeuangan;
use Illuminate\Http\Request;
use DB;
use App\Models\SetupAwalPeriode;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class PeriodeKeuanganController extends Controller
{
    public $viewDir = "periode_keuangan";
    public $breadcrumbs = array('permissions' => array('title' => 'Periode-keuangan', 'link' => "#", 'active' => false, 'display' => true),);

    public function __construct()
    {
        $this->middleware('permission:read-periode-keuangan');
    }

    public function index()
    {
        $setup = SetupAwalPeriode::select('*')->orderByDesc('id')->first();

        return $this->view("index")->with('setup', $setup);
    }

    public function create()
    {
        $periode_terakhir = PeriodeKeuangan::where('status_aktif', 'Y')->orderByDesc('tanggal_akhir')->first();
        if (isset($periode_terakhir->tanggal_akhir)) {
            $carbon = new Carbon($periode_terakhir->tanggal_akhir);
            $carbon->addMonthsNoOverflow(1);
        } else {
            $carbon = Carbon::now();
        }

        return $this->view("form", ['periodeKeuangan' => new PeriodeKeuangan, 'carbon' => $carbon]);
    }

    public function store(Request $request)
    {
        $this->validate($request, PeriodeKeuangan::validationRules());

        if ($request->tanggal_awal > $request->tanggal_akhir) 
        {
            message(false, '', 'Tanggal akhir periode lebih awal daripada tanggal awal periode');
            return redirect('/periode-keuangan'); // jika tanggal akhir lebih awal daripada tanggal awal
        }

        $periode = PeriodeKeuangan::where('status_aktif', 'Y')->orderByDesc('tanggal_akhir')->first();

        if ($periode == null) {
            $act = PeriodeKeuangan::create($request->all()); //Jika data kosong bisa langsung input tanpa kondisi tertentu
            message($act, 'Data Periode Keuangan berhasil ditambahkan', 'Data Periode Keuangan gagal ditambahkan');
            return redirect('/periode-keuangan');
        }

        if ($request->tanggal_awal > Carbon::now()->lastOfMonth()->toDateString()) {
            message(false, '', 'Data Periode Keuangan gagal ditambahkan. Anda dapat menambahkan di bulan depan.');
            return redirect('/periode-keuangan'); // maksimal boleh menambahkan periode yang sama dengan bulan sekarang
            
        } else {

            $act = PeriodeKeuangan::create($request->all());
            $id = $act->id; 
            $update = PeriodeKeuangan::where('id', '<>', $id)->update(['status_aktif' => 'N']);
            // untuk update otomatis periode akuntansi menjadi tidak aktif setelah input data periode akuntansi yang baru

            message($act, 'Data Periode Keuangan berhasil ditambahkan', 'Data Periode Keuangan gagal ditambahkan');
            return redirect('/periode-keuangan');
        }
    }

    public function cari (Request $request)
    {
        $cari = DB::table('periode_keuangan')
        ->selectRaw('tanggal_awal, tanggal_akhir, status_aktif, month(tanggal_awal) as bulan,
        case when month(tanggal_awal) =1 then year(tanggal_awal) else "" end as tahun')
        ->whereYear('tanggal_awal', $request->tahun)
        ->whereYear('tanggal_akhir', $request->tahun)
        ->paginate(25);

        return $this->view('pencarian-tahun', compact('cari'));
    }

    public function show(Request $request, $kode)
    {
        $periodeKeuangan = PeriodeKeuangan::find($kode);
        return $this->view("show", ['periodeKeuangan' => $periodeKeuangan]);
    }

    public function edit(Request $request, $kode)
    {
        $periodeKeuangan = PeriodeKeuangan::find($kode);

        return $this->view("form", ['periodeKeuangan' => $periodeKeuangan]);
    }

    public function update(Request $request, $kode)
    {
        $periodeKeuangan = PeriodeKeuangan::find($kode);
        if ($request->isXmlHttpRequest()) {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make($data, PeriodeKeuangan::validationRules($request->name));
            if ($validator->fails())
                return response($validator->errors()->first($request->name), 403);
            $periodeKeuangan->update($data);
            return "Record updated";
        }
        $this->validate($request, PeriodeKeuangan::validationRules());

        $act = $periodeKeuangan->update($request->all());
        message($act, 'Data Periode Keuangan berhasil diupdate', 'Data Periode Keuangan gagal diupdate');

        return redirect('/periode-keuangan');
    }

    public function destroy(Request $request, $kode)
    {
        $periodeKeuangan = PeriodeKeuangan::find($kode);
        $act = false;

        try {
            $act = $periodeKeuangan->forceDelete();
        } catch (\Exception $e) {
            $periodeKeuangan = PeriodeKeuangan::find($periodeKeuangan->pk());
            $act = $periodeKeuangan->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = PeriodeKeuangan::selectRaw('id, tanggal_awal, tanggal_akhir, status_aktif, month(tanggal_awal) as bulan,
        case when month(tanggal_awal) =1 then year(tanggal_awal) else "" end as tahun');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor', function ($kategori) {
            return $GLOBALS['nomor']++;
        
        })->addColumn('status_aktif', function ($data) {

        if (isset($data->status_aktif)) {
            return array('id' => $data->pk(), 'status_aktif' => $data->status_aktif);
        
        } else {
            return 0;
            }
        })->addColumn('tanggal_awal', function ($data) {

        if (isset($data->tanggal_awal)) {

            return date('d-M-Y', strtotime($data->tanggal_awal));
        } else {
            return 0;
        }
        })->addColumn('tanggal_akhir', function ($data) {

        if (isset($data->tanggal_akhir)) {
            return date('d-M-Y', strtotime($data->tanggal_akhir));
        }
        })->addColumn('action', function ($data) {

        // $edit = url("periode-keuangan/" . $data->pk()) . "/edit";
        $delete = url("periode-keuangan/" . $data->pk());
        $content = '';
        // $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
        //data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}