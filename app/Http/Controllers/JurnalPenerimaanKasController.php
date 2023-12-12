<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\MutasiKas;
use App\Jurnal;
use App\DetailJurnal;
use App\Models\TipeJurnal;
use App\transaksi;
use App\ArusKas;
use App\Models\Perkiraan;
use App\Models\KasBank;
use App\Http\Controllers\Controller;
use App\Voucher;
use Datatables;

// controller penerimaan kas
class JurnalPenerimaanKasController extends Controller
{
    public $viewDir = "jurnal-penerimaan-kas";
    public $breadcrumbs = array(
        'permissions' => array('title' => 'jurnal-penerimaan-kas', 'link' => "#", 'active' => false, 'display' => true),
    );

    public function __construct()
    {
        $this->middleware('permission:read-jurnal-pengeluaran-kas');
    }

    public function index()
    {
        return $this->view("index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $ArusKas = ArusKas::where('tipe', 1)->where('jenis', 1)->get();
        $KasBank = KasBank::select('id', 'nama')->get();
        $Perkiraan = Perkiraan::select('id', 'nama')->get();
        $kode = MutasiKas::selectRaw('CONCAT("BKK-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', '%BKK%')->orderByDesc('id')->first();

        return $this->view("form", ['MutasiKas' => new MutasiKas])->with('kode', $kode)->with('ArusKas', $ArusKas)->with('KasBank', $KasBank)->with('Perkiraan', $Perkiraan);
    }

    public function createJurnal($tgl)
    {
        $tipe_jurnal = TipeJurnal::where('kode_jurnal', 'CRJ')->first(); //untuk mendapatan id data jurnal Cash Dishburtment Journal
        $kode = Jurnal::selectRaw('CONCAT("CRJ-", SUBSTR(kode_jurnal, 5)+1) AS kode')->where('kode_jurnal', 'like', 'CRJ%')->orderByDesc('id')->first();

        if ($kode == null) {
            $kode = (object) array("kode"=>"CRJ-1");
        }
        $kredit = MutasiKas::selectRaw
        ('perkiraan.id, unit.id as id_unit, perkiraan.kode_rekening as kode, code_cost_centre, concat(unit.nama,"", "-", "", perkiraan.nama) as rekening,
        0 as debit, sum(mutasi_kas.nominal) as kredit')
        ->leftJoin('arus_kas', 'arus_kas.id', 'mutasi_kas.id_arus_kas')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kas_bank.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas.id_unit')
        ->whereNotNull('mutasi_kas.id_perkiraan')
        ->where('mutasi_kas.tipe', 2)
        ->where('mutasi_kas.ref', 'N')
        ->where('mutasi_kas.tanggal', $tgl)
        ->groupBy('perkiraan.id');

        $data = MutasiKas::selectRaw
        ('perkiraan.id, unit.id as id_unit, perkiraan.kode_rekening as kode, code_cost_centre, concat(unit.nama, "-", perkiraan.nama) as rekening,
        sum(mutasi_kas.nominal) as debit, 0 as kredit')
        ->leftJoin('arus_kas', 'arus_kas.id', 'mutasi_kas.id_arus_kas')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kas_bank.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas.id_unit')
        ->whereNotNull('mutasi_kas.id_perkiraan')
        ->where('mutasi_kas.tipe', 2)
        ->where('mutasi_kas.ref', 'N')
        ->where('mutasi_kas.tanggal', $tgl)
        ->groupBy('perkiraan.id')
        ->union($kredit)
        ->get();

        $tanggal = date('dmY');
        $dokumen = Jurnal::selectRaw('substr(no_dokumen, 10) +1 as no_dokumen')->where('no_dokumen', '<>', 'null')->orderByDesc('id')->first();
        $no_dokumen = $dokumen ? $tanggal.'-'.$dokumen->no_dokumen : $tanggal."-1";
        // dd( $data->toSql());
        $data_json = response()->json($data)->getContent();
        return $this->view("form-jurnal", [
            "tgl" => $tgl,
            "kode" => $kode,
            "no_dokumen"=>$no_dokumen,
            "tipe_jurnal" => $tipe_jurnal,
            "data" => $data,
            "tgl_mutasi_kas" => $tgl,
        ]);
    }

    public function getId($id_induk = 0)
    {
        $subArus['data'] = ArusKas::orderBy('nama', 'asc')->select('id', 'nama')->where('id_induk', $id_induk)->where('jenis', 1)->get();
        return response()->json($subArus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, MutasiKas::validationRules());

        $act = MutasiKas::create($request->except('user_update'));
        message($act, 'Data Mutasi Kas berhasil ditambahkan', 'Data Mutasi Kas gagal ditambahkan');
        return redirect('/pengeluaran-kas');
    }

    public function storeJurnal(Request $request)
    {
       // if (empty($request->id_perkiraan)) {
         //   message(false, '', 'Tidak dapat input data perkiraan kosong!');
           // return redirect('/jurnal-penerimaan-kas/create-jurnal/'.$request->tgl_mutasi_kas);
        //}

        $id_user = Auth::user()->id;
        $data = $request->all();
        DB::beginTransaction();

        try {
            // insert jurnal
            $jurnal = new Jurnal;
            $jurnal->kode_jurnal        = $request->kode_jurnal;
            $jurnal->tanggal_posting    = $request->tanggal;
            $jurnal->keterangan         = $request->keterangan;
            $jurnal->id_tipe_jurnal     = $request->id_tipe_jurnal;
            $jurnal->id_user            = $id_user;
            $jurnal->no_dokumen         = $request->no_dokumen;
            $jurnal->save();

            $id_jurnal = $jurnal->id;


            for ($i=0; $i<count($data['id_perkiraan']); $i++)
                {
                    $debet = str_replace('.', '', $data['debet'][$i]);
                    $kredit = str_replace('.', '', $data['kredit'][$i]);

				    $insert = array (
					    'id_jurnal'=>$id_jurnal,
					    'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'id_unit'=>$data['id_unit'][$i],
					    'debet'=>$debet,
					    'kredit'=>$kredit,
					    'ref'=>'N',);

				    DetailJurnal::create($insert);

                   // transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet',  $debet);
                   // transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit', $kredit);  // update table transaksi

                    MutasiKas::where('tanggal', $request->tgl_mutasi_kas)
                    ->where('id_perkiraan', $data['id_perkiraan'])
                    ->where('ref', '<>', 'Y')
                    ->update([
                        'ref' => 'Y',
                        'no_jurnal' => $id_jurnal
                    ]);
                }

                //update mutasikas

            // insert kode voucher
            $dateCode = 'KD.' . date('Ymd') . '.';
            $code = Voucher::selectRaw("CONCAT('$dateCode', SUBSTR(kode, 13)+1) AS kode")->where('kode', 'like', "$dateCode%")->orderByDesc('kode')->first();
            $newCode = $code ? $code->kode : $dateCode . '1';
            // dd($newCode);
            Voucher::insert([
                'id_jurnal'=> $id_jurnal,
                'kode'     => $newCode,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        message($jurnal, 'Jurnal Penerimaan Kas berhasil disimpan', 'Jurnal Penerimaan Kas gagal disimpan');
        return redirect('/jurnal-penerimaan-kas');
    }

    /**
     * Display the specified resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, $kode)
    {
        $MutasiKas = MutasiKas::find($kode);
        return $this->view("show", ['MutasiKas' => $MutasiKas]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, $kode)
    {
        $MutasiKas = MutasiKas::find($kode);
        $ArusKas = ArusKas::where('tipe', 1)->get();
        $KasBank = KasBank::all();
        $Perkiraan = Perkiraan::all();
        $kode = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', '%BKM%')->orderByDesc('id')->first();

        return $this->view("form", ['MutasiKas' => $MutasiKas])->with('kode', $kode)->with('ArusKas', $ArusKas)->with('KasBank', $KasBank)->with('Perkiraan', $Perkiraan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        $MutasiKas = MutasiKas::find($kode);
        if ($request->isXmlHttpRequest()) {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make($data, MutasiKas::validationRules($request->name));
            if ($validator->fails())
                return response($validator->errors()->first($request->name), 403);
            $MutasiKas->update($data);
            return "Record updated";
        }
        $this->validate($request, MutasiKas::validationRules());

        $act = $MutasiKas->update($request->except('user_input', 'ref', 'no_jurnal'));
        message($act, 'Data Mutasi Kas berhasil diupdate', 'Data Mutasi Kas gagal diupdate');

        return redirect('/jurnal-penerimaan-kas');
        // return $this->view("index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Request $request, $kode)
    {
        $MutasiKas = MutasiKas::find($kode);
        $act = false;
        try {
            $act = $MutasiKas->forceDelete();
        } catch (\Exception $e) {
            $MutasiKas = MutasiKas::find($MutasiKas->pk());
            $act = $MutasiKas->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }
    public function loadData()
    {
        $startDate  = \Request::input('startDate');
        $endDate    = \Request::input('endDate');

        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = MutasiKas::select('mutasi_kas.id', 'mutasi_kas.tanggal', 'mutasi_kas.kode', 'mutasi_kas.keterangan', 'perkiraan.nama as perkiraan', 'kas_bank.nama as kas_bank', 'nominal')
            ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
            ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
            ->whereNotNull('mutasi_kas.id_perkiraan')
            ->where('tipe', 2)
            ->where('ref', 'N');

        if ($startDate && $endDate) {
            $dataList->whereBetween('tanggal', [dbDate($startDate), dbDate($endDate)]);
        } else if ($startDate && !$endDate) {
            $dataList->where('tanggal', dbDate($startDate));
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)
            ->addColumn('nomor', function ($kategori) {
                return $GLOBALS['nomor']++;
            })
            ->addColumn('action', function ($data) {
                $edit = url("jurnal-penerimaan-kas/" . $data->pk()) . "/edit";
                $delete = url("jurnal-penerimaan-kas/" . $data->pk());
                $content = '';
                $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                return $content;
            })
            ->make(true);
    }
}
