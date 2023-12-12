<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\jurnal;
use App\DetailJurnal;
use App\transaksi;
use App\Http\Requests\SaldoAwal;
use Illuminate\Http\Request;

class SaldoAwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-saldo-awal');
    }

    public function index()
    {
        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);
        $perkiraan = DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']);

        return view('saldo-awal/index', compact('unit', 'perkiraan'));
    }

    public function laporan (Request $request)
    {
        $id_perkiraan = $request->id_perkiraan;
        $id_unit = $request->id_unit;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $data = DB::table('transaksi')
        ->selectRaw('transaksi.id, perkiraan.kode_rekening, unit.code_cost_centre as code_cost_centre')
        ->selectRaw('perkiraan.nama AS perkiraan, unit.nama as unit, sum(transaksi.debet) as debet, sum(transaksi.kredit) as kredit')
        ->leftJoin('perkiraan', 'perkiraan.id', 'transaksi.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'transaksi.id_unit')
        ->whereMonth('transaksi.tanggal', $bulan)
        ->whereYear('transaksi.tanggal', $tahun)
        ->when($id_perkiraan, function($query, $id_perkiraan){
            return $query->where('id_perkiraan', $id_perkiraan);
        })
        ->when($id_unit, function($query, $id_unit){
            return $query->where('id_unit', $id_unit);
        })
        ->orderBy('perkiraan.kode_rekening', 'asc')
        ->orderBy('code_cost_centre', 'asc')
        ->groupBy('id_perkiraan', 'id_unit')
        ->get();

        $parsing = [
            'unit'=>DB::table('unit')->get(['id', 'nama', 'code_cost_centre']),
            'perkiraan'=>DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']),
            'data'=>$data,
            'total_debet'=>$data->sum('debet'),
            'total_kredit'=>$data->sum('kredit'),
        ];

        return view('saldo-awal/index')->with($parsing);
    }

    public function create ()
    {
        $aksi = "create";
        $id_user = Auth::user()->id;
        $data = new transaksi;

        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);
        $perkiraan = DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']);

        return view('saldo-awal/form', compact('perkiraan', 'unit', 'data', 'id_user', 'aksi'));
    }

    public function edit (Request $request)
    {
        $aksi = "edit";
        $data = transaksi::selectRaw('id, id_perkiraan, debet, kredit, tanggal, id_unit')->where('id', $request->id)->firstOrFail();

        $unit = DB::table('unit')->get(['id', 'nama', 'code_cost_centre']);
        $perkiraan = DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']);

        $id_user = Auth::user()->id;

        return view('saldo-awal/form', compact('perkiraan', 'unit', 'data', 'id_user', 'aksi'));
    }

    public function store(SaldoAwal $request)
    {
        $debit = str_replace('.', '', $request->debet);
        $debet = str_replace(',', '.', $debit);

        $kredut = str_replace('.', '', $request->kredit);
        $kredit = str_replace(',', '.', $kredut);

        DB::beginTransaction();

        try {

            $act = new jurnal;
		    $act->tanggal_posting = $request->tanggal;
		    $act->keterangan = 'Saldo Awal';
		    $act->id_user = $request->id_user;
		    $act->save();

            $detail_jurnal = new DetailJurnal;
            $detail_jurnal->id_jurnal = $act->id;
            $detail_jurnal->id_perkiraan = $request->id_perkiraan;
            $detail_jurnal->debet = $debet;
            $detail_jurnal->kredit = $kredit;
            $detail_jurnal->id_unit = $request->id_unit;
            $detail_jurnal->save();

            $transaksi = new transaksi;
            $transaksi->id_perkiraan = $request->id_perkiraan;
            $transaksi->id_unit = $request->id_unit;
            $transaksi->tanggal = $request->tanggal;
            $transaksi->id_jurnal = $act->id;
            $transaksi->keterangan = $request->keterangan;
            $transaksi->debet = $debet;
            $transaksi->kredit = $kredit;
            $transaksi->save();

            DB::commit();
            message($act, 'Data berhasil disimpan', 'Data gagal disimpan');
            return redirect('saldo-awal/index');

        } catch (\Illuminate\Database\QueryException $e){
            DB::rollback();
            return back()->withError('Invalid data');
        }
    }

    public function update (SaldoAwal $request)
    {
        $debit = str_replace('.', '', $request->debet);
        $debet = str_replace(',', '.', $debit);

        $kredut = str_replace('.', '', $request->kredit);
        $kredit = str_replace(',', '.', $kredut);

        $act = transaksi::where('id', $request->id)->update([
            'id_perkiraan'=>$request->id_perkiraan,
            'id_unit'=>$request->id_unit,
            'tanggal'=>$request->tanggal,
            'debet'=>$debet,
            'kredit'=>$kredit,
        ]);

        message($act, 'Berhasil diupdate', 'Gagal di update');
        return redirect('saldo-awal/index');
    }

    public function delete (Request $request)
    {
        $data = transaksi::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {

            $act = Transaksi::where('id', $request->id)->delete();
            DB::commit();
            message($act, "Berhasil hapus data", "Gagal hapus data");
            return redirect('saldo-awal/index');
        }
        catch (Exception $e){
            DB::rollback();
        }
    }
}
