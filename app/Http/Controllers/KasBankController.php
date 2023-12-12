<?php
namespace App\Http\Controllers;

use App\Models\KasBank;
use Illuminate\Http\Request;
use App\Models\JenisUsaha;
use App\Http\Requests;
use App\setting_coa;
use App\Models\Perkiraan;
use App\Http\Controllers\Controller;
use DB;
use Datatables;

class KasBankController extends Controller
{
    public $viewDir = "kas_bank";
    public $breadcrumbs = array('permissions'=>array('title'=>'Kas-bank','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-kas-bank');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $aksi = "create";
        $kasBank = new KasBank;
        $perkiraan = Perkiraan::get(['id', 'nama']);
        $jenisUsaha = JenisUsaha::get(['id', 'nama']);

        return $this->view("form", compact('kasBank', 'jenisUsaha', 'perkiraan', 'aksi'));
    }

    public function cekKode ($kode_bank)
    {
        $data = KasBank::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode_bank', $kode_bank)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        //$this->validate($request, KasBank::validationRules());
        DB::beginTransaction();

        try {

            $cek = KasBank::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('kode_bank', $request->kode_bank)
            ->first();

            if ($cek->status == 'Tidak Ada')
            {
                $act= new KasBank;
                $act->kode_bank = $request->kode_bank;
                $act->nama = $request->nama;
                $act->keterangan = $request->keterangan;
                $act->alamat = $request->alamat;
                $act->email = $request->email;
                $act->telepon = $request->telepon;
                $act->fax = $request->fax;
                $act->id_jenis_usaha = $request->id_jenis_usaha;
                $act->rekening = $request->rekening;
                $act->kode_pos = $request->kode_pos;
                $act->negara = $request->negara;
                $act->id_perkiraan = $request->id_perkiraan;
                $act->save();

                $coa = new setting_coa;
                $coa->keterangan = 'Kas Bank';
                $coa->jenis = $act->nama;
                $coa->id_bank = $act->id;
                $coa->id_perkiraan = 3;
                $coa->save();
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }

        if ($cek->status == 'Ada')
        {
            return redirect('kas-bank')->with('danger', 'Gagal simpan karena kode Kas Bank sudah ada');
        } else {
            return redirect('kas-bank')->with('success', 'Kas Bank berhasil simpan');
        }
    }

    public function show(Request $request, $kode)
    {
        $kasBank=KasBank::find($kode);
        return $this->view("show",['kasBank' => $kasBank]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi = "update";
        $kasBank=KasBank::find($kode);
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $jenisUsaha = JenisUsaha::select('id','nama')->get();
        return $this->view( "form", compact('kasBank', 'jenisUsaha', 'perkiraan', 'aksi'));
    }

    public function activate(Request $request, $kode)
    {
        $kasBank= KasBank::find($kode);
        $data=array('flag_aktif'=>'Y',);

        $status=$kasBank->update($data);
        message($status,'Kas Bank Berhasil Diaktifkan Kembali','Kas Bank Gagal Diaktifkan Kembali');

        return redirect('/kas-bank');
    }

    public function deactivate(Request $request, $kode)
    {
        $kasBank=KasBank::find($kode);
        $data=array('flag_aktif'=>'N',);

        $status=$kasBank->update($data);
        message($status,'Kas Bank Berhasil Dinonaktifkan','Kas Bank Gagal Dinonaktifkan');

        return redirect('/kas-bank');
    }

    public function update(Request $request, $kode)
    {
        $kasBank=KasBank::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, KasBank::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $kasBank->update($data);
                return "Record updated";
        }

        $this->validate($request, KasBank::validationRules());
        $cek = KasBank::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode_bank', $request->kode_bank)
        ->where('id', '<>', $kode)
        ->first();

        if ($cek->status == 'Ada')
        {
            message(false, 'Gagal simpan karena kode sudah ada', 'Gagal Simpan karena kode sudah ada');
            echo "Gagal Simpan karena kode sudah ada";
        }

        if ($cek->status == 'Tidak Ada')
        {
            $act=$kasBank->update($request->all());
            message($act,'Data Kas Bank berhasil diupdate','Data Kas Bank gagal diupdate');
        }
        return redirect('/kas-bank');
    }

    public function destroy(Request $request, $kode)
    {
        $kasBank=KasBank::find($kode);
        $act=false;

        try {
            $act=$kasBank->forceDelete();
        } catch (\Exception $e) {
            $kasBank=KasBank::find($kasBank->pk());
            $act=$kasBank->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = KasBank::select('kas_bank.id', 'kode_bank', 'kas_bank.nama', 'rekening');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('flag_aktif', function($data){

        if(isset($data->flag_aktif)){
            return array ('id'=>$data->pk(), 'flag_aktif'=>$data->flag_aktif);
        } else {
            return null;
        }

        })->addColumn('action', function ($data) {

            $edit=url("kas-bank/".$data->pk())."/edit";
            $delete=url("kas-bank/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
