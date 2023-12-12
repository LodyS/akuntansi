<?php
namespace App\Http\Controllers;

use App\ArusKas;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Visit;
use DB;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\KasBank;
use App\Models\MutasiKas;
use Datatables;
use Auth;

class DepositController extends Controller
{
    public $viewDir = "deposit";
    public $breadcrumbs = array('permissions'=>array('title'=>'Deposit','link'=>"#",'active'=>false,'display'=>true),);
    public function __construct()
    {
        $this->middleware('permission:read-deposit');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $deposit = new Deposit;
        $bank = KasBank::select('id', 'nama')->get();
        //$id_arus_kas = ArusKas::where('nama','Kas dari pendapatan jasa')->max('id');

        return $this->view("form", compact('deposit', 'bank'));
    }

    public function store(Request $request)
    {
        $this->validate($request, Deposit::validationRules());

        try {
            DB::beginTransaction();

            $kreditt = str_replace('.', '', $request->kredit);
            $kredit = str_replace(',', '.', $kreditt);

            $deposit = new Deposit;
            $deposit->status = $request->status;
            $deposit->ref = $request->ref;
            $deposit->id_pelanggan = $request->id_pelanggan;
            $deposit->id_visit = $request->id_visit;
            $deposit->waktu = $request->waktu;
            $deposit->kredit = $kredit;
            $deposit->flag_ak = 'Y';
            $deposit->save();

            $lastKode = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', 'BKM%')->orderByDesc('id')->first();
            $kode = $lastKode->kode ?: 'BKM-1' ;
            $id_arus_kas = ArusKas::where('nama','Kas dari pendapatan jasa')->first();
            $idArusKas = isset($id_arus_kas) ? $id_arus_kas->id : null;

            $mutasiKas = new MutasiKas;
            $mutasiKas->kode = $kode;
            $mutasiKas->id_arus_kas = $idArusKas;
            $mutasiKas->tanggal = $request->waktu;
            $mutasiKas->id_kas_bank = $request->id_bank;
            $mutasiKas->id_deposit = $deposit->id;
            $mutasiKas->nominal = $kredit;
            $mutasiKas->tipe = 2;
            $mutasiKas->user_input = Auth::user()->id;
            $mutasiKas->save();

            Pelanggan::where('id', $request->id_pelanggan)->increment('deposit', $kredit);

            DB::commit();
            message(true, 'Data Deposit berhasil ditambahkan', 'Data Deposit gagal ditambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            message(false, 'Data Deposit berhasil ditambahkan', 'Data Deposit gagal ditambahkan');
            throw $th;
        }
        return redirect('deposit');
    }

    public function isiPasienDeposit ($id_pelanggan)
    {
        $visit = Visit::select('visit.id as id_visit','pelanggan.nama as nama_pasien', 'visit.flag_discharge')
        ->join('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
        ->where('visit.id_pelanggan', $id_pelanggan)
        ->where('visit.waktu',date('Y-m-d'))
        ->latest('visit.id')
        ->first();

        return response()->json($visit);
    }

    public function show(Request $request, $kode)
    {
        $deposit=Deposit::find($kode);
        return $this->view("show",['deposit' => $deposit]);
    }

    public function edit(Request $request, $kode)
    {
        $deposit=Deposit::select('id_pelanggan', 'id_visit', 'kredit', 'pelanggan.nama as nama_pasien', DB::raw('date(waktu) as waktu'))
        ->join('pelanggan', 'pelanggan.id', 'deposit.id_pelanggan')
        ->where('deposit.id', $kode)
        ->firstOrFail();

        return $this->view( "form", ['deposit' => $deposit] );
    }

    public function update(Request $request, $kode)
    {
            //$deposit=Deposit::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, Deposit::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $deposit->update($data);
                return "Record updated";
            }

        $this->validate($request, Deposit::validationRules());
        $kredit = str_replace('.', '', $request->kredit);
        $act= Deposit::where('id', $kode)->update(['kredit'=>$kredit]);

        message($act,'Data Deposit berhasil diupdate','Data Deposit gagal diupdate');

        return redirect('/deposit');
    }

    public function editDeposit(Request $request)
    {
        $deposit=Deposit::select('deposit.id','id_pelanggan', 'id_visit', 'kredit', 'pelanggan.nama as nama_pasien', 'waktu')
        ->join('pelanggan', 'pelanggan.id', 'deposit.id_pelanggan')
        ->where('deposit.id', $request->id)
        ->firstOrFail();

        return $this->view('edit-deposit', ['deposit'=>$deposit]);
    }

    public function updateDeposit (Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = $request->validate([
                'waktu'=>'required',
                'kredit'=>'required',
            ]);

            $kreditt = str_replace('.', '', $request->kredit);
            $kredit = str_replace(',', '.', $kreditt);
            $act= Deposit::where('id', $request->id)->update(['kredit'=>$kredit,]);

            DB::commit();
            message($act, 'Deposit berhasil di update', '');
            return redirect ('/deposit');

        } catch (Exception $e){
            DB::rollback();
            message(false, '', 'Error system');
            return back();
        }
    }

    public function destroy(Request $request, $kode)
    {
        $deposit=Deposit::find($kode);
        $act=false;
        try {
            $act=$deposit->forceDelete();
        } catch (\Exception $e) {
            $deposit=Deposit::find($deposit->pk());
            $act=$deposit->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $no_kunjungan = request()->get('no_kunjungan');
        $nama = request()->get('nama');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = Deposit::selectRaw("deposit.id, id_visit, pelanggan.nama as nama_pasien, waktu, kredit, case
        when status ='1' then 'Belum Dipakai'
        when status ='2' then 'Belum Lunas'
        when status ='3' then 'Lunas' end as status")
        ->leftJoin('pelanggan', 'pelanggan.id', 'deposit.id_pelanggan');

        if ($no_kunjungan){
            $dataList->where('id_visit', 'like', $no_kunjungan.'%');
        }

        if ($nama){
            $dataList->where('pelanggan.nama', 'like', $nama.'%');
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })
        ->addColumn('waktu', function($data){

            if (isset($data->waktu)){
                return date('d-M-Y', strtotime($data->waktu));
            }
        })
        ->addColumn('kredit', function ($data) {

            if (isset($data->kredit)){
                $kredit = nominalTitik($data->kredit);
                return $kredit;
            } else {
                return 0;
            }
        })
        ->addColumn('action', function ($data) {
            $edit=url("deposit/".$data->pk())."/edit";
            $delete=url("deposit/".$data->pk());
            $content = '';
            $content .= "<a href='deposit/edit-deposit/".$data->pk()."' class='btn btn-warning btn-sm'>Edit</a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })
        ->make(true);
    }
}
