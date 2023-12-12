<?php
namespace App\Http\Controllers;
use App\Support\Helpers;
use App\Models\InstansiRelasi;
use Illuminate\Http\Request;
use App\pembelian;
use App\provinsi;
use App\Models\Perkiraan;
use App\kabupaten;
use DB;
use App\kecamatan;
use App\kelurahan;
use App\Models\TerminPembayaran;
use App\jenis_instansi;
use App\Models\TarifPajak;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class InstansiRelasiController extends Controller
{
    public $viewDir = "instansi_relasi";
    public $breadcrumbs = array('permissions'=>array('title'=>'Instansi-relasi','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-instansi-relasi');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $data = [
            'provinsi'=>provinsi::all(),
            'termin'=>TerminPembayaran::all(),
            'rekening'=>Perkiraan::all(),
            'pajak'=>TarifPajak::all(),
            'jenis'=>jenis_instansi::all(),
            'instansiRelasi'=>new InstansiRelasi
        ];

        return $this->view("form")->with($data);
    }

    public function getKabupaten ($id=0)
    {
        $kabupaten['data'] = kabupaten::orderBy('kabupaten', 'asc')->select('id', 'kabupaten')->where('id_provinsi', $id)->get();

        echo json_encode($kabupaten);
        exit;
    }

    public function getKecamatan ($id=0)
    {
        $kecamatan['data'] = kecamatan::orderBy('kecamatan', 'asc')
        ->select('id', 'kecamatan')
        ->where('id_kabupaten', $id)
        ->get();

        echo json_encode($kecamatan);
        exit;
    }

    public function getKelurahan ($id=0)
    {
        $kelurahan['data'] = kelurahan::orderBy('kelurahan', 'asc')
        ->select('id', 'kelurahan')
        ->where('id_kecamatan', $id)
        ->get();

        echo json_encode($kelurahan);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {

            $this->validate($request, InstansiRelasi::validationRules());
            $batas_kreditt = str_replace('.', '', $request->batas_kredit);
            $batas_kredit = str_replace(',', '.', $batas_kreditt);
            //$act=InstansiRelasi::create($request->all());
            $act = new InstansiRelasi;
            $act->saldo_hutang =0;
            $act->tanggal_hutang ='0000-00-00';
            $act->jatuh_tempo = '0000-00-00';
            $act->alamat = $request->alamat;
            $act->kode = $request->kode;
            $act->nama = $request->alamat;
            $act->telp = $request->telp;
            $act->email = $request->email;
            $act->rekening = $request->rekening;
            $act->atas_nama = $request->atas_nama;
            $act->batas_kredit = $batas_kredit;
            $act->id_jenis_instansi_relasi = $request->id_jenis_instansi_relasi;
            $act->id_provinsi = $request->id_provinsi;
            $act->id_kabupaten = $request->id_kabupaten;
            $act->id_kecamatan = $request->id_kecamatan;
            $act->id_kelurahan = $request->id_kelurahan;
            $act->id_termin = $request->id_termin;
            $act->id_perkiraan = $request->id_perkiraan;
            $act->id_tarif_pajak = $request->id_tarif_pajak;
            $act->save();

            DB::commit();
            message($act,'Data Instansi Relasi berhasil ditambahkan','Data Instansi Relasi gagal ditambahkan');
            return redirect('instansi-relasi');

        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Instansi Relasi berhasil ditambahkan','Data Instansi Relasi gagal ditambahkan');
            return redirect('instansi-relasi');
        }
    }

    public function show(Request $request, $kode)
    {
        $instansiRelasi=InstansiRelasi::find($kode);
        return $this->view("show",['instansiRelasi' => $instansiRelasi]);
    }

    public function edit(Request $request, $kode)
    {
        $instansiRelasi=InstansiRelasi::selectRaw('instansi_relasi.id, instansi_relasi.kode, instansi_relasi.nama, alamat, telp,
        email, rekening, atas_nama,saldo_hutang, batas_kredit, tanggal_hutang, jatuh_tempo, id_jenis_instansi_relasi, id_termin,
        id_tarif_pajak, instansi_relasi.id_provinsi, instansi_relasi.id_kabupaten, instansi_relasi.id_kecamatan, instansi_relasi.id_kelurahan,
        provinsi.provinsi, kabupaten.kabupaten, kecamatan.kecamatan, kelurahan.kelurahan, id_perkiraan')
        ->leftJoin('provinsi', 'provinsi.id', 'instansi_relasi.id_provinsi')
        ->leftJoin('kabupaten', 'kabupaten.id', 'instansi_relasi.id_kabupaten')
        ->leftJoin('kecamatan', 'kecamatan.id', 'instansi_relasi.id_kecamatan')
        ->leftJoin('kelurahan', 'kelurahan.id', 'instansi_relasi.id_kelurahan')
        ->where('instansi_relasi.id',$kode)
        ->first();

        $data = [
            'provinsi'=>provinsi::get(['id', 'provinsi']),
            'termin'=>TerminPembayaran::all(),
            'rekening'=>Perkiraan::get(['id', 'nama']),
            'pajak'=>TarifPajak::get(['id', 'nama_pajak']),
            'jenis'=>jenis_instansi::get(['id', 'nama']),
            'instansiRelasi'=>$instansiRelasi
        ];

        return $this->view("form")->with($data);
    }

    public function update(Request $request, $kode)
    {
        $instansiRelasi=InstansiRelasi::find($kode);
        if( $request->isXmlHttpRequest()):
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, InstansiRelasi::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $instansiRelasi->update($data);
                return "Record updated";
        endif;

        $this->validate($request, InstansiRelasi::validationRules());

        $batas_kreditt = str_replace('.', '', $request->batas_kredit);
        $batas_kredit = str_replace(',', '.', $batas_kreditt);

        $act= InstansiRelasi::where('id', $kode)->update([
            'saldo_hutang'=>0,
            'tanggal_hutang'=>'0000-00-00',
            'jatuh_tempo'=>'0000-00-00',
            'alamat'=>$request->alamat,
            'kode'=>$request->kode,
            'nama'=>$request->alamat,
            'telp'=>$request->telp,
            'email'=>$request->email,
            'rekening'=>$request->rekening,
            'atas_nama'=>$request->atas_nama,
            'batas_kredit'=>$batas_kredit,
            'id_jenis_instansi_relasi'=>$request->id_jenis_instansi_relasi,
            'id_provinsi'=>$request->id_provinsi,
            'id_kabupaten'=>$request->id_kabupaten,
            'id_kecamatan'=>$request->id_kecamatan,
            'id_kelurahan'=>$request->id_kelurahan,
            'id_termin'=>$request->id_termin,
            'id_perkiraan'=>$request->id_perkiraan,
            'id_tarif_pajak'=>$request->id_tarif_pajak,]);

        message($act,'Data Instansi Relasi berhasil diupdate','Data Instansi Relasi gagal diupdate');
        return redirect('/instansi-relasi');
    }

    public function destroy(Request $request, $kode)
    {
        $instansiRelasi=InstansiRelasi::find($kode);
        $act=false;
        try {
            $act=$instansiRelasi->forceDelete();
        } catch (\Exception $e) {
            $instansiRelasi=InstansiRelasi::find($instansiRelasi->pk());
            $act=$instansiRelasi->delete();
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
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = InstansiRelasi::select('instansi_relasi.id', 'instansi_relasi.kode', 'instansi_relasi.nama as pemasok',
        'jenis_instansi_relasi.nama as instansi', 'telp', 'email',
        DB::raw("CONCAT(provinsi.provinsi, ' ', kabupaten.kabupaten, ' ', kecamatan.kecamatan, ' ', kelurahan.kelurahan, ' ', alamat ) AS alamat"),
        'rekening', 'atas_nama', 'saldo_hutang', 'batas_kredit', 'tarif_pajak.nama_pajak', 'termin_pembayaran.termin')
        ->leftJoin('jenis_instansi_relasi', 'jenis_instansi_relasi.id',  'instansi_relasi.id_jenis_instansi_relasi')
        ->leftJoin('provinsi', 'provinsi.id', 'instansi_relasi.id_provinsi')
        ->leftJoin('kabupaten', 'kabupaten.id', 'instansi_relasi.id_kabupaten')
        ->leftJoin('kecamatan', 'kecamatan.id', 'instansi_relasi.id_kecamatan')
        ->leftJoin('kelurahan', 'kelurahan.id', 'instansi_relasi.id_kelurahan')
        ->leftJoin('tarif_pajak', 'tarif_pajak.id', 'instansi_relasi.id_tarif_pajak')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id', 'instansi_relasi.id_termin');

        if ($nama):
            $dataList->where('instansi_relasi.nama', 'like', $nama.'%');
        endif;

        if ($kode):
            $dataList->where('instansi_relasi.kode', 'like', $kode.'%');
        endif;

        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;

        return Datatables::of($dataList)->addColumn('nomor',function($kategori) {
            return $GLOBALS['nomor']++;
        })
        ->addColumn('kode', function ($data) {
            return $data->kode ?? 0;
        })
        ->addColumn('nama', function ($data) {
            return $data->nama ?? 0;
        })
        ->addColumn('jenis', function ($data) {
            return $data->jenis ?? 0;
        })
        ->addColumn('saldo_hutang', function ($data) {
            return nominalTitik($data->saldo_hutang) ?? 0;
        })
        ->addColumn('batas_kredit', function ($data) {
            return nominalTitik($data->batas_kredit) ?? 0;
        })
        ->addColumn('action', function ($data) {

            $edit=url("instansi-relasi/".$data->pk())."/edit";
            $delete=url("instansi-relasi/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
    
            return $content;
        })->make(true);
    }
}
