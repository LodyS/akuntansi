<?php

namespace App\Http\Controllers;
use App\Models\KasBank;
use App\pembayaran;
use App\DetailPembayaran;
use App\Models\Pelanggan;
use App\Models\PendapatanJasa;
use App\pendapatan_jasa_langganan;
use App\Tagihan;
use App\jurnal;
use App\DetailJurnal;
use App\transaksi;
use DB;
use App\TipeJurnal;
use App\SettingCoa;
use App\Models\ProdukAsuransi;
use Illuminate\Http\Request;

class JurnalPenerimaanPiutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-penerimaan-piutang');
    }

    public function index ()
    {
        $KasBank = KasBank::select('id', 'nama')->get();
        $ProdukAsuransi = ProdukAsuransi::select('id', 'nama')->get();

        return view('jurnal-penerimaan-piutang/index', compact('KasBank', 'ProdukAsuransi'));
    }

    public function detail(Request $request)
    {
        $nama = DB::table('pelanggan')->select('nama as pelanggan')->where('id', $request->id_pelanggan)->first();
        $pelanggan = DB::table('tagihan')
        ->selectRaw('pelanggan.nama as pasien,
        SUM(tagihan.piutang) AS total_tagihan, SUM(tagihan.piutang) - sum(pendapatan_jasa.total_tagihan) as total_pembayaran')
        ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.id', 'tagihan.id_pendapatan_jasa')
        ->leftJoin('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
        ->where('tagihan.id_pelanggan', $request->id_pelanggan)
        ->where('tagihan.tanggal', $request->tanggal)
        ->first();

        $detail = DB::table('pembayaran')
        ->selectRaw('waktu, kode_bkm, jumlah_bayar as total_bayar, jenis, kas_bank.nama as bank')
        ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
        ->leftJoin('kas_bank', 'kas_bank.id', 'pembayaran.id_bank')
        ->where('id_pelanggan', $request->id_pelanggan)
        ->get();

        return view('jurnal-penerimaan-piutang/detail', compact('detail', 'pelanggan', 'nama'));
    }

    public function rekapitulasi (Request $request)
    {
        $request->validate([
            'tipe_pasien'=>'required',
            'tanggal'=>'required',
            'id_bank'=>'required',
        ]);

        $tipe_pasien = $request->tipe_pasien;
        $tanggal = $request->tanggal;
        $id_bank = $request->id_bank;
        //$id_asuransi = $request->id_asuransi;

        if ($tipe_pasien == null)
        {
            message(false, 'Gagal cari rekapitulasi Jurnal Penerimaan Piutang', 'Gagal cari rekapitulasi Jurnal Penerimaan Piutang');
            return redirect('jurnal-penerimaan-piutang/index');
        }

        if ($tipe_pasien == 2)
        {
            $rekapitulasi = DB::table('pembayaran')
            ->selectRaw('distinct pembayaran.id as id_pembayaran, kode_bkm, waktu, pelanggan.id as id_pelanggan,
            pembayaran.no_kunjungan, pelanggan.nama, pembayaran.total_tagihan, diskon,jumlah_bayar')
            ->leftJoin('pelanggan', 'pelanggan.id', 'pembayaran.id_pelanggan')
            ->where('tipe_pasien', '2')
            ->where('pembayaran.ref','N')
            ->where('pembayaran.id_bank', $id_bank)
            ->whereDate('pembayaran.waktu', $tanggal)
            ->simplePaginate(25);
        }

        if ($tipe_pasien == 1)
        {
           $rekapitulasi = DB::table('pembayaran')
            ->selectRaw('distinct pembayaran.id as id_pembayaran, kode_bkm, waktu, pelanggan.id as id_pelanggan, pembayaran.no_kunjungan,
            pelanggan.nama, pembayaran.total_tagihan, diskon, jumlah_bayar')
            ->leftJoin('pelanggan', 'pelanggan.id', 'pembayaran.id_pelanggan')
            ->where('pembayaran.id_bank', $id_bank)
            ->whereDate('pembayaran.waktu', $tanggal)
            ->where('pembayaran.ref','N')
            ->where('tipe_pasien', 1)
            ->simplePaginate(25);
        }

        return view('jurnal-penerimaan-piutang/rekapitulasi-jurnal-penerimaan-piutang',
        compact('rekapitulasi','id_bank','tanggal','tipe_pasien'));
    }

    public function jurnal (Request $request)
    {
        $tipe_pasien = $request->tipe_pasien;
        $tanggal = $request->tanggal;
        $id_pembayaran = $request->id_pembayaran;
        $tipe_jurnal = TipeJurnal::selectRaw('id, tipe_jurnal')->where('kode_jurnal', '=', 'CRJ')->first();
        $jurnal = Jurnal::selectRaw('CONCAT("CRJ-", SUBSTR(kode_jurnal, 5)+1) AS kode_jurnal')
        ->where('kode_jurnal', 'like', '%CRJ%')
        ->orderByDesc('id')
        ->first();

        if ($tipe_pasien ==2)
        {
            $kredit = DB::table('pembayaran')
            ->selectRaw("(SELECT perkiraan.id FROM setting_coa JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE jenis='Penagihan Piutang RJ'  AND setting_coa.tipe_pasien=2) AS id_perkiraan,

            (SELECT perkiraan.nama FROM setting_coa JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE jenis='Penagihan Piutang RJ'  AND setting_coa.tipe_pasien=2) AS rekening, 0 AS debit, sum(pembayaran.total_tagihan) AS kredit")
            ->whereDate('waktu', $tanggal)
            ->where('tipe_pasien', 2)
            ->where('pembayaran.ref', 'N')
            ->where('pembayaran.id_bank', $request->id_bank);

            $debet = DB::table('pembayaran')
            ->selectRaw('perkiraan.id AS id_perkiraan, perkiraan.nama AS rekening, sum(pembayaran.total_tagihan) as debit, 0 AS kredit')
            ->leftJoin('setting_coa', 'setting_coa.id_bank', 'pembayaran.id_bank')
            ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
            ->where('pembayaran.tipe_pasien', 2)
            ->whereDate('waktu', $tanggal)
            ->where('pembayaran.ref', 'N')
            ->where('pembayaran.id_bank', $request->id_bank)
            //->groupBy('setting_coa.id_perkiraan')
            ->unionAll($kredit)
            ->get();
        }

        if ($tipe_pasien == 1)
        {
            $kredit = DB::table('pembayaran')
            ->selectRaw("(SELECT perkiraan.id FROM setting_coa JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE jenis='Penagihan Piutang RI' AND setting_coa.tipe_pasien=1) AS id_perkiraan,

            (SELECT perkiraan.nama FROM setting_coa JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE jenis='Penagihan Piutang RI' AND setting_coa.tipe_pasien=1) AS rekening, 0 AS debit, sum(pembayaran.total_tagihan) AS kredit")
            ->where('tipe_pasien', 1)
            ->whereDate('waktu', $tanggal)
            ->where('pembayaran.ref', 'N')
            ->where('pembayaran.id_bank', $request->id_bank);

            $debet = DB::table('pembayaran')
            ->selectRaw('perkiraan.id AS id_perkiraan, perkiraan.nama AS rekening, sum(pembayaran.total_tagihan) as debit, 0 AS kredit ')
            ->leftJoin('setting_coa', 'setting_coa.id_bank', 'pembayaran.id_bank')
            ->leftJoin('perkiraan', 'perkiraan.id','setting_coa.id_perkiraan')
            ->where('pembayaran.tipe_pasien', 1)
            ->whereDate('waktu', $tanggal)
            ->where('pembayaran.ref', 'N')
            ->where('pembayaran.id_bank', $request->id_bank)
            ->groupBy('setting_coa.id_perkiraan')
            ->unionAll($kredit)
            ->get();
        }

        return view('jurnal-penerimaan-piutang/jurnal-umum', compact('tanggal','tipe_pasien','jurnal','tipe_jurnal','debet', 'id_pembayaran'));
    }

    public function simpanJurnalPenerimaanPiutang (Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request->balance > 0 || $request->balance === null)
            {
                message(false, '', 'Maaf tidak bisa input jurnal deposit karena debet dan kredit beda');
                return redirect('jurnal-deposit/index');
            }

            if ($request->id_pembayaran === null){
				message(false, 'Maaf tidak bisa input jurnal penerimaan piutang', 'Maaf tidak bisa input jurnal penerimaan piutang');
				return redirect ('jurnal-penerimaan-piutang/index');
            }

            if (isset($request->id_pembayaran))
            {
                $act = new Jurnal;
		        $act->kode_jurnal = $request->kode_jurnal;
		        $act->tanggal_posting = $request->tanggal;
		        $act->keterangan = $request->keterangan;
		        $act->id_tipe_jurnal = $request->id_tipe_jurnal;
		        $act->id_user = $request->id_user;
		        $act->save();

		        $id_jurnal = $act->id;
		        $data = $request->all();

                for ($i=0; $i<count($data['id_perkiraan']); $i++)
                {
			        $insert = array (
				        'id_jurnal'=>$id_jurnal,
                        'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'ref'=>'N',
				        'debet'=>$data['debet'][$i],
                        'kredit'=>$data['kredit'][$i],);

                    DetailJurnal::create($insert);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet',  $data['debet'][$i]);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit', $data['kredit'][$i]);
                }
                DB::table('pembayaran')->whereIn('id', $request->id_pembayaran)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);

                DB::commit();
                message($act, 'Jurnal Penerimaan Piutang berhasil disimpan', 'Jurnal Penerimaan Piutang gagal disimpan');
                return redirect ('jurnal-penerimaan-piutang/index');
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
        }
	}
}
