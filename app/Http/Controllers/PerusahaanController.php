<?php
namespace App\Http\Controllers;
use DB;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Models\SubJenisUsaha;
use App\Models\JenisUsaha;
use App\Models\KelompokBisnis;
use App\Models\SubUnitUsaha;
use App\Models\Unit;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class PerusahaanController extends Controller
{
    public $viewDir = "perusahaan";
    public $breadcrumbs = array('permissions'=>array('title'=>'Perusahaan','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-perusahaan');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $subJenisUsaha = SubJenisUsaha::select('id', 'nama')->get();
        $jenisUsaha = JenisUsaha::select('id', 'nama')->get();
        $kelompokBisnis = KelompokBisnis::select('id', 'nama')->get();
        $unitUsaha = SubUnitUsaha::select('id', 'nama')->get();
        $unit = Unit::select('id', 'nama')->get();
        $perusahaan = new Perusahaan;

        return $this->view("form", compact('perusahaan', 'subJenisUsaha', 'jenisUsaha', 'kelompokBisnis', 'unitUsaha', 'unit'));
    }

    public function store( Request $request )
    {
        DB::beginTransaction();
        try {

            $this->validate($request, Perusahaan::validationRules());
            $act=Perusahaan::create($request->all());
            DB::commit();
            message($act,'Data Perusahaan berhasil ditambahkan','Data Perusahaan gagal ditambahkan');
        } catch (Exception $e){
            DB::rollback;
            return 'Error sistem';
        }
        return redirect('perusahaan');
    }

    public function show(Request $request, $kode)
    {
        $perusahaan=Perusahaan::find($kode);
        return $this->view("show",['perusahaan' => $perusahaan]);
    }

    public function edit(Request $request, $kode)
    {
        $subJenisUsaha = SubJenisUsaha::all(['id', 'nama']);
        $jenisUsaha = JenisUsaha::all(['id', 'nama']);
        $kelompokBisnis = KelompokBisnis::all(['id', 'nama']);
        $unitUsaha = SubUnitUsaha::all(['id', 'nama']);
        $unit = Unit::all(['id', 'nama']);
        $perusahaan=Perusahaan::find($kode);

        return $this->view( "form", compact('perusahaan', 'subJenisUsaha', 'jenisUsaha', 'kelompokBisnis', 'unitUsaha', 'unit'));
    }

    public function update(Request $request, $kode)
    {
        $perusahaan=Perusahaan::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Perusahaan::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $perusahaan->update($data);
                return "Record updated";
        }
        $this->validate($request, Perusahaan::validationRules());

        $act=$perusahaan->update($request->all());
        message($act,'Data Perusahaan berhasil diupdate','Data Perusahaan gagal diupdate');

        return redirect('/perusahaan');
    }

    public function destroy(Request $request, $kode)
    {
        $perusahaan=Perusahaan::find($kode);
        $act=false;
        try
        {
            $act=$perusahaan->forceDelete();
            } catch (\Exception $e) {
            $perusahaan=Perusahaan::find($perusahaan->pk());
            $act=$perusahaan->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $nama = request()->get('nama');
        $kode_unit_usaha = request()->get('kode_unit_usaha');
        $dataList = Perusahaan::select('perusahaan.id', 'nama_badan_usaha',  'kode_unit_usaha', 'sub_jenis_usaha.nama as unit_usaha',
        'alamat_perusahaan', 'kota', 'negara_perusahaan', 'kode_pos', 'telepon_perusahaan', 'fax_perusahaan', 'email_perusahaan', 'npwp',
        DB::raw("concat(kelompok_bisnis.kode, '-',  jenis_usaha.kode, '-',  sub_jenis_usaha.kode, '-',   unit.kode, '-',  sub_unit_usaha.kode) as kode "))
        ->leftJoin('kelompok_bisnis','kelompok_bisnis.id', 'perusahaan.id_kelompok_bisnis')
        ->leftJoin('jenis_usaha','jenis_usaha.id', 'perusahaan.id_jenis_usaha')
        ->leftJoin('sub_jenis_usaha','sub_jenis_usaha.id', 'perusahaan.id_sub_jenis_usaha')
        ->leftJoin('unit','unit.id', 'perusahaan.id_unit')
        ->leftJoin('sub_unit_usaha','sub_unit_usaha.id', 'perusahaan.id_sub_unit_usaha');

        if ($nama){
            $dataList->where('perusahaan.nama_badan_usaha', 'like', $nama.'%');
        }

        if ($kode_unit_usaha){
            $dataList->where('perusahaan.kode_unit_usaha', 'like', $kode_unit_usaha.'%');
        }

        if (request()->get('status') == 'trash')
        {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori)
        {
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
            $edit=url("perusahaan/".$data->pk())."/edit";
            $delete=url("perusahaan/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
