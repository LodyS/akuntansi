<?php
namespace App\Http\Controllers;
use App\Support\Helpers;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\billing;
use App\visit;
use App\penjualan;
use App\penjualan_resep;
use App\Models\TerminPembayaran;
use App\pembayaran;
use App\provinsi;
use App\Models\Perkiraan;
use App\kabupaten;
use App\kecamatan;
use App\kelurahan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;
use DB;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public $viewDir = "pelanggan";
    public $breadcrumbs = array('permissions'=>array('title'=>'Pelanggan','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-pelanggan');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $pelanggan = new Pelanggan;
        $provinsi = DB::table('provinsi')->select('id', 'provinsi')->get();
        $termin = DB::table('termin_pembayaran')->select('id', 'kode')->get();
        $rekening = DB::table('perkiraan')->select('id', 'nama')->get();

        return $this->view("form", compact('pelanggan', 'provinsi', 'termin', 'rekening'));
    }

    public function getKabupaten ($id=0)
    {
        $kabupaten['data'] = DB::table('kabupaten')->orderBy('kabupaten', 'asc')->select('id', 'kabupaten')->where('id_provinsi', $id)->get();

        echo json_encode($kabupaten);
        exit;
    }

    public function getKecamatan ($id=0)
    {
        $kecamatan['data'] = DB::table('kecamatan')->orderBy('kecamatan', 'asc')->select('id', 'kecamatan')->where('id_kabupaten', $id)->get();

        echo json_encode($kecamatan);
        exit;
    }

    public function getKelurahan ($id=0)
    {
        $kelurahan['data'] = DB::table('kelurahan')->orderBy('kelurahan', 'asc')->select('id', 'kelurahan')->where('id_kecamatan', $id)->get();

        echo json_encode($kelurahan);
        exit;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            //$this->validate($request, Pelanggan::validationRules());
            $batas_kreditt = str_replace('.','',$request->batas_kredit);
            $batas_kredit = str_replace(',', '.', $batas_kreditt);
            $saldo_piutangg = str_replace('.', '', $request->saldo_piutang);
            $saldo_piutang = str_replace(',', '.', $saldo_piutangg);

            $act= new Pelanggan;
            $act->kode = $request->kode;
            $act->nama = $request->nama;
            $act->keterangan = $request->keterangan;
            $act->telp = $request->telp;
            $act->email = $request->email;
            $act->alamat = $request->alamat;
            $act->batas_kredit = $batas_kredit;
            $act->id_user = $request->id_user;
            $act->saldo_piutang = $saldo_piutang;
            $act->id_provinsi = $request->id_provinsi;
            $act->id_kabupaten = $request->id_kabupaten;
            $act->id_kecamatan = $request->id_kecamatan;
            $act->id_kelurahan = $request->id_kelurahan;
            $act->id_termin = $request->id_termin;
            $act->id_perkiraan = $request->id_perkiraan;
            $act->save();

            $update = Perkiraan::where('id', $request->id_perkiraan)->update(['debet' => $saldo_piutang]);

            DB::commit();
            message($act,'Data Pelanggan berhasil ditambahkan','Data Pelanggan gagal ditambahkan');
            return redirect('pelanggan');

        } catch (Exception $e){

            DB::rollback();
            message(false, 'Data Pelanggan gagal disimpan', 'Data Pelanggan Gagal disimpan');
			return redirect('/pelanggan');
        }
    }

    public function show(Request $request, $kode)
    {
        $pelanggan=Pelanggan::find($kode);
        return $this->view("show",['pelanggan' => $pelanggan]);
    }

    public function edit(Request $request, $kode)
    {
        $pelanggan=Pelanggan::selectRaw('pelanggan.id, pelanggan.kode, pelanggan.nama, pelanggan.keterangan, pelanggan.telp,
        pelanggan.email, pelanggan.alamat, pelanggan.batas_kredit, pelanggan.id_provinsi, pelanggan.id_kabupaten,
        pelanggan.id_kecamatan, pelanggan.id_kelurahan,
        id_termin, id_perkiraan, kabupaten, kecamatan, kelurahan')
        ->leftJoin('kelurahan', 'kelurahan.id', 'pelanggan.id_kelurahan')
        ->leftJoin('kecamatan', 'kecamatan.id', 'pelanggan.id_kecamatan')
        ->leftJoin('kabupaten', 'kabupaten.id', 'pelanggan.id_kabupaten')
        ->where('pelanggan.id',$kode)
        ->first();

        $provinsi = DB::table('provinsi')->select('id', 'provinsi')->get();
        $termin = DB::table('termin_pembayaran')->select('id', 'kode')->get();
        $rekening = DB::table('perkiraan')->select('id', 'nama')->get();
        return $this->view( "form", compact('pelanggan', 'provinsi', 'termin', 'rekening') );
    }

    public function activate(Request $request, $kode)
    {
         // dd($kode);
        $pelanggan= Pelanggan::find($kode);
        $data=array('flag_aktif'=>'Y',);

        $status=$pelanggan->update($data);
        message($status,'Pelanggan Berhasil Diaktifkan Kembali','Pelanggan Gagal Diaktifkan Kembali');

        return redirect('pelanggan');
    }

    public function deactivate(Request $request, $kode)
    {
        $pelanggan=Pelanggan::find($kode);
        $data=array('flag_aktif'=>'N',);

        $status=$pelanggan->update($data);
        message($status,'Pelanggan Berhasil Dinonaktifkan','Pelanggan Gagal Dinonaktifkan');

        return redirect('pelanggan');
    }

    public function update(Request $request, $kode)
    {
        //$pelanggan=Pelanggan::find($kode);
        $batas_kreditt = str_replace('.','',$request->batas_kredit);
        $batas_kredit = str_replace(',', '.', $batas_kreditt);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Pelanggan::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $pelanggan->update($data);
                return "Record updated";
            }

        //$this->validate($request, Pelanggan::validationRules());
        $act= Pelanggan::where('id', $kode)->update([
            'kode'=>$request->kode,
            'nama'=>$request->nama,
            'keterangan'=>$request->keterangan,
            'telp'=>$request->telp,
            'email'=>$request->email,
            'alamat'=>$request->alamat,
            'batas_kredit'=>$batas_kredit,
            'id_provinsi'=>$request->id_provinsi,
            'id_kabupaten'=>$request->id_kabupaten,
            'id_kecamatan'=>$request->id_kecamatan,
            'id_kelurahan'=>$request->id_kelurahan,
            'id_termin'=>$request->id_termin,
            'id_perkiraan'=>$request->id_perkiraan
        ]);

        message($act,'Data Pelanggan berhasil diupdate','Data Pelanggan gagal diupdate');
        return redirect('/pelanggan');
    }

    public function destroy(Request $request, $kode)
    {
        $pelanggan=Pelanggan::find($kode);
        $act=false;
        try {
            $act=$pelanggan->forceDelete();
        } catch (\Exception $e) {
            $pelanggan=Pelanggan::find($pelanggan->pk());
            $act=$pelanggan->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $nama = request()->get('nama');
        $kode = request()->get('kode');
        $date = Carbon::now();
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Pelanggan::selectRaw('pelanggan.id,  pelanggan.nama, keterangan,  flag_aktif, pelanggan.saldo_piutang, batas_kredit,
        perkiraan.nama as rekening_kontrol, termin_pembayaran.termin as termin')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id', 'pelanggan.id_termin')
        ->leftJoin('perkiraan', 'perkiraan.id', 'pelanggan.id_perkiraan');

        if ($nama) {
            $dataList->where('pelanggan.nama', 'like', $nama.'%');
        }

        if ($kode) {
            $dataList->where('pelanggan.kode', 'like', $kode.'%');
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;

        })->addColumn('flag_aktif', function($data){

            if (isset ($data->flag_aktif)){
                return array ('id'=>$data->pk(), 'flag_aktif'=>$data->flag_aktif);
            } else {
                return null;
            }
        })->addColumn('saldo_piutang', function ($data) {

            if (isset($data->saldo_piutang)){
                $saldo_piutang = nominalKoma($data->saldo_piutang);
                return $saldo_piutang;
            } else {
                return 0;
            }
        })->addColumn('batas_kredit', function ($data) {

            if (isset($data->batas_kredit)){
                $batas_kredit = nominalTitik($data->batas_kredit);
                return $batas_kredit;
            } else {
                return 0;
            }
        })->addColumn('action', function ($data) {
            $edit=url("pelanggan/".$data->pk())."/edit";
            $delete=url("pelanggan/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";
            return $content;
        })->make(true);
    }
}
