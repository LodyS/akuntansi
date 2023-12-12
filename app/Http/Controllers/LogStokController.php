<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\LogStok;
use Illuminate\Http\Request;
use App\Stok;
use DB;
use App\Models\Barang;
use App\JenisTransaksi;
use App\Models\PackingBarang;
use App\Models\Unit;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class LogStokController extends Controller
{
    public $viewDir = "log_stok";
    public $breadcrumbs = array('permissions'=>array('title'=>'Log-stok','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-log-stok');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function barcode ($barcode)
    {    
        $data = Stok::selectRaw('stok.id AS id_stok, jumlah_stok, barang.nama AS barang, id_packing_barang')
        ->leftJoin('packing_barang', 'packing_barang.id', 'stok.id_packing_barang')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->where('barcode', $barcode)
        ->orderByDesc('stok.id')
        ->first();

        echo json_encode($data);
        exit;
    }

    public function cari (Request $request)
    {
        if ($request->barcode == null)
        {
            message('false', 'Barcode Kosong', 'Barcode Kosong');
            return redirect('/log-stok');
        }

        if (isset($request->barcode))
        {
            $data = DB::table('log_stok')
            ->selectRaw('barcode, kategori_barang.nama AS kategori_barang, sub_kategori_barang.nama as sub_kategori_barang,
            hna, hpp, barang.nama as persediaan, unit.nama as departemen, satuan, jumlah_stok')
            ->leftJoin('stok', 'stok.id', 'log_stok.id_stok')
            ->leftJoin('unit', 'unit.id', 'stok.id_unit')
            ->leftJoin('packing_barang', 'packing_barang.id', 'stok.id_packing_barang')
            ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
            ->leftJoin('sub_kategori_barang', 'sub_kategori_barang.id', 'barang.id_sub_kategori_barang')
            ->leftJoin('kategori_barang', 'kategori_barang.id', 'sub_kategori_barang.id_kategori_barang')
            ->where('barcode', $request->barcode)
            ->get();
        }

        return $this->view('rekapitulasi', compact('data'));
    }

    public function create()
    {
        $jenisTransaksi = JenisTransaksi::where('nama', 'Saldo Awal')->first();
        $unit = Unit::select('id', 'nama')->get();
        $logStok = new LogStok;

        return $this->view("form", compact('logStok', 'jenisTransaksi', 'unit'));
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $hnaa = str_replace('.', '', $request->hna);
            $hna = str_replace(',', '.', $hnaa);
            $this->validate($request, LogStok::validationRules());
            $stok = new Stok;
            $stok->id_packing_barang = $request->id_packing_barang;
            $stok->id_unit = $request->id_unit;
            $stok->hpp = $request->hpp;
            $stok->hna = $hna;
            $stok->jumlah_stok = $request->stok_akhir;
            $stok->save();

            $id_stok = $stok->id;

            $act = new LogStok;
            $act->id_stok = $id_stok;
            $act->waktu = $request->waktu;
            $act->stok_awal = $request->stok_awal;
            $act->selisih = $request->selisih;
            $act->stok_akhir = $request->stok_akhir;
            $act->id_transaksi = $request->id_transaksi;
            $act->user_input = $request->user_input;
            $act->save();
            DB::commit();
            message($act,'Data Log Stok berhasil ditambahkan','Data Log Stok gagal ditambahkan');
            return redirect('log-stok');
        } catch (Exception $e){
            DB::rollback();
            message(false,'Data Log Stok berhasil ditambahkan','Data Log Stok gagal ditambahkan');
            return redirect('log-stok');
        }
    }

    public function show(Request $request, $kode)
    {
        $logStok=LogStok::find($kode);
        return $this->view("show",['logStok' => $logStok]);
    }

    public function edit(Request $request, $kode)
    {
        $jenisTransaksi = JenisTransaksi::where('nama', 'Saldo Awal')->first();
        $unit = Unit::select('id', 'nama')->get();
        $logStok=LogStok::selectRaw('log_stok.id, id_stok, waktu, stok_awal, stok.id_packing_barang,
        selisih, stok_akhir, hna, hpp, id_unit, waktu, barcode, barang.nama as barang, stok_awal')
        ->leftJoin('stok', 'stok.id', 'log_stok.id_stok')
        ->leftJoin('packing_barang', 'packing_barang.id', 'stok.id_packing_barang')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->where('log_stok.id', $kode)
        ->first();
         
        return $this->view( "form", compact('logStok', 'jenisTransaksi', 'unit'));
    }

    public function update(Request $request, $kode)
    {
        $logStok=LogStok::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, LogStok::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
            $logStok->update($data);
            return "Record updated";
        }
        $this->validate($request, LogStok::validationRules());
        $hnaa = str_replace('.', '', $request->hna);
        $hna = str_replace(',', '.', $hnaa);
        $act = LogStok::where('id', $kode)->update(['selisih'=>$request->selisih, 'stok_akhir'=>$request->stok_akhir]);
        Stok::where('id', $request->id_stok)->update([
            'id_unit'=>$request->id_unit, 
            'hpp'=>$request->hpp, 
            'hna'=>$hna]);

        message($act,'Data Log Stok berhasil diupdate','Data Log Stok gagal diupdate');

        return redirect('/log-stok');
    }

    public function destroy(Request $request, $kode)
    {
        $logStok=LogStok::find($kode);
        $act=false;
        try {
            $act=$logStok->forceDelete();
        } catch (\Exception $e) {
            $logStok=LogStok::find($logStok->pk());
            $act=$logStok->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
        
    public function loadData()
    {
        $barcode = request()->get('barcode');
        $kategori = request()->get('kategori');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = LogStok::selectRaw('log_stok.id, waktu, barcode, kategori_barang.nama AS kategori_barang, perkiraan.nama AS perkiraan, 
        stok_akhir, hna, hpp')
        ->leftJoin('stok', 'stok.id', 'log_stok.id_stok')
        ->leftJoin('packing_barang', 'packing_barang.id', 'stok.id_packing_barang')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->leftJoin('sub_kategori_barang', 'sub_kategori_barang.id', 'barang.id_sub_kategori_barang')
        ->leftJoin('kategori_barang', 'kategori_barang.id', 'sub_kategori_barang.id_kategori_barang')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kategori_barang.id_perkiraan');

        if($barcode){
            $dataList->where('barcode', 'like', $barcode.'%');
        }

        if ($kategori){
            $dataList->where('kategori_barang.nama', 'like', $kategori.'%');
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('waktu', function($data){
                
            if (isset($data->waktu)){
                return date('d-M-Y', strtotime($data->waktu));
            }
        })->addColumn('hna', function($data){
                
            if (isset($data->hna)){
                $hna = nominalTitik($data->hna);
                return $hna;
            }
        })->addColumn('action', function ($data) {
                   
            $edit=url("log-stok/".$data->pk())."/edit";
            $delete=url("log-stok/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row' 
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' 
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}
