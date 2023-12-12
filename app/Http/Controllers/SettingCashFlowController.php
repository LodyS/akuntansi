<?php

namespace App\Http\Controllers;
use DB;
use App\JenisTransaksi;
use App\Http\Requests\SettingCashFlowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingCashFlowController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-cash-flow');
    }

    public function index()
    {
        $data = DB::table('jenis_transaksi')
        ->selectRaw('jenis_transaksi.id, transaksi_jurnal.nama, jenis_transaksi.kode')
        ->selectRaw('jenis_transaksi.urutan, jenis_transaksi.level, tj1.nama AS induk')
        ->leftJoin('jenis_transaksi as jt1', 'jt1.id', 'jenis_transaksi.id_induk')
        ->leftJoin('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->leftJoin('transaksi_jurnal as tj1', 'tj1.id', 'jt1.id_transaksi_jurnal')
        ->paginate(100);

        return view('setting-cash-flow/index', compact('data'));
    }

    public function create ()
    {
        $aksi = "create";
        $transaksi = DB::table('transaksi_jurnal')
        ->whereNotIn('id', DB::table('jenis_transaksi')->select('id_transaksi_jurnal'))
        ->get(['id', 'nama']);

        $induk = DB::table('jenis_transaksi')
        ->join('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->get(['jenis_transaksi.id', 'transaksi_jurnal.nama']);

        $kodes = JenisTransaksi::select('kode')->whereNull('id_induk')->orderByDesc('id')->first();
        $kodeS = ($kodes) ? $kodes->kode+1 : 1;
        $jenisTransaksi = new JenisTransaksi;

        return view ('setting-cash-flow/form', compact('transaksi', 'aksi', 'kodeS', 'jenisTransaksi', 'induk'));
    }

    public function isi($induk)
    {
        $data = JenisTransaksi::select('kode', 'level')->where('id', $induk)->first();
        $jumlah = JenisTransaksi::selectRaw('id, urutan, level')->where('id_induk', $induk)->orderByDesc('id')->first();
        $master_level = JenisTransaksi::selectRaw('level')->where('id', $induk)->first();
        $master_urutan = JenisTransaksi::selectRaw('max(urutan) as urutan')->where('id_induk', $induk)->first();
        $max_kode = JenisTransaksi::selectRaw("CONCAT((SELECT kode FROM jenis_transaksi WHERE id='$induk' ),'.', '',
        MAX(SUBSTRING_INDEX(kode, '.',-1))+1) AS kode")
        ->where('id_induk', $induk)
        ->first(); // untuk mendapatkan kode jenis transaksi yang mungkin induknya ada titik untuk bisa berurutan

        $kode = (isset($jumlah->id)) ? $max_kode->kode : $data->kode.'.'.'1' ;
        $level = isset($master_level) ? $master_level->level +1 : '1';
        $urutan = (isset($jumlah->id)) ? $master_urutan->urutan + 1 : '1';

        return response()->json(['level'=>$level, 'urutan'=>$urutan, 'kode'=>$kode]);
    }

    public function store (SettingCashFlowRequest $request)
    {
        DB::beginTransaction();

        try {

            JenisTransaksi::create($request->except('id', 'tipe'));
            DB::commit();
            return redirect('setting-cash-flow/index')->with('success', 'Berhasil disimpan');

        } catch (Exception $e){
            DB::rollback();
            return redirect('setting-cash-flow/index')->with('danger', 'Gagal simpan');
        }
    }

    public function edit (Request $request)
    {
        $aksi = "update";
        $jenisTransaksi = JenisTransaksi::findOrFail($request->id);

        $induk = DB::table('jenis_transaksi')
        ->join('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->get(['jenis_transaksi.id', 'transaksi_jurnal.nama']);

        $transaksi = DB::table('transaksi_jurnal')->get(['id', 'nama']);
        $kodes = JenisTransaksi::select('kode')->whereNull('id_induk')->orderByDesc('id')->first();
        $kodeS = ($kodes) ? $kodes->kode+1 : 1;

        return view('setting-cash-flow/form', compact('jenisTransaksi', 'aksi', 'kodeS', 'transaksi', 'induk'));
    }

    public function update (Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = JenisTransaksi::select('id_transaksi_jurnal')->where('id_transaksi_jurnal', $request->id_transaksi_jurnal)->first();
            //dd($validator);
            if($validator == null)
            {
                JenisTransaksi::find($request->id)->update($request->all());
                DB::commit();
                return redirect('setting-cash-flow/index')->with('success', 'Berhasil di update');
            }
        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
        }

        if($validator == null)
        {
            return redirect('setting-cash-flow/index')->with('success', 'Berhasil di update');
        }

        if (isset($validator->id_transaksi_jurnal))
        {
            return 'Gagal di update karena Transaksi Jurnal sudah ada';
        }
    }

    public function delete(Request $request)
    {
        $data = JenisTransaksi::select('jenis_transaksi.id', 'transaksi_jurnal.nama')
        ->join('transaksi_jurnal', 'transaksi_jurnal.id', 'jenis_transaksi.id_transaksi_jurnal')
        ->where('jenis_transaksi.id', $request->id)
        ->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        JenisTransaksi::find($request->id)->delete();
        return redirect('setting-cash-flow/index')->with('success', 'Berhasil dihapus');
    }
}
