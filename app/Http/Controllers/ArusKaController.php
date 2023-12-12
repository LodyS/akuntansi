<?php
namespace App\Http\Controllers;

use App\Models\ArusKa;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\JenisTransaksi;
use App\TransaksiJurnal;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingCashFlowRequest;
use Datatables;

class ArusKaController extends Controller
{
    public $viewDir = "arus_ka";
    public $breadcrumbs = array('permissions'=>array('title'=>'Arus-kas','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-arus-kas');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $aksi = 'create';
        $transaksi = DB::table('transaksi_jurnal')
        ->whereNotIn('id', DB::table('jenis_transaksi')->select('id_transaksi_jurnal'))
        ->get(['id', 'nama']);

        $induk = DB::table('jenis_transaksi')
        ->join('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->get(['jenis_transaksi.id', 'transaksi_jurnal.nama']);

        $kodes = JenisTransaksi::select('kode')->whereNull('id_induk')->orderByDesc('id')->first();
        $kodeS = ($kodes) ? $kodes->kode+1 : 1;
        $jenisTransaksi = new JenisTransaksi;

        return $this->view("form",compact('jenisTransaksi', 'kodeS',  'induk', 'transaksi', 'aksi'));
    }

    public function isi($induk)
    {
        $data = JenisTransaksi::select('kode', 'level')->where('id', $induk)->first();
        $jumlah = JenisTransaksi::selectRaw('id, urutan, level')->where('id_induk', $induk)->orderByDesc('id')->first();
        $master_level = JenisTransaksi::selectRaw('level')->where('id', $induk)->first();
        $master_urutan = JenisTransaksi::selectRaw('max(urutan) as urutan')->where('id_induk', $induk)->first();
        $max_kode = JenisTransaksi::selectRaw("CONCAT((SELECT kode FROM jenis_transaksi WHERE id='$induk' ),'.', '',
        MAX(SUBSTRING_INDEX(kode, '.',-1))+1) AS kode") // untuk generate kode jenis_transaksi yang kodenya sudah dalam anakan 1.1.1 untuk
        ->where('id_induk', $induk) // nambah nomor urut
        ->first();

        $kode = (isset($jumlah->id)) ? $max_kode->kode : $data->kode.'.'.'1' ;
        $level = isset($master_level) ? $master_level->level +1 : '1';
        $urutan = (isset($jumlah->id)) ? $master_urutan->urutan + 1 : '1';

        return response()->json(['level'=>$level, 'urutan'=>$urutan, 'kode'=>$kode]);
    }

    public function store (SettingCashFlowRequest $request)
    {
     //   $this->validate($request, ArusKa::validationRules());

        DB::beginTransaction();

        try {

            $act=JenisTransaksi::create($request->all());
            DB::commit();
            message($act,'Data Arus Kas berhasil ditambahkan','Data Arus Kas gagal ditambahkan');
            return redirect('arus-ka');

        } catch (\Illuminate\Database\QueryException $e){

            DB::rollback();
            message(false, 'Data Arus Kas gagal disimpan', 'Data Arus Kas Gagal disimpan');
            return back()->withError('Invalid data');
        }
    }

    public function show(Request $request, $kode)
    {
        $arusKa=ArusKa::find($kode);
        return $this->view("show",['arusKa' => $arusKa]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi = "update";
        $jenisTransaksi = JenisTransaksi::findOrFail($kode);

        $induk = DB::table('jenis_transaksi')
        ->join('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->get(['jenis_transaksi.id', 'nama']);

        $transaksi = DB::table('transaksi_jurnal')->get(['id', 'nama']);
        $kodes = JenisTransaksi::select('kode')->whereNull('id_induk')->orderByDesc('id')->first();
        $kodeS = ($kodes) ? $kodes->kode+1 : 1;

        return $this->view( "form", compact('jenisTransaksi', 'transaksi', 'induk', 'aksi', 'kodeS'));
    }

    public function isiUrutan ($id_induk)
    {
        $data = ArusKa::selectRaw('urutan +1 as urutan')->where('id', $id_induk)->orderByDesc('id')->first();

        echo json_encode($data);
        exit;
    }

    public function update(Request $request, $kode)
    {
        $arusKa=JenisTransaksi::find($kode);
        $act=$arusKa->update($request->except('user_input', 'user_delete'));
        message($act,'Data Arus Kas berhasil diupdate','Data Arus Kas gagal diupdate');

        return redirect('/arus-ka');
    }

    public function destroy(Request $request, $kode)
    {
        $arusKa=JenisTransaksi::find($kode);
        $act=false;

        try {
            $act=$arusKa->forceDelete();
        } catch (\Exception $e) {
            $arusKa=JenisTransaksi::find($arusKa->pk());
            $act=$arusKa->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = JenisTransaksi::selectRaw('jenis_transaksi.id, transaksi_jurnal.nama, jenis_transaksi.kode')
        ->selectRaw('jenis_transaksi.urutan, jenis_transaksi.level, tj1.nama AS id_induk')
        ->leftJoin('jenis_transaksi as jt1', 'jt1.id', 'jenis_transaksi.id_induk')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->leftJoin('transaksi_jurnal as tj1', 'tj1.id', 'jt1.id_transaksi_jurnal');


        if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)
        ->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {

            $edit=url("arus-ka/".$data->pk())."/edit";
            $delete=url("arus-ka/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
