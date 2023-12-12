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
use App\Models\ProdukAsuransi;
use App\Models\TipeJurnal;
use App\tipe_pasien;
use App\Models\KasBank;
use App\DetailJurnal;
use App\tagihan;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JurnalPenagihanPiutangController extends Controller
{
    public function __construct()
	{
        $this->middleware('permission:read-jurnal-penagihan-piutang');
    }

	public function index ()
	{
		$asuransi = ProdukAsuransi::select('id', 'nama')->get();
		return view('jurnal-penagihan-piutang/index', compact('asuransi'));
	}

	public function rekapitulasiPenagihan (Request $request)
	{
		$request->validate([
			'tanggal'=>'required',
			'tipe_pasien'=>'required',
			'jenis'=>'required',
		]);

		$tanggal = $request->tanggal;
		$tipe_pasien = $request->tipe_pasien;
		$jenis = $request->jenis;

		if ($tanggal == null || $tipe_pasien == null || $jenis == null)
		{
			message(false, '', 'Ada field yang kosong');
			return redirect('jurnal-penagihan-piutang/index');
		}

		$rekapitulasi = DB::table('pendapatan_jasa')
		->selectRaw('pendapatan_jasa.id as id_pendapatan_jasa, pendapatan_jasa.no_kunjungan, tagihan.tanggal, tindakan AS dokter, tindakan, lab,
		usg_ekg, obat, tagihan.tarif + penjualan.total_tagihan AS total')
		->leftJoin('tagihan', 'tagihan.id_pendapatan_jasa', 'pendapatan_jasa.id')
		->leftJoin('pendapatan_jasa_langganan', 'pendapatan_jasa_langganan.id_pendapatan_jasa', 'pendapatan_jasa.id')
		->leftJoin('penjualan', 'penjualan.id_produk_asuransi', 'pendapatan_jasa_langganan.id_asuransi_produk')
		->where('tagihan.ref', 'N')
		->where('pendapatan_jasa.jenis', $jenis)
		->where('pendapatan_jasa.tipe_pasien', $tipe_pasien)
		->where('tagihan.tanggal', $tanggal)
		->where('tagihan.status_tagihan', 'Y')
		->paginate(25);

		return view ('jurnal-penagihan-piutang/rekapitulasi-penagihan-piutang', compact('rekapitulasi', 'jenis', 'tanggal', 'tipe_pasien'));
	}

	public function JurnalUmum (Request $request)
	{
		$tipe_jurnal = TipeJurnal::find(3);
		$id_pendapatan_jasa = $request->id_pendapatan_jasa;
		$tanggal = $request->tanggal;
		$tipe_pasien = $request->tipe_pasien;
		$jenis = $request->jenis_pasien;
		$kode_jurnal = Jurnal::selectRaw('CONCAT("SJ-", SUBSTR(kode_jurnal, 5)+1) AS kode')
		->where('kode_jurnal', 'like', 'SJ%')
		->orderByDesc('id')
		->first();

		$kredit_kedua = DB::table('tagihan')
		->selectRaw("(SELECT id FROM perkiraan WHERE id='56') AS id_perkiraan,
		(SELECT nama FROM perkiraan WHERE id=56) AS perkiraan, 0 AS debit, SUM(piutang) AS kredit")
		->leftJoin('pendapatan_jasa', 'pendapatan_jasa.id', 'tagihan.id_pendapatan_jasa')
		->where('tagihan.ref', 'N')
		->where('tagihan.type', $jenis)
		->where('pendapatan_jasa.tipe_pasien', $tipe_pasien)
		->where('pendapatan_jasa.tanggal', $tanggal);

		$debet_kedua = DB::table('tagihan')
		->selectRaw("CASE
		WHEN tagihan.type='RI' THEN (SELECT perkiraan.id FROM setting_coa JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
		WHERE keterangan='piutang' AND jenis='Pelunasan Piutang RI' AND tipe_pasien='$request->tipe_pasien')
		WHEN tagihan.type='RJ' THEN (SELECT perkiraan.id FROM setting_coa JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
		WHERE keterangan='piutang' AND jenis='Pelunasan Piutang RJ' AND tipe_pasien='$request->tipe_pasien')
		END AS id_perkiraan,

		CASE
		WHEN tagihan.type='RI' THEN (SELECT perkiraan.nama FROM setting_coa JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
		WHERE keterangan='piutang' AND jenis='Pelunasan Piutang RI' AND tipe_pasien='$request->tipe_pasien')
		WHEN tagihan.type='RJ' THEN (SELECT perkiraan.nama FROM setting_coa JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
		WHERE keterangan='piutang' AND jenis='Pelunasan Piutang RJ' AND tipe_pasien='$tipe_pasien')
		END AS perkiraan,
		SUM(piutang) AS debit, 0 AS kredit")
		->leftJoin('pendapatan_jasa', 'pendapatan_jasa.id', 'tagihan.id_pendapatan_jasa')
		->where('tagihan.type', $jenis)
		->where('pendapatan_jasa.tipe_pasien', $tipe_pasien)
		->where('pendapatan_jasa.tanggal', $tanggal)
		->where('tagihan.ref', 'N');

		$kredit = DB::table('tagihan')
		->selectRaw("CASE
		WHEN tagihan.type= 'RJ' THEN (SELECT perkiraan.id  FROM setting_coa  JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.keterangan='piutang' AND setting_coa.jenis='Pasien Masih Dirawat RJ' AND setting_coa.type='RJ' AND tipe_pasien='$tipe_pasien')
		WHEN tagihan.type= 'RI' THEN (SELECT perkiraan.id  FROM setting_coa  JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.keterangan='piutang' AND setting_coa.jenis='Piutang Pasien Masih Dirawat RI'
		AND setting_coa.type='RI' AND tipe_pasien='$tipe_pasien')
		END AS id_perkiraan,

		CASE
		WHEN tagihan.type= 'RJ' THEN (SELECT perkiraan.nama  FROM setting_coa  JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.keterangan='piutang' AND setting_coa.jenis='Pasien Masih Dirawat RJ' AND setting_coa.type='RJ' AND tipe_pasien='$tipe_pasien')
		WHEN tagihan.type= 'RI' THEN (SELECT perkiraan.nama  FROM setting_coa  JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.keterangan='piutang' AND setting_coa.jenis='Piutang Pasien Masih Dirawat RI'
		AND setting_coa.type='RI' AND tipe_pasien='$tipe_pasien')
		END AS perkiraan, 0 AS debit, SUM(piutang) AS kredit")
		->leftJoin('pendapatan_jasa', 'pendapatan_jasa.id', 'tagihan.id_pendapatan_jasa')
		->where('tagihan.type', $jenis)
		->where('pendapatan_jasa.tipe_pasien', $tipe_pasien)
		->where('pendapatan_jasa.tanggal', $tanggal)
		->where('tagihan.ref', 'N');

		$debet = DB::table('tagihan')
		->selectRaw('(SELECT id FROM perkiraan  WHERE id=56) AS id_perkiraan, (SELECT nama FROM perkiraan  WHERE id=56) AS perkiraan,
		SUM(piutang) AS debit, 0 AS kredit')
		->leftJoin('pendapatan_jasa', 'pendapatan_jasa.id', 'tagihan.id_pendapatan_jasa')
		->where('tagihan.type', $jenis)
		->where('pendapatan_jasa.tipe_pasien', $tipe_pasien)
		->where('pendapatan_jasa.tanggal', $tanggal)
		->where('tagihan.ref', 'N')
		->unionAll($kredit_kedua)
		->unionAll($debet_kedua)
		->unionAll($kredit)
		->get();

		return view ('jurnal-penagihan-piutang/jurnal-umum', compact('debet', 'kode_jurnal','tipe_jurnal', 'tanggal', 'id_pendapatan_jasa'));
	}

	public function simpanJurnalPenagihanPiutang (Request $request){

		$id_user = Auth::user()->id;
		DB::beginTransaction();

    	try {

			if ($request->id_pendapatan_jasa == null)
			{
				message(false, 'Gagal simpan jurnal penagihan piutang', 'Gagal simpan jurnal penagihan piutang');
				return back();
			}

			if ($request->balance > 0)
            {
                message(false, '', 'Maaf tidak bisa input jurnal deposit karena debet dan kredit beda');
                return back();
            }

			if (in_array(null, $request->id_perkiraan))
			{
				message(false, '', 'Maaf Nama Perkiraan tidak boleh ada yang kosong');
				return back();
			}

			if (isset($request->id_pendapatan_jasa))
			{
				$act = new Jurnal;
				$act->kode_jurnal = $request->kode_jurnal;
				$act->tanggal_posting = $request->tanggal_posting;
				$act->keterangan = $request->keterangan;
				$act->id_tipe_jurnal = $request->id_tipe_jurnal;
				$act->id_user = $id_user;
				$act->save();

				$id_jurnal = $act->id;
				$data = $request->all();

				for ($i=0; $i<count($data['id_perkiraan']); $i++)
				{
					$insert = array (
						'id_jurnal'=>$id_jurnal,
						'id_perkiraan'=>$data['id_perkiraan'][$i],
						'debet'=>$data['debet'][$i],
						'kredit'=>$data['kredit'][$i],
						'ref'=>'N',);

					DetailJurnal::create($insert);
					transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet', $data['debet'][$i]);
                	transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit', $data['kredit'][$i]);
				}

				tagihan::whereIn('id_pendapatan_jasa', $request->id_pendapatan_jasa)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);
				//update tabel pendapatan jasa kolom ref dan no_jurnal
				DB::commit();
				message($act, 'Jurnal Penagihan Piutang berhasil disimpan', 'Jurnal Penagihan Piutang gagal disimpan');
				return redirect ('jurnal-penagihan-piutang/index');
			}
		} catch (Exception $e) {
			DB::rollback();
			message(false, '', 'Jurnal Penagihan Piutang gagal disimpan');
			return redirect ('jurnal-penagihan-piutang/index');
		}
	}
}
