<?php
namespace App\Http\Controllers;
use DB;
use App\Models\AkunAnggaran;
use Illuminate\Http\Request;
use App\Models\Perkiraan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class AkunAnggaranController extends Controller
{
    public $viewDir = "akun_anggaran";
    public $breadcrumbs = array('permissions'=>array('title'=>'Akun-anggaran','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-akun-anggaran');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $induk = AkunAnggaran::select('id', 'nama')->get();
        $akunAnggaran = new AkunAnggaran;

        return $this->view("form", compact('akunAnggaran', 'perkiraan', 'induk'));
    }
    
    public function cekKode ($kode)
    {
        $data = AkunAnggaran::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();
        
        try {

            $this->validate($request, AkunAnggaran::validationRules());
            $data = AkunAnggaran::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('kode', $request->kode)
            ->first();
            
            if ($data->status == 'Tidak Ada')
            {
                $urutanLevel = AkunAnggaran::selectRaw('CASE WHEN COUNT(id_induk) >0  THEN LEVEL+1 ELSE 0 END AS level,
                CASE WHEN COUNT(id_induk) >0  THEN urutan+1 ELSE "" END AS urutan')->where('id', $request->id_induk)
                ->orderByDesc('id')
                ->first();

                $urutan = $urutanLevel->urutan;
                $level = $urutanLevel->level;

                $act= new AkunAnggaran;
                $act->kode = $request->kode;
                $act->nama = $request->nama;
                $act->tipe = $request->tipe;
                $act->level = $level;
                $act->urutan = $urutan;
                $act->id_induk = $request->id_induk;
                $act->keterangan = $request->keterangan;
                $act->id_perkiraan = $request->id_perkiraan;
                $act->user_input = $request->user_input;
                $act->save();
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }

        if ($data->status == 'Ada')
        {
            return redirect('akun-anggaran')->with('warning', 'Gagal simpan karena kode akun anggaran sudah ada');
        } else {
            return redirect('akun-anggaran')->with('info', 'Akun Anggaran berhasil simpan');
        }
    }
  
    public function show(Request $request, $kode)
    {
        $akunAnggaran=AkunAnggaran::find($kode);
        return $this->view("show",['akunAnggaran' => $akunAnggaran]);
    }

    public function edit(Request $request, $kode)
    {
        $akunAnggaran=AkunAnggaran::find($kode);
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $induk = AkunAnggaran::select('id', 'nama')->get();

        return $this->view( "form", compact('akunAnggaran', 'perkiraan', 'induk'));
    }

    public function update(Request $request, $kode)
    {
        $akunAnggaran=AkunAnggaran::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, AkunAnggaran::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $akunAnggaran->update($data);
                return "Record updated";
            }

        $this->validate($request, AkunAnggaran::validationRules());
        $cek = AkunAnggaran::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $request->kode)->first();

        if ($cek->status == 'Ada')
        {
            message(false,'','Data Akun Anggaran gagal ditambahkan karena kode sudah ada');
            echo "Data Akun Anggaran gagal diupdate karena kode sudah ada.";
        }

        if ($cek->status == 'Tidak Ada')
        {
            $act=$akunAnggaran->update($request->all());
            message($act,'Data Akun Anggaran berhasil diupdate','Data Akun Anggaran gagal diupdate');
        }
        return redirect('/akun-anggaran');
    }

    public function destroy(Request $request, $kode)
    {
        $akunAnggaran=AkunAnggaran::find($kode);
        $act=false;
        try {
            $act=$akunAnggaran->forceDelete();
        } catch (\Exception $e) {
            $akunAnggaran=AkunAnggaran::find($akunAnggaran->pk());
            $act=$akunAnggaran->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $nama = request()->get('nama');
        $tipe = request()->get('tipe');
        $induk = request()->get('induk');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = AkunAnggaran::selectRaw('akun_anggaran.id as id, akun_anggaran.nama, akun_anggaran.kode, perkiraan.nama as perkiraan, CASE 
        WHEN akun_anggaran.tipe=1 THEN "header" WHEN akun_anggaran.tipe=2 THEN "detail" END AS tipe, dua.nama as induk')
        ->leftJoin('akun_anggaran as dua', 'dua.id', 'akun_anggaran.id_induk')
        ->leftJoin('perkiraan', 'perkiraan.id', 'akun_anggaran.id_perkiraan');
        
        if ($nama)
        {
            $dataList->where('akun_anggaran.nama', 'like', $nama.'%');
        }

        if ($tipe)
        {
            $dataList->where('akun_anggaran.tipe', $tipe);
        }

        if ($induk)
        {
            $dataList->where('dua.nama', $induk);
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
           
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
        
            $edit=url("akun-anggaran/".$data->pk())."/edit";
            $delete=url("akun-anggaran/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
