<?php
namespace App\Http\Controllers;

use App\Models\Perkiraan;
use Illuminate\Http\Request;
use App\Models\Fungsi;
use App\KategoriPerkiraan;
use App\Models\PeriodeKeuangan;
use App\Http\Requests;
use App\Models\CabangUser;
use DB;
use Auth;
use Carbon\Carbon;
use App\transaksi;
use App\Http\Controllers\Controller;
use Datatables;

class PerkiraanController extends Controller
{
    public $viewDir = "perkiraan";
    public $breadcrumbs = array('permissions'=>array('title'=>'Perkiraan','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-perkiraan');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $fungsi = Fungsi::select('id', 'nama_fungsi')->get();
        $kira = Perkiraan::select('id', 'nama')->get();
        $kategori = KategoriPerkiraan::select('id', 'nama')->get();
        $id_user = Auth::user()->id;
        $cabanguser = CabangUser::select('id')->where('id_user', $id_user)->first();
        $periodeKeuangan = PeriodeKeuangan::select('id')->where('status_aktif', 'Y')->first();
        $perkiraan = new Perkiraan;

        return $this->view("form", compact('perkiraan', 'fungsi', 'kira', 'kategori', 'periodeKeuangan', 'cabanguser'));
    }

    public function isiKolom ($id)
    {
        $induk = Perkiraan::find($id);
        $lastKode = Perkiraan::where('id_induk', $id)->max('kode');
        $newKode = $lastKode ? (int)$lastKode + 1 : $induk->kode . '1';
        $newLevel = (int)$induk->level + 1;

        return response()->json(['kode'=>$newKode, 'level'=>$newLevel]);
	}

    public function cekNama ($nama)
    {
        $data = Perkiraan::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('nama', $nama)->first();

        echo json_encode($data);
        exit;
    }

    public function store (Request $request)
    {
        DB::beginTransaction();

        try {

            $saldo_awall = str_replace('.', '', $request->saldo_awal);
            $saldo_awal = str_replace(',', '.', $saldo_awall);
            $this->validate($request, Perkiraan::validationRules());
            $id_periode = $request->id_periode;

            $cek = Perkiraan::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('nama', $request->nama)->first();

            if ($request->id_kategori ==1 && $id_periode != null && $cek->status == 'Tidak Ada'):
            
                $act = new Perkiraan;
                $act->kode = $request->kode;
                $act->id_kategori = $request->id_kategori;
                $act->nama = $request->nama;
                $act->fungsi = $request->fungsi;
                $act->bagian = $request->bagian;
                $act->debet = $saldo_awal;
                $act->kredit = 0;
                $act->level = $request->level;
                $act->id_induk = $request->id_induk;
                $act->type = $request->type;
                $act->flag_sistem = 'Y';
                $act->save();

                $id_perkiraan = $act->id;
                $user_id = $request->id_cabang_user;
                $sekarang = date('d-m-y');
    
                if ($request->id_cabang_user != null):
                    $transaksi = new transaksi;
                    $transaksi->id_user = $user_id;
                    $transaksi->id_perkiraan = $id_perkiraan;
                    $transaksi->keterangan = 'Input dari perkiraan';
                    $transaksi->id_periode = $id_periode;
                    $transaksi->tanggal = $sekarang;
                    $transaksi->debet = 0;
                    $transaksi->kredit = 0;
                    $transaksi->save();
                endif;
            endif;

            if ($request->id_kategori ==2 && $id_periode != null):
                $act = new Perkiraan;
                $act->kode = $request->kode;
                $act->id_kategori = $request->id_kategori;
                $act->nama = $request->nama;
                $act->fungsi = $request->fungsi;
                $act->bagian = $request->bagian;
                $act->debet = 0;
                $act->kredit = $saldo_awal;
                $act->level = $request->level;
                $act->id_induk = $request->id_induk;
                $act->type = $request->type;
                $act->flag_sistem = 'Y';
                $act->save();

                $id_perkiraan = $act->id;
                $user_id = $request->id_cabang_user;
                $sekarang = date('d-m-y');
    
                if ($request->id_cabang_user != null):
                    $transaksi = new transaksi;
                    $transaksi->id_user = $user_id;
                    $transaksi->id_perkiraan = $id_perkiraan;
                    $transaksi->keterangan = 'Input dari perkiraan';
                    $transaksi->id_periode = $id_periode;
                    $transaksi->tanggal = $sekarang;
                    $transaksi->debet = 0;
                    $transaksi->kredit = 0;
                    $transaksi->save();
                endif;
            endif;

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
        }

        if ($cek->status == 'Ada' || $request->id_periode == null):
            return redirect('perkiraan')->with('warning', 'Gagal simpan karena nama rekening sudah ada atau periode keuangan kosong');
        else:
            return redirect('perkiraan')->with('info', 'Rekening Berhasil disimpan');
        endif;
    }

    public function show(Request $request, $kode)
    {
        $perkiraan=Perkiraan::find($kode);
        return $this->view("show",['perkiraan' => $perkiraan]);
    }

    public function edit(Request $request, $kode)
    {
        $perkiraan=Perkiraan::find($kode);
        $fungsi = Fungsi::select('id', 'nama_fungsi')->get();
        $kira = Perkiraan::select('id', 'nama')->get();
        $kategori = KategoriPerkiraan::select('id', 'nama')->get();
        $id_user = Auth::user()->id;
        $cabanguser = CabangUser::select('id')->where('id_user', $id_user)->first();
        $periodeKeuangan = PeriodeKeuangan::select('id')->where('status_aktif', 'Y')->first();
        return $this->view( "form", compact('perkiraan', 'fungsi', 'kira', 'kategori','id_user', 'cabanguser', 'periodeKeuangan') );
    }

    public function update(Request $request, $kode)
    {
        $perkiraan=Perkiraan::find($kode);

        if($request->isXmlHttpRequest()):
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Perkiraan::validationRules($request->name));
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $perkiraan->update($data);
                return "Record updated";
        endif;
        
        $saldo_awall = str_replace('.', '', $request->saldo_awal);
        $saldo_awal = str_replace(',', '.', $saldo_awall);
        $cek = Perkiraan::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
        ->where('nama', $request->nama)
        ->where('id', '<>', $kode)
        ->first();

        if ($cek->status == 'Tidak Ada'):
            $this->validate($request, Perkiraan::validationRules());
            if ($request->id_kategori == 1):
                $act = Perkiraan::where('id', $kode)->update([
                'type'=>$request->type,
                'id_induk'=>$request->id_induk,
                'kode'=>$request->kode,
                'level'=>$request->level,
                'nama'=>$request->nama,
                'fungsi'=>$request->fungsi,
                'id_kategori'=>$request->id_kategori,
                'debet'=>$saldo_awal,]);
            endif;

            if ($request->id_kategori == 2):
                $act = Perkiraan::where('id', $kode)->update([
                'type'=>$request->type,
                'id_induk'=>$request->id_induk,
                'kode'=>$request->kode,
                'level'=>$request->level,
                'nama'=>$request->nama,
                'fungsi'=>$request->fungsi,
                'id_kategori'=>$request->id_kategori,
                'kredit'=>$saldo_awal,]);
            endif;
        endif;

        if ($cek->status == 'Ada'):
            return redirect('perkiraan')->with('warning', 'Gagal update karena nama perkiraan sudah ada');
        else:
            return redirect('perkiraan')->with('info', 'Data Perkiraan Berhasil di update');
        endif;
    }

    public function destroy(Request $request, $kode)
    {
        $perkiraan=Perkiraan::find($kode);
        $act=false;

        try {
            $act=$perkiraan->forceDelete();
        }
        catch (\Exception $e) {
            $perkiraan=Perkiraan::find($perkiraan->pk());
            $act=$perkiraan->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = Perkiraan::selectRaw('perkiraan.id, perkiraan.kode_rekening, perkiraan.nama, fungsi.nama_fungsi AS fungsi, dua.nama AS induk,
        CASE 
        WHEN perkiraan.type=1 THEN "header" 
        WHEN perkiraan.type=2 THEN "detail"
        END AS tipe, perkiraan.flag_sistem as flag_sistem')
        ->leftJoin('fungsi', 'fungsi.id', 'perkiraan.fungsi')
        ->leftJoin('perkiraan as dua', 'dua.id', 'perkiraan.id_induk');

        if (request()->get('status') == 'trash') 
        {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor', function ($kategori) {
            return $GLOBALS['nomor']++;
        })->addColumn('type', function ($data) {
            
            if (isset($data->type)) 
            {
                return array('id' => $data->pk(), 'type' => $data->type);
            } else {
                return null;
            }
        
        })->addColumn('kode_induk', function ($data) {
            
            if ($data->id_induk > 0) 
            {
                return $data->induk->kode;
            } else {
                return null;
            }

        })
        /*->addColumn('flag_sistem', function($data){
                
            if (isset($data->flag_sistem)) 
            {
                return array ('id'=>$data->pk(), 'flag_sistem'=>$data->flag_sistem);
            } else {
                return null;
            }
        })*/
        ->addColumn('action', function ($data) {
            
            $edit = url("perkiraan/" . $data->pk()) . "/edit";
            $delete = url("perkiraan/" . $data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
