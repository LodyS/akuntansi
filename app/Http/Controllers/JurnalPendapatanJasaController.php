<?php

namespace App\Http\Controllers;
use App\pendapatan_jasa;
use App\Jurnal;
use App\transaksi;
use App\SettingCoa;
use App\Models\Layanan;
use App\Models\Kelas;
use App\detail_pendapatan_jasa;
use App\Models\Unit;
use App\Models\Perkiraan;
use App\Models\PeriodeKeuangan;
use App\Models\ProdukAsuransi;
use App\Models\TipeJurnal;
use App\tipe_pasien;
use App\Voucher;
use App\Models\KasBank;
use App\DetailJurnal;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JurnalPendapatanJasaController extends Controller
{
	public function __construct()
	{
        $this->middleware('permission:read-jurnal-pendapatan-jasa');
    }

	public function index ()
	{
		$tipe = tipe_pasien::all();
		$bank = KasBank::select('id', 'nama')->get();

		return view('jurnal-pendapatan-jasa/index', compact('tipe', 'bank'));
	}

	public function Rekapitulasi (Request $request)
	{
		$tipe_pembayaran = $request->tipe_pembayaran;
		$tanggal = $request->tanggal;
		$id_bank = $request->id_bank;
		$jenis_pasien = $request->jenis_pasien;
        $tipe_pasien = tipe_pasien::find($request->tipe_pasien);

		$rekapitulasi = DB::table('pendapatan_jasa')
		->selectRaw('pendapatan_jasa.id as id_pendapatan_jasa, pelanggan.nama as pasien, total_tagihan')
		->join('pelanggan', 'pelanggan.id', 'pendapatan_jasa.id_pelanggan')
		->where('tanggal', $tanggal)
		->where('pendapatan_jasa.tipe_pasien', $tipe_pasien->id)
		->where('pendapatan_jasa.jenis', $jenis_pasien)
		->where('pendapatan_jasa.tipe_bayar', $tipe_pembayaran)
		->where('ref_discharge', 'N')
		->simplePaginate(25);

		return view ('jurnal-pendapatan-jasa/rekapitulasi-pendapatan-jasa',
		compact('rekapitulasi', 'jenis_pasien','tipe_pasien', 'tanggal', 'tipe_pembayaran'));
	}

	public function detail (Request $request)
	{
		$detail = pendapatan_jasa::selectRaw('pendapatan_jasa.id as id_pendapatan_jasa, pelanggan.nama as pelanggan, pendapatan_jasa.tanggal,
		tipe_bayar, tipe_pasien, jenis, total_tagihan, adm, materai, biaya_kirim, kas_bank.nama as bank,
		 total_tagihan - pendapatan_jasa.deposit as pembayaran, pendapatan_jasa.deposit')
		->leftJoin('pelanggan', 'pelanggan.id', 'pendapatan_jasa.id_pelanggan')
		->leftJoin('kas_bank', 'kas_bank.id', 'pendapatan_jasa.id_bank')
		->where('pendapatan_jasa.id', $request->id_pendapatan_jasa)
		->firstOrFail();

		$detail_pendapatan_jasa = detail_pendapatan_jasa::selectRaw('layanan.nama as layanan, tarif')
		->leftJoin('tarif', 'tarif.id', 'detail_pendapatan_jasa.id_tarif')
		->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
		->where('id_pendapatan_jasa', $request->id_pendapatan_jasa)
		->get();

		return view ('jurnal-pendapatan-jasa/detail', compact('detail', 'detail_pendapatan_jasa'));
	}

	public function Jurnal (Request $request)
	{
		$id_pendapatan_jasa = $request->id_pendapatan_jasa;
		$kode_jurnal = Jurnal::selectRaw('CONCAT("SJ-", SUBSTR(kode_jurnal, 5)+1) AS kode')
		->where('kode_jurnal', 'like', 'SJ%')
		->orderByDesc('id')
		->first();
		// untuk mendapat urutan kode jurnal
		$tipe_jurnal = Tipejurnal::find(3); //id 3 untuk kode tipe jurnal pendapatan jasa

        if ($request->tipe_pembayaran == "Kredit"):
            # code...
            $jenis_coa = $request->jenis=='RJ' ? 'Pasien Masih Dirawat RJ' : 'Piutang Pasien Masih Dirawat RI';

            $jurnal = DB::select("select pk.id AS id_perkiraan, pk.nama AS perkiraan, p.total_tagihan AS debet , 1-1 AS kredit
			FROM pendapatan_jasa p
            join setting_coa s on s.tipe_pasien=p.tipe_pasien
            join perkiraan pk on pk.id=s.id_perkiraan
            where s.keterangan='Piutang' and s.type='$request->jenis' AND s.jenis='$jenis_coa'
			and p.tanggal='$request->tanggal' AND p.tipe_pasien='$request->tipe_pasien' AND p.jenis='$request->jenis' AND p.tipe_bayar='Kredit'
			AND p.id='$request->id_pendapatan_jasa'
			HAVING pk.id is not null

			UNION ALL

			SELECT pk.id as id_perkiraan, pk.nama as perkiraan, 1-1 as debet, (
			select
			(case s.keterangan
			when 'Biaya Administrasi' then p.adm
			when 'Biaya Materai' then p.materai
			when 'Charge' then p.charge
			when 'Biaya Kirim' then p.biaya_kirim end) AS kredit
			FROM pendapatan_jasa p
			WHERE
			p.tanggal='$request->tanggal' AND
			p.tipe_pasien='$request->tipe_pasien' AND
			p.jenis='$request->jenis' AND
			p.tipe_bayar='Kredit' AND
			p.id='$request->id_pendapatan_jasa') as kredit
            FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan in ('Biaya Administrasi','Biaya Materai','Charge','Biaya Kirim')

			UNION ALL

			SELECT pk.id AS id_perkiraan, pk.nama AS perkiraan ,1-1 AS debet , d.tarif AS kredit FROM pendapatan_jasa p
			JOIN detail_pendapatan_jasa d ON p.id=d.id_pendapatan_jasa
			JOIN tarif t ON t.id=d.id_tarif
			JOIN setting_coa s ON s.id_tarif=d.id_tarif
			JOIN perkiraan pk ON pk.id=s.id_perkiraan
			WHERE p.tanggal='$request->tanggal' AND p.tipe_pasien='$request->tipe_pasien'  AND p.jenis='$request->jenis' AND p.tipe_bayar='Kredit'
			AND s.tipe_pasien='$request->tipe_pasien' AND s.type_bayar='Kredit' AND s.type='$request->jenis'  AND p.id='$request->id_pendapatan_jasa'
			HAVING pk.id is not null");
			// dd($jurnal);
        else:
            # code...
			$jurnal = DB::select("SELECT pk.id AS id_perkiraan, pk.nama AS perkiraan,
			(SUM(dj.tarif)+ IFNULL((p.charge),0) +IFNULL((p.adm),0) + IFNULL((p.materai),0) + IFNULL(( p.biaya_kirim),0))-(p.deposit) AS debet, 1-1 AS kredit
			FROM pendapatan_jasa p
			JOIN detail_pendapatan_jasa dj ON dj.id_pendapatan_jasa=p.id
			JOIN setting_coa s ON s.id_bank=p.id_bank
			JOIN perkiraan pk ON pk.id=s.id_perkiraan
			WHERE p.id='$request->id_pendapatan_jasa'

			UNION ALL

			SELECT
			(SELECT pk.id FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Biaya Administrasi') AS perkiran,
			(SELECT pk.nama FROM setting_coa s  JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Deposit'),
			SUM(p.deposit) AS debet , 1-1 AS kredit FROM pendapatan_jasa p
			WHERE p.id='$request->id_pendapatan_jasa'

			UNION ALL

			SELECT
			(SELECT pk.id FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Biaya Administrasi') AS id_perkiraan,
			(SELECT pk.nama FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Biaya Administrasi') AS perkiraan,
			1-1 AS debet , SUM(p.adm) AS kredit FROM pendapatan_jasa p
			WHERE   p.id='$request->id_pendapatan_jasa'

			UNION  ALL

			SELECT
			(SELECT pk.id FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Biaya Materai') AS id_perkiraan,
			(SELECT pk.nama FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Biaya Materai') AS perkiraan,
			1-1 AS debet , SUM(p.materai) AS kredit FROM pendapatan_jasa p
			WHERE  p.id='$request->id_pendapatan_jasa'

			UNION ALL

			SELECT
			(SELECT pk.id FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Charge') AS id_perkiraan,
			(SELECT pk.nama FROM setting_coa s JOIN perkiraan pk ON pk.id=s.id_perkiraan WHERE s.keterangan='Charge') AS perkiraan,
			1-1 AS debet , (p.charge) AS kredit FROM pendapatan_jasa p
			WHERE  p.id='$request->id_pendapatan_jasa'

			UNION ALL
			SELECT pk.id AS id_perkiraan, pk.nama AS perkiraan, 1-1 AS debet , d.tarif AS kredit FROM pendapatan_jasa p
			JOIN detail_pendapatan_jasa d ON p.id=d.id_pendapatan_jasa
			JOIN tarif t ON t.id=d.id_tarif
			JOIN setting_coa s ON s.id_tarif=d.id_tarif
			JOIN perkiraan pk ON pk.id=s.id_perkiraan
			WHERE s.tipe_pasien=2 AND s.type_bayar='Tunai' AND s.type='$request->jenis'  AND p.id='$request->id_pendapatan_jasa'");
			
		endif;

		return view ('jurnal-pendapatan-jasa/jurnal-umum', compact('tipe_jurnal', 'jurnal', 'kode_jurnal', 'id_pendapatan_jasa'));
	}

	public function Simpan (Request $request)
	{
        if ($request->id_perkiraan):
            if (in_array(null, $request->id_perkiraan)):
                message(false, '', 'Maaf Nama Perkiraan tidak boleh ada yang kosong');
                return back();
			endif;
        else:
            message(false, 'Maaf tidak bisa input jurnal pendapatan jasa', 'Maaf tidak bisa input jurnal pendapatan jasa');
            return back();
		endif;

        $debet = array_sum($request->debet);
        $kredit = array_sum($request->kredit);
        if ($debet != $kredit):
            message(false, '', 'Maaf tidak bisa input jurnal deposit karena debet dan kredit beda');
            return back();
		endif;

		$id_user = Auth::user()->id;
		DB::beginTransaction();

		try {

			if (isset($request->id_perkiraan)):
			
				$act = new Jurnal;
				$act->no_dokumen = $request->no_dokumen;
				$act->kode_jurnal = $request->kode_jurnal;
				$act->tanggal_posting = $request->tanggal;
				$act->keterangan = $request->keterangan;
				$act->id_tipe_jurnal = $request->tipe_jurnal;
				$act->id_user = $id_user;
				$act->save();

				$id_jurnal = $act->id;
                $data = $request->all();

                for ($i=0; $i<count($data['id_perkiraan']); $i++):
                
                    $insert = array (
                        'id_jurnal'=>$id_jurnal,
                        'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'debet'=>$data['debet'][$i],
                        'kredit'=>$data['kredit'][$i],
                        'ref'=>'N'
					);

                    DetailJurnal::create($insert);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet', $data['debet'][$i]);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit', $data['kredit'][$i]); // update table transaksi
				
				endfor;

                $kode_tgl = 'KD.'.date('Ymd').'.';
                // $kode_akhir = Voucher::where('kode', 'like', $kode_tgl.'%')->max('kode');
                $kode_akhir = Voucher::where('kode', 'like', $kode_tgl.'%')->max('kode');
                $kode_baru = $kode_akhir ? $kode_tgl . (intval(substr($kode_akhir,12)) + 1) : $no_baru = $kode_tgl . '1';

                $voucher = new Voucher;
                $voucher->kode = $kode_baru;
                $voucher->id_jurnal = $id_jurnal;
                $voucher->save();

				DB::table('pendapatan_jasa')->where('id', $request->id_pendapatan_jasa)->update(['ref_discharge'=>'Y', 'no_jurnal'=>$id_jurnal]);
				//untuk update table pendapatan jasa untuk update kolom ref dan no jurnal setelah input jurnal
				DB::commit();
				message($act, 'Jurnal Pendapatan Jasa Berhasil disimpan', 'Jurnal Pendapatan Jasa Gagal disimpan');
				return redirect ('jurnal-pendapatan-jasa/index');

			endif;
		}
		catch (Exception $e){
			DB::rollback();
            message(false, '', 'Error system');
            return back();
		}
	}
}
