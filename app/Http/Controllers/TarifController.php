<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use DB;
use Auth;
use Carbon\Carbon;

use App\tarif as Tarif;
use App\kelas as Kelas;
use App\Layanan;
use App\SettingCoa;

class TarifController extends Controller
{
    public $viewDir = "tarif";

    public function __construct()
    {
        $this->middleware('permission:read-tarif');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view("index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastId = Tarif::select("id")->orderBy('id', 'DESC')->first();
        $kelas = Kelas::all();
        $layanan = Layanan::all();
        return $this->view("form", ['tarif' => new Tarif, 'id' => is_null($lastId) ? 1 : $lastId->id + 1, 'kelas' => $kelas, 'layanan' => $layanan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        // dd($request->except('_token'));
        $this->validate($request, Tarif::validationRules());

        DB::beginTransaction();
        try {
            //code...
            Tarif::create($request->except('_token'));
            SettingCoa::insert([
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RI', 'type_bayar'=>'KREDIT', 'tipe_pasien'=>1, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RI', 'type_bayar'=>'KREDIT', 'tipe_pasien'=>2, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RI', 'type_bayar'=>'KREDIT', 'tipe_pasien'=>3, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RI', 'type_bayar'=>'TUNAI',  'tipe_pasien'=>2, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RJ', 'type_bayar'=>'KREDIT', 'tipe_pasien'=>1, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RJ', 'type_bayar'=>'KREDIT', 'tipe_pasien'=>2, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RJ', 'type_bayar'=>'KREDIT', 'tipe_pasien'=>3, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
                ['keterangan'=>'Pendapatan Jasa', 'id_tarif'=>$request->id, 'type'=>'RJ', 'type_bayar'=>'TUNAI',  'tipe_pasien'=>2, 'id_kelas'=>$request->id_kelas, 'id_perkiraan'=>27, 'user_input'=>Auth::user()->id,'created_at'=>Carbon::now()],
            ]);
            DB::commit();
            message(true, 'Data Tarif berhasil ditambahkan', 'Data Tarif gagal ditambahkan');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            // echo $th->getMessage();
            message(false, 'Data Tarif berhasil ditambahkan', 'Data Tarif gagal ditambahkan! <br>'.$th->getMessage ());
        }
        return redirect('tarif');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }
    public function loadData()
    {
        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = Tarif::selectRaw('*')->with(['kelas', 'layanan']);

        // return response()->json($dataList);

        return Datatables::of($dataList)
            ->addColumn('nomor', function () {
                return $GLOBALS['nomor']++;
            })

            ->make(true);
    }
}
