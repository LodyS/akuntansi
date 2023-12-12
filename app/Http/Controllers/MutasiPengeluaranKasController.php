<?php

namespace App\Http\Controllers;
use App\Models\KasBank;
use App\Models\Perkiraan;
use App\MutasiKas;
use App\MutasiKasDetail;
use App\Models\TarifPajak;
use App\ArusKas;
use App\Models\Unit;
use DB;
use App\jurnal;
use App\DetailJurnal;
use Auth;
use App\Http\Requests\MutasiValidasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MutasiPengeluaranKasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-mutasi-pengeluaran-kas');
    }

    public function index()
    {
        $startDate = Carbon::now()->toDateString();
        $endDate = Carbon::now()->toDateString();

        // $tanggal = Carbon::now();
        $bank = KasBank::get(['id', 'nama']);
        // $perkiraan = Perkiraan::select('id', 'nama')->get();
        $data = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal, flag_bayar')
        ->selectRaw('mutasi_kas.penerima, perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal')
        ->selectRaw('case when flag_jurnal ="N" then "Belum Dijurnal"
        when flag_jurnal ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->where('tipe','-1')
        // ->whereMonth('tanggal', $tanggal->month)
        // ->whereYear('tanggal', $tanggal->year)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->paginate(25);

        return view('mutasi-pengeluaran-kas/index', compact('bank', 'data', 'startDate', 'endDate'));
    }

    public function pencarian (Request $request)
    {
        // dd($request->all());
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        // $id_perkiraan = $request->id_perkiraan;
        // $id_unit = $request->id_unit;
        $id_bank = $request->id_bank;
        $flag_bayar = $request->flag_bayar;

        $bank = KasBank::get(['id', 'nama']);
        $perkiraan = Perkiraan::get(['id', 'nama']);
        $data = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal, flag_bayar')
        ->selectRaw('mutasi_kas.penerima, perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal')
        ->selectRaw('case when status ="N" then "Belum Dijurnal" when status ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
        ->where('tipe',-1)
        ->when($id_bank, function($query, $id_bank){
            return $query->where('mutasi_kas.id_kas_bank', $id_bank);
        })
        ->when($flag_bayar, function($query, $flag_bayar){
            return $query->where('mutasi_kas.flag_bayar', $flag_bayar);
        })
        ->paginate(20);

        return view ('mutasi-pengeluaran-kas/index', compact('data', 'bank', 'perkiraan'))->with([
            'startDate' => $tanggal_awal,
            'endDate' => $tanggal_akhir,
            'id_bank' => $id_bank,
            'flag_bayar' => $flag_bayar,
        ]);
    }

    public function verif (Request $request)
    {
        $data = MutasiKas::find($request->id);
        echo json_encode($data);
    }

    public function verifikasi (Request $request)
    {
       MutasiKas::where('id', $request->id)->update([
            'flag_bayar'=>'Y'
       ]);

       return redirect('mutasi-pengeluaran-kas/index')->with('success', 'Sukses diverifikasi');
    }

    public function create ()
    {
        $data = [
            'arusKas'=>ArusKas::where('tipe', '-1')->get(),
            'arus'=>ArusKas::get(['id', 'nama']),
            'kasBank'=>KasBank::get(['id', 'nama']),
            'perkiraan'=>Perkiraan::get(['id', 'nama', 'kode_rekening']),
            'unit'=>Unit::get(['id', 'nama', 'code_cost_centre']),
            'tarifPajak'=>TarifPajak::get(['id', 'nama_pajak']),
            'kode_jurnal'=>jurnal::newCode('CDJ'),
            'kode_awal'=>MutasiKas::bkk()
        ];

        return view('mutasi-pengeluaran-kas/form')->with($data);
    }

    public function bukti(Request $request)
    {
        $id = $request->id;
        $mutasi = MutasiKas::where('no_jurnal', $id)->first();
        $mutasiId = isset($mutasi) ? $mutasi->id : '';

        $mutasi_kas = MutasiKas::selectRaw('id, kode, tanggal, penerima, keterangan')->where('tipe','-1');

        $mutasi_kas_detail = DB::table('mutasi_kas_detail')
        ->selectRaw('unit.nama as unit, mutasi_kas.id as id_mutasi, unit.code_cost_centre, mutasi_kas_detail.keterangan')
        ->selectRaw('mutasi_kas_detail.nominal, perkiraan.nama as rekening')
        ->leftJoin('mutasi_kas', 'mutasi_kas.id', 'mutasi_kas_detail.id_mutasi_kas')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas_detail.id_unit')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas_detail.id_perkiraan');

        $mutasiKas = ($mutasiId == null) ? $mutasi_kas->where('id', $id)->first() : $mutasi_kas->where('id', $mutasiId)->first();
        $mutasiKasDetail = ($mutasiId == null) ? $mutasi_kas_detail->where('id_mutasi_kas', $id)->get() : $mutasi_kas_detail->where('id_mutasi_kas', $mutasiId)->get();

        return view('mutasi-pengeluaran-kas/bukti-transaksi', compact('mutasiKas', 'mutasiKasDetail'));
    }

    public function buktiKasKeluar($id_mutasi_kas)
    {
        $mutasiKas = MutasiKas::where('id', $id_mutasi_kas)->first();

        $mutasiKasDetail = DB::table('mutasi_kas_detail')
        ->selectRaw('unit.nama as unit, mutasi_kas.id as id_mutasi, unit.code_cost_centre, mutasi_kas_detail.keterangan')
        ->selectRaw('(mutasi_kas_detail.nominal * coalesce(mutasi_kas_detail.tipe,1)) as nominal, perkiraan.nama as rekening')
        ->join('mutasi_kas', 'mutasi_kas.id', 'mutasi_kas_detail.id_mutasi_kas')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas_detail.id_unit')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas_detail.id_perkiraan')
        ->where('mutasi_kas.id', $id_mutasi_kas)
        ->get();

        $total = $mutasiKasDetail->sum('nominal');

        return view('mutasi-pengeluaran-kas/bukti-transaksi-kas-keluar', compact('mutasiKas', 'mutasiKasDetail', 'total'));
    }

    public function autocomplete ($id_pembayaran=0)
    {
        $subArus['data'] = ArusKas::orderBy('nama', 'asc')
        ->select('id', 'nama')
        ->where('id_induk', $id_pembayaran)
        ->where('jenis',1)
        ->get();

        echo json_encode($subArus);
        exit;
    }

    public function detail (Request $request)
    {
        $mutasiKas = MutasiKas::selectRaw('mutasi_kas.kode, tanggal, mutasi_kas.keterangan, kas_bank.nama as bank, nominal')
        ->selectRaw('arus_kas.nama as arus_kas, ak.nama as induk')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('arus_kas', 'arus_kas.id', 'mutasi_kas.id_arus_kas')
        ->leftJoin('arus_kas as ak', 'ak.id', 'arus_kas.id_induk')
        ->where('mutasi_kas.id', $request->id)
        ->where('mutasi_kas.tipe', '-1')
        ->firstOrFail();

        $mutasiKasDetail = DB::table('mutasi_kas_detail')
        ->selectRaw('mutasi_kas_detail.id, perkiraan.kode_rekening, perkiraan.nama as rekening, mutasi_kas_detail.keterangan')
        ->selectRaw('unit.nama as cost_centre, nama_pajak, mutasi_kas_detail.nominal')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas_detail.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas_detail.id_unit')
        ->leftJoin('tarif_pajak', 'tarif_pajak.id', 'mutasi_kas_detail.id_tarif_pajak')
        ->where('id_mutasi_kas', $request->id)
        ->paginate(50);

        return view('mutasi-pengeluaran-kas/detail', compact('mutasiKas', 'mutasiKasDetail'));
    }

    public function edit (Request $request)
    {
        $data = MutasiKasDetail::find($request->id);
        echo json_encode($data);
    }

    public function isi($id_tarif_pajak)
    {
        $data = TarifPajak::select('id_perkiraan')->where('id', $id_tarif_pajak)->first();

        echo json_encode($data);
        exit;
    }

    public function store (MutasiValidasi $request)
    {
        // dd($request->all());
		DB::beginTransaction();

		try {

            $success = true;
			$act = new MutasiKas;
			$act->kode = $request->kode;
			//$act->id_arus_kas = $request->id_arus_kas;
			$act->tanggal = $request->tanggal;
            $act->tipe = '-1';
            $act->nominal = $request->total_nominal;
            $act->penerima = $request->penerima;
            $act->keterangan = $request->keterangan_awal;
			$act->id_kas_bank = $request->id_kas_bank;
            $act->status = 'N';
            $act->flag_bayar = 'N';
			$act->save();

			$id_mutasi_kas = $act->id;
			$data = $request->all();

			for ($i=0; $i<count($data['id_unit']); $i++)
            {
                // $nominal = str_replace('.', '', $data['nominal'][$i]);
                $nominal = str_replace('-', '', $data['jumlah'][$i]);

				$insert = array (
					'id_mutasi_kas'=>$id_mutasi_kas,
					'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'id_unit'=>$data['id_unit'][$i],
					'nominal'=>$nominal,
                    'id_tarif_pajak'=>$data['id_tarif_pajak'][$i],
                    'tipe'=>$data['tipe'][$i],
					'keterangan'=>$data['keterangan'][$i],);

				MutasiKasDetail::create($insert);
            }

             // proses jurnal

             $jurnalkredit = DB::table('mutasi_kas')
             ->selectRaw("perkiraan.id AS id_perkiraan, null AS id_unit, 0 AS debet, ABS(mutasi_kas.nominal) AS kredit")
             ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
             ->leftJoin('perkiraan', 'perkiraan.id', 'kas_bank.id_perkiraan')
             ->where('mutasi_kas.id', $id_mutasi_kas)
             ->get();

             $jurnaldebet = DB::table('mutasi_kas')
             ->selectRaw("mutasi_kas_detail.id_perkiraan, mutasi_kas_detail.id_unit, 0 AS debet, ABS(mutasi_kas_detail.nominal) AS kredit ")
             ->leftJoin('mutasi_kas_detail', 'mutasi_kas_detail.id_mutasi_kas', 'mutasi_kas.id')
             ->where('mutasi_kas.id', $id_mutasi_kas)
             ->where('mutasi_kas_detail.tipe', '-1')
             ->get();

             $jurnalkreditt = DB::table('mutasi_kas')
             ->selectRaw("mutasi_kas_detail.id_perkiraan, mutasi_kas_detail.id_unit, ABS(mutasi_kas_detail.nominal) AS debet, 0 AS kredit")
             ->leftJoin('mutasi_kas_detail', 'mutasi_kas_detail.id_mutasi_kas', 'mutasi_kas.id')
             ->where('mutasi_kas.id', $id_mutasi_kas)
            //  ->where('mutasi_kas_detail.tipe',1)
             ->where(function($query){
                 $query->where('mutasi_kas_detail.tipe',1)->orWhereNull('mutasi_kas_detail.tipe');
             })
             ->get();

             //dd($jurnalkredit);

             $id_user = Auth::user()->id;

             $act = new jurnal;
            //  $act->kode_jurnal = $request->kode_jurnal;
             $act->kode_jurnal = jurnal::newCode('CDJ');
             $act->tanggal_posting = $request->tanggal;
             $act->keterangan = $request->keterangan_awal;
             $act->id_tipe_jurnal = 4; //Cash Dishbursment Journal
             $act->id_user = $id_user;
             $act->save();

             $id_jurnal = $act->id;

             foreach($jurnaldebet as $data)
             {
                 $id_perkiraan = $data->id_perkiraan;
                 $debet = $data->debet;
                 $kredit = $data->kredit;

                 $insert = array (
                     'id_jurnal'=>$id_jurnal,
                     'id_perkiraan'=>$id_perkiraan,
                     'debet'=>$debet,
                     'kredit'=>$kredit,
                     'ref'=>'N',);

                 DetailJurnal::insert($insert);
             }

             //dd($jurnalkredit);
             foreach($jurnalkredit as $data)
             {
                 $id_perkiraan = $data->id_perkiraan;
                 $id_unit = $data->id_unit;
                 $debet = $data->debet;
                 $kredit = $data->kredit;

                 $insert = array (
                     'id_jurnal'=>$id_jurnal,
                     'id_perkiraan'=>$id_perkiraan,
                     'id_unit'=>$id_unit,
                     'debet'=>$debet,
                     'kredit'=>$kredit,
                     'ref'=>'N',);

                 DetailJurnal::insert($insert);
             }

             foreach($jurnalkreditt as $data)
             {
                 $id_perkiraan = $data->id_perkiraan;
                 $id_unit = $data->id_unit;
                 $debet = $data->debet;
                 $kredit = $data->kredit;

                 $insert = array (
                     'id_jurnal'=>$id_jurnal,
                     'id_perkiraan'=>$id_perkiraan,
                     'id_unit'=>$id_unit,
                     'debet'=>$debet,
                     'kredit'=>$kredit,
                     'ref'=>'N',);

                 DetailJurnal::insert($insert);
             }

             MutasiKas::where('id', $id_mutasi_kas)->update(['flag_jurnal'=>'Y', 'no_jurnal'=>$id_jurnal]);

			DB::commit();
		} catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            $success =false;
		}

        if ($success == true){
            return redirect('mutasi-pengeluaran-kas/index')->with('success', 'Mutasi Pengeluaran Kas Berhasil disimpan');
        } else if ($success == false){
            return redirect('mutasi-pengeluaran-kas/index')->with('danger', 'Mutasi Pengeluaran kas simpan karena error sistem');
        }
    }

    public function update (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->id_perkiraan == null && $request->id == null)
            {
                message(false, '', 'Mutasi Pengeluaran Kas Bank gagal disimpan');
                return back();
            }

            if (isset($request->id_perkiraan) && isset($request->id))
            {
                $act = MutasiKasDetail::where('id', $request->id)->update([
                    'id_perkiraan'=>$request->id_perkiraan,
                    'id_unit'=>$request->id_unit,
                ]);

                DB::commit();
                message($act, 'Mutasi Pengeluaran Kas Berhasil disimpan', 'Mutasi Pengeluaran Kas gagal disimpan');
                return redirect('mutasi-pengeluaran-kas/index');
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false, '', 'Mutasi Penerimaan Kas disimpan');
            return redirect('mutasi-pengeluaran-kas/index');
        }
    }

    public function delete (Request $request)
    {
        $data = MutasiKasDetail::where('id', $request->id)->first();

        echo json_encode($data);
    }

    public function destroy(Request $request)
    {
        $act = MutasiKasDetail::where('id', $request->id)->delete();
        message($act, "Berhasil hapus data", "Gagal hapus data");
        return redirect('mutasi-pengeluaran-kas/index');
    }

    public function simpan (Request $request)
    {
		$id_user = Auth::user()->id;
		DB::beginTransaction();

		try {

            if ($request->balance == 0)
            {
                $success = true;
			    $act = new Jurnal;
			    $act->kode_jurnal = $request->kode_jurnal;
			    $act->tanggal_posting = $request->tanggal;
			    $act->keterangan = 'Pengeluaran kas tanggal : '.date('d-m-Y', strtotime($request->tanggal));
			    $act->id_tipe_jurnal = 5;
			    $act->id_user = $id_user;
			    $act->save();

			    $id_jurnal = $act->id;
			    $data = $request->all();

			    for ($i=0; $i<count($data['id_perkiraan']); $i++)
                {
				    $insert = array (
					    'id_jurnal'=>$id_jurnal,
					    'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'id_unit'=>$data['id_unit'][$i],
					    'debet'=>$data['debet'][$i],
					    'kredit'=>$data['kredit'][$i],
					    'ref'=>'N',);

				    DetailJurnal::create($insert);
                }

                MutasiKas::where('id', $request->id_mutasi_kas)->update(['flag_jurnal'=>'Y', 'no_jurnal'=>$id_jurnal, 'flag_bayar'=>'Y']);
			}
			DB::commit();
		} catch (Exception $e) {
            DB::rollback();
            $success =false;
		}

        if ($request->balance > 0 || $request->balance == null || $request->balance < 0) {
			return redirect('mutasi-pengeluaran-kas/index')->with('error', 'Maaf tidak bisa input jurnal pengeluaran kas karena tidak balance');
        } else if ($success == true){
            return redirect('mutasi-pengeluaran-kas/index')->with('success', 'Jurnal Mutasi Pengeluaran Kas Berhasil disimpan');
        } else if ($success == false){
            return redirect('mutasi-pengeluaran-kas/index')->with('danger', 'Jurnal Mutasi Pengeluaran Kas Gagal simpan karena error sistem');
        }
	} //simpan jurnal dari hasil verifikasi

    public function lihatJurnal ($id_mutasi_kas)
    {
        $data = DB::table('mutasi_kas as mk')
        ->join('jurnal as j','mk.no_jurnal','j.id')
        ->join('detail_jurnal as dj', 'j.id', 'dj.id_jurnal')
        ->leftJoin('perkiraan as p', 'dj.id_perkiraan', 'p.id')
        ->leftJoin('unit as u', 'dj.id_unit', 'u.id')
        ->where('mk.id', $id_mutasi_kas)
        ->select('j.id', 'j.tanggal_posting', 'j.keterangan', 'j.kode_jurnal', 'p.kode_rekening', 'p.nama as coa', 'u.nama as unit', 'u.code_cost_centre')
        ->selectRaw('sum(dj.debet) as debet, sum(dj.kredit) as kredit')
        ->groupBy('j.id', 'j.tanggal_posting', 'j.keterangan', 'j.kode_jurnal', 'p.kode_rekening', 'p.nama', 'u.nama', 'u.code_cost_centre')
        ->get();

        return view('mutasi-pengeluaran-kas.lihat-jurnal', ['data' => $data]);
    }
}
