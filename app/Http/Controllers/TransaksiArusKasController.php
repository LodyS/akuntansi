<?php

namespace App\Http\Controllers;
use App\Models\KasBank;
use App\Models\Perkiraan;
use App\Models\MutasiKas;
use App\MutasiKasDetail;
use App\Models\TarifPajak;
use App\ArusKas;
use App\Models\Unit;
use DB;
use App\Http\Requests\TransaksiArusKas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiArusKasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-transaksi-arus-kas');
    }

    public function index()
    {
        $tanggal = Carbon::now();
        $bank = KasBank::pluck('nama', 'id');
        $perkiraan = Perkiraan::pluck('nama', 'id');
        $data = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal,
        perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal, case when flag_jurnal ="N" then "Belum Dijurnal"
        when flag_jurnal ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status,
        case when flag_bayar ="N" then "Belum Diverifikasi"
        when flag_bayar ="Y" then "Sudah Diverifikasi" end as verifikasi')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->whereMonth('tanggal', $tanggal->month)
        ->whereYear('tanggal', $tanggal->year)
        ->paginate(50);

        return view('transaksi-arus-kas/index', compact('bank', 'data','perkiraan'));
    }

    public function pencarian (Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $id_perkiraan = $request->id_perkiraan;
        $flag_bayar = $request->flag_bayar;
        //$id_unit = $request->id_unit;
        $id_bank = $request->id_bank;

        $bank = KasBank::pluck('nama', 'id');
        $perkiraan = Perkiraan::pluck('nama', 'id');
        $data = DB::table('mutasi_kas')
        ->selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal')
        ->selectRaw('perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal')
        ->selectRaw('case when flag_jurnal ="N" then "Belum Dijurnal"
        when flag_jurnal ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status,
        case when flag_bayar ="N" then "Belum Diverifikasi"
        when flag_bayar ="Y" then "Sudah Diverifikasi" end as verifikasi')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->where(function($query) use($tanggal_awal,$tanggal_akhir){
            $query->whereBetween('tanggal',[$tanggal_awal,$tanggal_akhir]);
        })
        ->when($id_perkiraan, function($query, $id_perkiraan){
            return $query->where('id_perkiraan', $id_perkiraan);
        })
        ->when($flag_bayar, function($query, $flag_bayar){
            return $query->where('flag_bayar', $flag_bayar);
        })
        ->when($id_perkiraan, function($query, $id_perkiraan){
            return $query->where('id_perkiraan', $id_perkiraan);
        })
        ->paginate(50);

        return view ('transaksi-arus-kas/index', compact('data', 'bank', 'perkiraan'));
    }

    public function verifikasi ()
    {
        $tanggal = Carbon::now();
        $bank = KasBank::select('id', 'nama')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $data = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal,
        perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal, case when flag_jurnal ="N" then "Belum Dijurnal"
        when flag_jurnal ="Y" then "Sudah Dijurnal" when flag_jurnal ="T" then "Tidak Dijurnal" end as status')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->where('tipe','-1')
        ->where('flag_bayar', 'N')
        ->whereMonth('tanggal', $tanggal->month)
        ->whereYear('tanggal', $tanggal->year)
        ->get();

        return view('transaksi-arus-kas/verifikasi', compact('bank', 'data','perkiraan'));
    }

    public function pencarianVerifikasi (Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $id_perkiraan = $request->id_perkiraan;
        $id_unit = $request->id_unit;

        $bank = KasBank::select('id', 'nama')->get();
        $perkiraan = Perkiraan::select('id', 'nama')->get();
        $data = MutasiKas::selectRaw('mutasi_kas.id,mutasi_kas.tanggal, mutasi_kas.kode, mutasi_kas.keterangan, no_jurnal,
        perkiraan.nama as perkiraan, kas_bank.nama as kas_bank, abs(nominal) as nominal, case when status ="N" then "Belum Dijurnal"
        when status ="Y" then "Sudah Dijurnal" when status ="T" then "Tidak Dijurnal" end as status')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'mutasi_kas.id_perkiraan')
        ->where('tipe','-1')
        ->where('flag_bayar', 'N')
        ->whereBetween('tanggal',[$tanggal_awal,$tanggal_akhir])
        ->when($id_perkiraan, function($query, $id_perkiraan){
            return $query->where('id_perkiraan', $id_perkiraan);
        })
        ->when($flag_bayar, function($query, $flag_bayar){
            return $query->where('flag_bayar', $flag_bayar);
        })
        ->when($id_perkiraan, function($query, $id_perkiraan){
            return $query->where('id_perkiraan', $id_perkiraan);
        })
        ->get();

        return view ('transaksi-arus-kas/verifikasi', compact('data', 'bank', 'perkiraan'));
    }

    public function updateVerifikasi(Request $request)
    {
        $jumlah = count($request->id);
        for ($i=0; $i<$jumlah; $i++)
        {
            DB::table('mutasi_kas')->where('id', $request->id[$i])->update([
                'flag_bayar'=>$request->centang[$i],
            ]);
        }
        return redirect('transaksi-arus-kas/verifikasi')->with('success', 'Berhasil Verifikasi');
    }

    public function create ()
    {
        $tanggal = date('Ymd');
        $kode_bkm = MutasiKas::selectRaw("CONCAT('BKK-','$tanggal', '-',SUBSTR(kode, 14)+1) AS kode")
        ->where('kode', 'like', 'BKK%')
        ->orderByDesc('id')
        ->first();

        $data = [
            'tanggal'=>$tanggal,
            'arusKas'=>ArusKas::pluck('nama', 'id'),
            'kasBank'=>KasBank::pluck('nama', 'id'),
            'perkiraan'=>DB::table('perkiraan')->get(['id', 'nama', 'kode_rekening']),
            'unit'=>Unit::get(['id', 'nama', 'code_cost_centre']),
            'kode_bkm'=>$kode_bkm,
            'tarifPajak'=>TarifPajak::pluck('nama_pajak', 'id'),
            'kode_awal'=>isset($kode_bkm) ? $kode_bkm : "BKK".'-'.$tanggal.'-'.'1',
        ];

        return view('transaksi-arus-kas/form')->with($data);
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

    public function isiKode ($tipe)
    {
        $tanggal = date('Ymd');
        if ($tipe == '-1')
        {
            $kode_bkm = MutasiKas::selectRaw("CONCAT('BKK-','$tanggal', '-',SUBSTR(kode, 14)+1) AS kode")
            ->where('kode', 'like', 'BKK%')
            ->orderByDesc('id')
            ->first();

            $kode = isset($kode_bkm->kode) ? $kode_bkm->kode : 'BKK-'.$tanggal.'-1';

            return response()->json(['kode'=>$kode]);

        } else {

            $kode_bkm = MutasiKas::selectRaw("CONCAT('BKM-','$tanggal', '-',SUBSTR(kode, 14)+1) AS kode")
            ->where('kode', 'like', 'BKM%')
            ->orderByDesc('id')
            ->first();

            $kode = isset($kode_bkm->kode) ? $kode_bkm->kode : 'BKM-'.$tanggal.'-1';

            return response()->json(['kode'=>$kode]);
        }
    }

    public function detail (Request $request)
    {
        $mutasiKas = MutasiKas::selectRaw('mutasi_kas.kode, tanggal, mutasi_kas.keterangan, kas_bank.nama as bank')
        ->selectRaw('abs(nominal) as nominal, arus_kas.nama as arus_kas, ak.nama as induk')
        ->leftJoin('kas_bank', 'kas_bank.id', 'mutasi_kas.id_kas_bank')
        ->leftJoin('arus_kas', 'arus_kas.id', 'mutasi_kas.id_arus_kas')
        ->leftJoin('arus_kas as ak', 'ak.id', 'arus_kas.id_induk')
        ->where('mutasi_kas.id', $request->id)
        ->firstOrFail();

        return view('transaksi-arus-kas/detail', compact('mutasiKas'));
    }

    public function edit (Request $request)
    {
        $data = MutasiKasDetail::find($request->id);
        echo json_encode($data);
    }

    public function store (TransaksiArusKas $request)
    {
		DB::beginTransaction();

        $total_nominall = str_replace('.', '', $request->total_nominal);
        $total_nominal = str_replace(',', '.', $total_nominall);

		try {

            $success = true;
			$act = new MutasiKas;
			$act->kode = $request->kode;
			$act->id_arus_kas = $request->id_arus_kas;
			$act->tanggal = $request->tanggal;
            $act->nominal = $total_nominal;
            $act->tipe = $request->tipe;
            $act->keterangan = $request->keterangan_awal;
            $act->status = 'N';
            $act->flag_jurnal = 'T';
			$act->save();

			DB::commit();
		} catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
            $success =false;
		}

        if ($success == true){
            return redirect('transaksi-arus-kas/index')->with('success', 'Transaksi Arus Kas Berhasil disimpan');
        } else if ($success == false){
            return redirect('transaksi-arus-kas/index')->with('danger', 'Transaksi Arus kas simpan karena error sistem');
        }
    }

    public function update (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->id_perkiraan == null && $request->id == null)
            {
                message(false, '', 'Setting COA Kas Bank gagal disimpan');
                return redirect('setting-kas-bank/index');
            }

            if (isset($request->id_perkiraan) && isset($request->id))
            {
                $act = MutasiKasDetail::where('id', $request->id)->update([
                    'id_perkiraan'=>$request->id_perkiraan,
                    'id_unit'=>$request->id_unit,
                ]);

                DB::commit();
                message($act, 'Transaksi Kas Berhasil disimpan', 'Transaksi Kas gagal disimpan');
                return redirect('transaksi-arus-kas/index');
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false, '', 'Mutasi Penerimaan Kas gagal disimpan');
            return redirect('transaksi-arus-kas/index');
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
        return redirect('transaksi-arus-kas/index');
    }
}
