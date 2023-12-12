<?php

namespace App\Http\Controllers;
use App\Models\KasBank;
use App\Models\Perkiraan;
use App\MutasiKas;
use App\MutasiKasDetail;
use App\ArusKas;
use App\Models\TarifPajak;
use App\Models\Unit;
use App\Jurnal;
use App\DetailJurnal;
use DB;
use Carbon\Carbon;
use Auth;
use App\Http\Requests\MutasiValidation;
use Illuminate\Http\Request;

class MutasiPenerimaanKasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-mutasi-penerimaan-kas');
    }

    public function index()
    {
        $startDate = Carbon::now()->toDateString();
        $endDate = Carbon::now()->toDateString();
        $bank = KasBank::pluck('nama', 'id');
        $data = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, mutasi_kas.penerima, no_jurnal')
        ->selectRaw('flag_bayar, perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal')
        ->selectRaw('case when flag_jurnal ="N" then "Belum Dijurnal"
        when flag_jurnal ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->where('tipe',1)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->paginate(50);

        return view('mutasi-penerimaan-kas/index', compact('bank', 'data', 'startDate', 'endDate'));
    }

    public function verif (Request $request)
    {
        $data = MutasiKas::find($request->id);

        echo json_encode($data);
    }

    public function verifikasi (Request $request)
    {
       MutasiKas::where('id',$request->id)->update([
            'flag_bayar'=>'Y'
       ]);

       return redirect('mutasi-penerimaan-kas/index')->with('success', 'Sukses diverifikasi');
    }

    public function pencarian (Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        // $id_perkiraan = $request->id_perkiraan;
        // $id_unit = $request->id_unit;
        $id_bank = $request->id_bank;
        $flag_bayar = $request->flag_bayar;

        $data = DB::table('mutasi_kas')
        ->selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal, flag_bayar, mutasi_kas.penerima')
        ->selectRaw('perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(mutasi_kas.nominal) as nominal')
        ->selectRaw('case when flag_jurnal ="N" then "Belum Dijurnal" when flag_jurnal ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status')
        ->selectRaw('case when flag_bayar ="N" then "Belum Diverifikasi" when flag_bayar ="Y" then "Sudah Diverifikasi" end as verifikasi')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
        ->where('tipe',1)
        ->when($id_bank, function($query, $id_bank){
            return $query->where('mutasi_kas.id_kas_bank', $id_bank);
        })
        ->when($flag_bayar, function($query, $flag_bayar){
            return $query->where('mutasi_kas.flag_bayar', $flag_bayar);
        })
        ->paginate(50);

        $passing = [
            'startDate'=>Carbon::now()->toDateString(),
            'endDate'=>Carbon::now()->toDateString(),
            'tanggal_awal'=>$tanggal_awal,
            'tanggal_akhir'=>$tanggal_akhir,
            'id_bank'=>$id_bank,
            'flag_bayar'=>$flag_bayar,
            'bank'=> KasBank::pluck('nama', 'id'),
            'perkiraan'=>Perkiraan::get(['id', 'nama']),
            'data'=>$data
        ];

        return view ('mutasi-penerimaan-kas/index')->with($passing);
    }

    public function isi($id_tarif_pajak)
    {
        $data = TarifPajak::select('id_perkiraan')->where('id', $id_tarif_pajak)->first();

        echo json_encode($data);
        exit;
    }

    public function create ()
    {
        $data = [
            'kode_awal'=>MutasiKas::bkm(),
            'kode_jurnal'=>Jurnal::newCodeCRJ(),
            'tarifPajak'=>TarifPajak::pluck('nama_pajak', 'id'),
            'unit'=>Unit::get(['id', 'nama', 'code_cost_centre']),
            'perkiraan'=>Perkiraan::get(['id','kode_rekening', 'nama']),
            'kasBank'=>KasBank::pluck('nama', 'id'),
            'arus'=>ArusKas::get(['id', 'nama']),
            'arusKas'=>ArusKas::filterSatu()
        ];

        return view('mutasi-penerimaan-kas/form')->with($data);
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

    public function bukti(Request $request)
    {
        $id = $request->id;
        $mutasi = MutasiKas::where('no_jurnal', $id)->first();
        $mutasiId = isset($mutasi) ? $mutasi->id : '';

        $mutasi_kas = MutasiKas::selectRaw('id, kode, tanggal, keterangan, penerima, flag_bayar')->where('tipe', '1');

        $mutasi_kas_detail = DB::table('mutasi_kas_detail')
        ->selectRaw('unit.nama as unit, mutasi_kas.id as id_mutasi, unit.code_cost_centre, mutasi_kas_detail.keterangan')
        ->selectRaw('mutasi_kas_detail.nominal, perkiraan.nama as rekening')
        ->leftJoin('mutasi_kas_detail', function($join){
            $join->on('mutasi_kas', 'mutasi_kas.id', 'mutasi_kas_detail.id_mutasi_kas')
                 ->on('unit', 'unit.id', 'mutasi_kas_detail.id_unit')
                 ->on('perkiraan', 'perkiraan.id', 'mutasi_kas_detail.id_perkiraan');
        });

        $mutasiKas = ($mutasiId == null) ? $mutasi_kas->where('id', $id)->first() : $mutasi_kas->where('id', $mutasiId)->first();
        $mutasiKasDetail = ($mutasiId == null) ? $mutasi_kas_detail->where('id_mutasi_kas', $id)->get() : $mutasi_kas_detail->where('id_mutasi_kas', $mutasiId)->get();

        return view('mutasi-penerimaan-kas/bukti-transaksi', compact('mutasiKas', 'mutasiKasDetail'));
    }

    public function buktiKasMasuk($id_mutasi_kas)
    {
        $mutasiKas = MutasiKas::where('id', $id_mutasi_kas)->first();

        $mutasiKasDetail = DB::table('mutasi_kas_detail')
        ->selectRaw('unit.nama as unit, mutasi_kas.id as id_mutasi, unit.code_cost_centre, mutasi_kas_detail.keterangan')
        ->selectRaw('mutasi_kas_detail.nominal * coalesce(mutasi_kas_detail.tipe,1) as nominal, perkiraan.nama as rekening')
        ->join('mutasi_kas', 'mutasi_kas.id', 'mutasi_kas_detail.id_mutasi_kas')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas_detail.id_unit')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas_detail.id_perkiraan')
        ->where('mutasi_kas.id', $id_mutasi_kas)
        ->get();

        $total = $mutasiKasDetail->sum('nominal');

        return view('mutasi-penerimaan-kas/bukti-transaksi-kas-masuk', compact('mutasiKas', 'mutasiKasDetail', 'total'));
    }

    public function detail (Request $request)
    {
        $mutasiKas = MutasiKas::selectRaw('mutasi_kas.kode, tanggal, mutasi_kas.keterangan, kas_bank.nama as bank, abs(nominal) as nominal')
        ->selectRaw('arus_kas.nama as arus_kas, ak.nama as induk')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('arus_kas', 'arus_kas.id', 'mutasi_kas.id_arus_kas')
        ->leftJoin('arus_kas as ak', 'ak.id', 'arus_kas.id_induk')
        ->where('mutasi_kas.id', $request->id)
        ->where('mutasi_kas.tipe', 1)
        ->firstOrFail();

        $mutasiKasDetail = DB::table('mutasi_kas_detail')
        ->selectRaw('mutasi_kas_detail.id, perkiraan.kode_rekening, perkiraan.nama as rekening, mutasi_kas_detail.keterangan')
        ->selectRaw('unit.nama as cost_centre, nama_pajak, mutasi_kas_detail.nominal')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas_detail.id_perkiraan')
        ->leftJoin('unit', 'unit.id', 'mutasi_kas_detail.id_unit')
        ->leftJoin('tarif_pajak', 'tarif_pajak.id', 'mutasi_kas_detail.id_tarif_pajak')
        ->where('id_mutasi_kas', $request->id)
        ->paginate(50);

        return view('mutasi-penerimaan-kas/detail', compact('mutasiKas', 'mutasiKasDetail'));
    }

    public function edit (Request $request)
    {
        $data = MutasiKasDetail::find($request->id);

        echo json_encode($data);
    }

    public function store (MutasiValidation $request)
    {
		DB::beginTransaction();

		try {

            $success = true;
			$act = new MutasiKas;
			$act->kode = $request->kode;
			$act->tanggal = $request->tanggal;
            $act->tipe = 1;
            $act->nominal = $request->total_nominal;
            $act->penerima = $request->diterima_oleh;
            $act->keterangan = $request->keterangan_awal;
			$act->id_kas_bank = $request->id_kas_bank;
            $act->status = 'N';
            $act->flag_bayar = 'N';
			$act->save();

			$id_mutasi_kas = $act->id;
			$data = $request->all();

			for ($i=0; $i<count($data['id_unit']); $i++):
              
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
            endfor;

            // proses jurnal

            $jurnaldebet = DB::table('mutasi_kas')
            ->selectRaw("perkiraan.id AS id_perkiraan, null AS id_unit, ABS(SUM(mutasi_kas.nominal)) AS debet, 0 AS kredit")
            ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
            ->leftJoin('perkiraan', 'perkiraan.id', 'kas_bank.id_perkiraan')
            ->where('mutasi_kas.id', $id_mutasi_kas)
            ->get();

            $jurnalkredit = DB::table('mutasi_kas')
            ->selectRaw("mutasi_kas_detail.id_perkiraan, mutasi_kas_detail.id_unit, 0 AS debet, ABS(mutasi_kas_detail.nominal) AS kredit")
            ->leftJoin('mutasi_kas_detail', 'mutasi_kas_detail.id_mutasi_kas', 'mutasi_kas.id')
            ->where('mutasi_kas.id', $id_mutasi_kas)
            // ->where('mutasi_kas_detail.tipe',1)
            ->where(function($query){
                $query->where('mutasi_kas_detail.tipe',1)->orWhereNull('mutasi_kas_detail.tipe');
            })
            ->get();

            $jurnaldebit = DB::table('mutasi_kas')
            ->selectRaw("mutasi_kas_detail.id_perkiraan, mutasi_kas_detail.id_unit, ABS(mutasi_kas_detail.nominal) AS debet ,0 AS kredit ")
            ->leftJoin('mutasi_kas_detail', 'mutasi_kas_detail.id_mutasi_kas', 'mutasi_kas.id')
            ->where('mutasi_kas.id', $id_mutasi_kas)
            ->where('mutasi_kas_detail.tipe', '-1')
            ->get();

            $id_user = Auth::user()->id;

            $act = new Jurnal;
			// $act->kode_jurnal = $request->kode_jurnal;
			$act->kode_jurnal = Jurnal::newCodeCRJ();
			$act->tanggal_posting = $request->tanggal;
			$act->keterangan = $request->keterangan_awal;
			$act->id_tipe_jurnal = 3; //Cash Receipt Journal
			$act->id_user = $id_user;
			$act->save();

			$id_jurnal = $act->id;

			foreach($jurnaldebet as $data):
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
            endforeach;

            foreach($jurnalkredit as $data):
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
            endforeach;

            foreach($jurnaldebit as $data):
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
            endforeach;

            MutasiKas::where('id', $id_mutasi_kas)->update(['flag_jurnal'=>'Y', 'no_jurnal'=>$id_jurnal]);
			DB::commit();
		} catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            $success =false;
		}

        if ($success == true):
            return redirect('mutasi-penerimaan-kas/index')->with('success', 'Mutasi Penerimaan Kas Berhasil disimpan');
        elseif ($success == false):
            return redirect('mutasi-penerimaan-kas/index')->with('danger', 'Mutasi Penerimaan kas simpan karena error sistem');
        endif;
    }

    public function update (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->id_perkiraan == null && $request->id == null):
                message(false, '', 'Setting COA Kas Bank gagal disimpan');
                return back();
            endif;

            if (isset($request->id_perkiraan) && isset($request->id)):
                $act = MutasiKasDetail::where('id', $request->id)->update([
                    'id_perkiraan'=>$request->id_perkiraan,
                    'id_unit'=>$request->id_unit,
                ]);

                DB::commit();
                message($act, 'Mutasi Penerimaan Kas Berhasil disimpan', 'Mutasi Penerimaan Kas gagal disimpan');
                return redirect('mutasi-penerimaan-kas/index');
            endif;
        }
        catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false, '', 'Mutasi Penerimaan Kas disimpan');
            return redirect('mutasi-penerimaan-kas/index');
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
        return redirect('mutasi-penerimaan-kas/index');
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
			    $act->keterangan = 'Penerimaan kas tanggal : '.date('d-m-Y', strtotime($request->tanggal));
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
		} catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            $success =false;
		}

        if ($request->balance > 0 || $request->balance == null || $request->balance < 0) {
			return redirect('mutasi-penerimaan-kas/index')->with('error', 'Maaf tidak bisa input jurnal penerimaan kas karena tidak balance');
        } else if ($success == true){
            return redirect('mutasi-penerimaan-kas/index')->with('success', 'Jurnal Mutasi Penerimaan Kas Berhasil disimpan');
        } else if ($success == false){
            return redirect('mutasi-penerimaan-kas/index')->with('danger', 'Jurnal Mutasi Penerimaan Kas Gagal simpan karena error sistem');
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

        return view('mutasi-penerimaan-kas.lihat-jurnal', ['data' => $data]);
    }
}
