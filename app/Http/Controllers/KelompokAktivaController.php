<?php
namespace App\Http\Controllers;
use DB;
use App\Models\KelompokAktiva;
use Illuminate\Http\Request;
use App\Models\Perkiraan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KelompokAktivaController extends Controller
{
    public $viewDir = "kelompok_aktiva";
    public $breadcrumbs = array('permissions'=>array('title'=>'Kelompok-aktiva','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-kelompok-aktiva');
    }

    public function index()
    {
        return $this->view("index");
    }

    public function create()
    {
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        return $this->view("form",['kelompokAktiva' => new KelompokAktiva])->with('perkiraan', $perkiraan);
    }

    public function cekKode ($kode)
    {
        $data = KelompokAktiva::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('kode', $kode)->first();
        echo json_encode($data);
        exit;
    }

    public function cekNama ($nama)
    {
        $data = KelompokAktiva::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('nama', $nama)->first();
        echo json_encode($data);
        exit;
    }

    public function store (Request $request)
    {
        DB::beginTransaction();
        try {

            $this->validate($request, KelompokAktiva::validationRules());

            $cek = KelompokAktiva::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('kode', $request->kode)
            ->orWhere('nama', $request->nama)
            ->first();

            if ($request->flag_penyusutan == 'N' && $cek->status == 'Tidak Ada') {
                $act=KelompokAktiva::create($request->except('user_update', 'harga_perolehan', 'biaya_penyusutan', 'akumulasi_penyusutan'));
            } else if ($request->flag_penyusutan == 'Y' && $cek->status == 'Tidak Ada') {
                $act=KelompokAktiva::create($request->except('user_update'));
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollback();
        }

        if ($cek->status == 'Ada'){
            return redirect('kelompok-aktiva')->with('error', 'Kode atau Nama Kelompok Aktiva Sudah dipakai');
        } else if ($request->flag_penyusutan == 'N' && $cek->status == 'Tidak Ada'){
            return redirect('kelompok-aktiva')->with('success', 'Kelompok Aktiva Sudah disimpan');
        } else if ($request->flag_penyusutan == 'Y' && $cek->status == 'Tidak Ada'){
            return redirect('kelompok-aktiva')->with('success', 'Kelompok Aktiva Sudah disimpan');
        }
    }

    public function show(Request $request, $kode)
    {
        $kelompokAktiva=KelompokAktiva::find($kode);
        return $this->view("show",['kelompokAktiva' => $kelompokAktiva]);
    }

    public function edit(Request $request, $kode)
    {
        $kelompokAktiva=KelompokAktiva::find($kode);
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        return $this->view( "form", ['kelompokAktiva' => $kelompokAktiva] )->with('perkiraan', $perkiraan);
    }

    public function activate(Request $request, $kode)
    {
        $kelompokAktiva= KelompokAktiva::find($kode);
        $data=array('flag_aktif'=>'Y',);

        $status=$kelompokAktiva->update($data);
        message($status,'Kelompok Aktiva Berhasil Diaktifkan Kembali','Kelompok Aktiva Gagal Diaktifkan Kembali');

        return redirect('/kelompok-aktiva');
    }

    public function deactivate(Request $request, $kode)
    {
        $kelompokAktiva=KelompokAktiva::find($kode);
        $data=array('flag_aktif'=>'N',);

        $status=$kelompokAktiva->update($data);
        message($status,'Kelompok Aktiva Berhasil Dinonaktifkan','Kelompok Aktiva Gagal Dinonaktifkan');

        return redirect('/kelompok-aktiva');
    }

    public function update(Request $request, $id)
    {
        $kelompokAktiva=KelompokAktiva::find($id);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, KelompokAktiva::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $kelompokAktiva->update($data);
                return "Record updated";
        }
        $this->validate($request, KelompokAktiva::validationRules());

        $cek = KelompokAktiva::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('kode', $request->kode)
        ->orWhere('nama', $request->nama)
        ->where('id', '<>', $id)
        ->first();

        if ($cek->status == 'Ada')
        {
            message(false,'','Data Termin Pembayaran gagal ditambahkan karena kode sudah ada');
        }

        if ($request->flag_penyusutan == 'N' && $cek->status == 'Tidak Ada')
        {
            $act=$kelompokAktiva->update($request->except('user_input', 'harga_perolehan', 'biaya_penyusutan', 'akumulasi_penyusutan' ));
            message($act,'Data Kelompok Aktiva berhasil diupdate','Data Kelompok Aktiva gagal diupdate');
        }
        if ($request->flag_penyusutan == 'Y' && $cek->status == 'Tidak Ada')
        {
            $act=$kelompokAktiva->update($request->except('user_input'));
            message($act,'Data Kelompok Aktiva berhasil diupdate','Data Kelompok Aktiva gagal diupdate');
        }
        return redirect('/kelompok-aktiva');
    }

    public function destroy(Request $request, $kode)
    {
        $kelompokAktiva=KelompokAktiva::find($kode);
        $act=false;
        try {
            $act=$kelompokAktiva->forceDelete();
        } catch (\Exception $e) {
            $kelompokAktiva=KelompokAktiva::find($kelompokAktiva->pk());
            $act=$kelompokAktiva->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = KelompokAktiva::select('kelompok_aktiva.id', 'kelompok_aktiva.nama','kelompok_aktiva.kode', 'flag_penyusutan',
        'flag_aktif', 'perkiraan.nama as harga_perolehan', 'p.nama as biaya_penyusutan','pk.nama as akumulasi_penyusutan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kelompok_aktiva.harga_perolehan')
        ->leftJoin('perkiraan as p', 'p.id', 'kelompok_aktiva.biaya_penyusutan')
        ->leftJoin('perkiraan as pk','pk.id', 'kelompok_aktiva.akumulasi_penyusutan');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori)
        {
            return $GLOBALS['nomor']++;
        })->addColumn ('flag_penyusutan', function ($data){

            if(isset($data->flag_penyusutan))
            {
                return array('id'=>$data->pk(), 'flag_penyusutan'=>$data->flag_penyusutan);
            } else {

                return null;
            }

            })->addColumn ('flag_aktif', function ($data)
            {
                if(isset($data->flag_aktif))
                {
                    return array('id'=>$data->pk(), 'flag_aktif'=>$data->flag_aktif);
                } else {
                    return null;
                }

            })->addColumn('action', function ($data) {
            $edit=url("kelompok-aktiva/".$data->pk())."/edit";
            $delete=url("kelompok-aktiva/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
