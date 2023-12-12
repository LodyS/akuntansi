<?php
namespace App\Http\Controllers;

use App\Models\MutasiKas;
use App\ArusKas;
use Illuminate\Http\Request;
use DB;
use App\Models\Perkiraan;
use App\Models\KasBank;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

// controller penerimaan kas
class MutasiKasController extends Controller
{
    public $viewDir = "mutasi_kas";
    public $breadcrumbs = array('permissions'=>array('title'=>'mutasi-kas','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-mutasi-kas');
    }

    public function index()
    {
        $bank = KasBank::select('id', 'nama')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();

        return $this->view( "index", compact('bank', 'perkiraan'));
    }

    public function create()
    {
        $aksi ="create";
        $MutasiKas = new MutasiKas;
        $unit = DB::table('unit')->select('id', 'nama', 'code_cost_centre')->get();
        $ArusKas = ArusKas::where('tipe', 1)->get();
        $Arus = ArusKas::select('id', 'nama')->get();
        $KasBank = DB::table('kas_bank')->select('id', 'nama')->get();
        $Perkiraan = DB::table('perkiraan')->select('id', 'nama')->get();
        $kode_bkm = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', 'BKM%')->orderByDesc('id')->first();

        return $this->view("form", compact('MutasiKas', 'Arus', 'kode_bkm', 'ArusKas', 'KasBank', 'Perkiraan', 'aksi', 'unit'));
    }

    public function getId ($id_pembayaran=0)
    {
        $subArus['data'] = ArusKas::orderBy('nama', 'asc')
        ->select('id', 'nama')
        ->where('id_induk', $id_pembayaran)
        ->where('jenis',1)
        ->get();

        echo json_encode($subArus);
        exit;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            if (is_null($request->file('file')))
            {
                $nominall = str_replace('.', '', $request->nominal);
                $nominal = str_replace(',', '.', $nominall);

                $act= new MutasiKas;
                $act->kode = $request->kode;
                $act->id_arus_kas = $request->id_arus_kas;
                $act->tanggal = $request->tanggal;
                $act->keterangan = $request->keterangan;
                $act->id_perkiraan = $request->id_perkiraan;
                $act->id_kas_bank = $request->id_kas_bank;
                $act->nominal = $nominal;
                $act->user_input = $request->user_input;
                $act->ref = $request->ref;
                $act->tipe = $request->tipe;
                $act->id_unit = $request->id_unit;
                $act->status = 'N';
                $act->save();

            } else if ($request->file('file')->getClientOriginalExtension() === 'jpg' || $request->file('file')->getClientOriginalExtension() === 'png') {

                $nominall = str_replace('.', '', $request->nominal);
                $nominal = str_replace(',', '.', $nominall);
                $tambahFile = $request->file('file');
    	        $file = time()."_".$tambahFile->getClientOriginalName();
    	        $folder = 'lampiran';
                $tambahFile->move($folder, $file);

                $act= new MutasiKas;
                $act->kode = $request->kode;
                $act->id_arus_kas = $request->id_arus_kas;
                $act->tanggal = $request->tanggal;
                $act->keterangan = $request->keterangan;
                $act->id_perkiraan = $request->id_perkiraan;
                $act->id_kas_bank = $request->id_kas_bank;
                $act->nominal = $nominal;
                $act->user_input = $request->user_input;
                $act->ref = $request->ref;
                $act->id_unit = $request->id_unit;
                $act->tipe = $request->tipe;
                $act->file = $file;
                $act->nama_file = $request->nama_file;
                $act->status = 'N';
                $act->save();
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }

        if (is_null($request->file('file')))
        {
            return redirect('mutasi-kas')->with('info', 'Pemasukan Kas berhasil disimpan');
        } else if ($request->file('file')->getClientOriginalExtension() === 'jpg' || $request->file('file')->getClientOriginalExtension() === 'png') {
            return redirect('mutasi-kas')->with('info', 'Pemasukan Kas berhasil disimpan');
        } else {
            return redirect('mutasi-kas')->with('warning', 'Pemasukan Kas gagal disimpan karena format file bukan PNG atau JPG');
        }
    }

    public function show(Request $request, $kode)
    {
        $MutasiKas=MutasiKas::find($kode);
        return $this->view("show",['MutasiKas' => $MutasiKas]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi = 'update';
        $MutasiKas = MutasiKas::SelectRaw('mutasi_kas.id, kode, id_arus_kas,tanggal, id_perkiraan, id_kas_bank,
        id_unit, id_induk, nominal, keterangan, id_pembayaran, file, nama_file')
        ->leftJoin('arus_kas', 'arus_kas.id', 'mutasi_kas.id_arus_kas')
        ->where('mutasi_kas.id', $kode)
        ->first();

        $Arus = ArusKas::select('id', 'nama')->get();
        $ArusKas = ArusKas::select('id', 'nama')->where('tipe', 1)->get();
        $KasBank = KasBank::select('id', 'nama')->get();
        $Perkiraan = Perkiraan::select('id', 'nama')->get();
        $unit = DB::table('unit')->select('id', 'nama', 'code_cost_centre')->get();
        $kode_bkm = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', 'BKM%')->orderByDesc('id')->first();

        return $this->view( "form", compact('MutasiKas', 'kode_bkm', 'ArusKas','KasBank', 'Perkiraan', 'Arus', 'aksi', 'unit'));
    }

    public function update(Request $request)
    {
        $cek = MutasiKas::select('file', 'nama_file')->where('id', $request->id)->first();
        $nominall = str_replace('.', '', $request->nominal);
        $nominal = str_replace(',', '.', $nominall);

        if ($request->file_cek == $cek->file && is_null($request->file('file'))) {

            $act= MutasiKas::where('id', $request->id)->update([
                'id_arus_kas'=>$request->id_arus_kas,
                'keterangan'=>$request->keterangan,
                'id_kas_bank'=>$request->id_kas_bank,
                'id_perkiraan'=>$request->id_perkiraan,
                'id_unit' =>$request->id_unit,
                'nominal'=>$nominal,
                'user_update'=>$request->user_update,]);

            return redirect('mutasi-kas')->with('info', 'Pemasukan Kas berhasil di update tapi tidak merubah file bukti pembayaran');

        } else if (is_null($request->file('file')) && $request->file_cek == null) {

            $act= MutasiKas::where('id', $request->id)->update([
                'id_arus_kas'=>$request->id_arus_kas,
                'keterangan'=>$request->keterangan,
                'id_kas_bank'=>$request->id_kas_bank,
                'id_perkiraan'=>$request->id_perkiraan,
                'id_unit' =>$request->id_unit,
                'nominal'=>$nominal,
                'user_update'=>$request->user_update,]);

            return redirect('mutasi-kas')->with('info', 'Pemasukan Kas berhasil di update dan file bukti pembayaran asli kosong');

        } else if ($request->file('file')->getClientOriginalExtension() === 'jpg' || $request->file('file')->getClientOriginalExtension() === 'png') {

            $tambahFile = $request->file('file');
    	    $file = time()."_".$tambahFile->getClientOriginalName();
    	    $folder = 'lampiran';
            $tambahFile->move($folder, $file);

            $act= MutasiKas::where('id', $request->id)->update([
                'id_arus_kas'=>$request->id_arus_kas,
                'keterangan'=>$request->keterangan,
                'id_kas_bank'=>$request->id_kas_bank,
                'id_perkiraan'=>$request->id_perkiraan,
                'nominal'=>$nominal,
                'id_unit' =>$request->id_unit,
                'user_update'=>$request->user_update,
                'file'=>$file,
                'nama_file'=>$request->nama_file,]);

            return redirect('mutasi-kas')->with('info', 'Pemasukan Kas berhasil di update');
        } else {

            $act= MutasiKas::where('id', $request->id)->update([
                'id_arus_kas'=>$request->id_arus_kas,
                'keterangan'=>$request->keterangan,
                'id_kas_bank'=>$request->id_kas_bank,
                'id_perkiraan'=>$request->id_perkiraan,
                'id_unit' =>$request->id_unit,
                'nominal'=>$nominal,
                'user_update'=>$request->user_update,]);

            return redirect('mutasi-kas')->with('warning', 'Pemasukan Kas berhasil di update tapi format file bukti pembayaran bukan PNG atau JPG');
        }
    }

    public function lihatBukti(Request $request)
    {
        $bukti = MutasiKas::selectRaw
        ('mutasi_kas.kode, mutasi_kas.nominal, mutasi_kas.keterangan, mutasi_kas.tanggal, users.name,
        perusahaan.kota, perusahaan.nama_badan_usaha, perusahaan.alamat_perusahaan')
        ->leftJoin('users', 'users.id', 'mutasi_kas.user_input', 'mutasi_kas.nominal')
        ->leftJoin('cabang_user', 'cabang_user.id_user', 'users.id')
        ->leftJoin('perusahaan', 'perusahaan.id', 'cabang_user.id_perusahaan')
        ->where('mutasi_kas.id', $request->id)
        ->firstOrFail();

        return $this->view('lihat-bukti', ['bukti'=>$bukti]);
    }

    public function lihatBuktiAsli (Request $request)
    {
        $bukti = MutasiKas::select('file', 'nama_file', 'tanggal', 'keterangan', 'nominal')->where('id', $request->id)->firstOrFail();

        return $this->view('lihat-bukti-asli', ['bukti'=>$bukti]);
    }

    public function destroy(Request $request, $kode)
    {
        $MutasiKas=MutasiKas::find($kode);

        if ($MutasiKas && $MutasiKas->ref == 'Y') {
            $response = [
                'code' => 200,
                'message' => 'Data tidak dapat dihapus karena sudah dijurnalkan !'
            ];
            return response()->json(['error' => $response]);
        }

        $act=false;

        try {
            $act=$MutasiKas->forceDelete();

        } catch (\Exception $e) {

            $MutasiKas=MutasiKas::find($MutasiKas->pk());
            $act=$MutasiKas->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $startDate = request()->get('tanggal_awal');
        $endDate = request()->get('tanggal_akhir');
        $id_bank = request()->get('id_bank');
        $id_perkiraan = request()->get('id_perkiraan');

        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal,
        perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, nominal, case when status ="N" then "Belum Dijurnal"
        when status ="Y" then "Sudah Dijurnal" end as status')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->where('tipe',2);

        if ($id_bank) {
            $dataList->where('kas_bank.id', $id_bank);
        }

        if ($startDate && $endDate) {
            $dataList->whereBetween('mutasi_kas.tanggal', [$startDate,$endDate]);
        }

        if ($id_perkiraan)
        {
            $dataList->where('mutasi_kas.id_perkiraan', $id_perkiraan);
        }

        if (request()->get('status') == 'trash')
        {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;

        })->addColumn('no_jurnal', function($data){

            if(isset($data->no_jurnal)){
                return array ('id'=>$data->pk(), 'no_jurnal'=>$data->no_jurnal);

            } else {
                return null;
            }

        })->addColumn('nominal', function ($data) {

            if (isset($data->nominal)){
                $nominal = nominalTitik($data->nominal);
                return $nominal;
            } else {
                return 0;
            }
        })->addColumn('action', function ($data) {

            $edit=url("mutasi-kas/".$data->pk())."/edit";
            $delete=url("mutasi-kas/".$data->pk());
            $content = '';
            $content .= "<a href='mutasi-kas/lihat-bukti/".$data->pk()."' class='btn btn-outline-succes btn-sm'>Lihat Bukti</a>";
            $content .= "<a href='mutasi-kas/lihat-bukti-asli/".$data->pk()."' class='btn btn-outline-succes btn-sm'>Lihat Bukti Asli</a>";
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
