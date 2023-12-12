<?php

namespace App\Http\Controllers;
use App\pendapatan_jasa;
use App\Jurnal;
use App\transaksi;
use App\SettingCoa;
use App\Models\Perkiraan;
use App\Models\TipeJurnal;
use App\DetailJurnal;
use App\tagihan;
use DB;
use Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;

class JurnalPasienRiController extends Controller
{
    public function __construct()
	{
        $this->middleware('permission:read-jurnal-pasien-ri-pulang-rawat');
    }

	public function index ()
	{
		return view ('jurnal-pasien-ri-pulang-rawat/index');
	}

	public function rekapitulasi (Request $request)
	{
		$request->validate([
			'tanggal'=>'required',
			'tipe_pasien'=>'required',
		]);

		$tanggal = $request->tanggal;
		$tipe_pasien = $request->tipe_pasien;
		$rekapitulasi = DB::table('pendapatan_jasa')
		->selectRaw('pendapatan_jasa.id AS id_pendapatan_jasa, visit.waktu AS tanggal_kunjungan,
		pendapatan_jasa.no_kunjungan, pelanggan.nama as pasien, SUM(tagihan.piutang) AS tagihan')
		->leftJoin('pelanggan', 'pelanggan.id',  'pendapatan_jasa.id_pelanggan')
		->leftjoin('visit', 'visit.id', 'pendapatan_jasa.no_kunjungan')
		->leftJoin('tagihan', 'tagihan.id_pendapatan_jasa', 'pendapatan_jasa.id')
		->where('jenis', 'RI')
		//->where('visit.flag_discharge', 'Y')
		->where('tipe_pasien', $tipe_pasien)
		->where('waktu_pulang', $tanggal)
		->groupBy('pendapatan_jasa.no_kunjungan')
		->paginate(15);

		return view ('jurnal-pasien-ri-pulang-rawat/rekapitulasi', compact('rekapitulasi', 'tanggal', 'tipe_pasien'));
	}

	public function jurnal (Request $request)
	{
		$id_pendapatan_jasa = $request->id_pendapatan_jasa;
		$tipe_jurnal = TipeJurnal::find(5);
		$kode_jurnal = Jurnal::selectRaw('CONCAT("GJ-", SUBSTR(kode_jurnal, 5)+1) AS kode')
		->where('kode_jurnal', 'like', 'GJ%')
		->orderByDesc('id')
		->first();
		//untuk mendapat no jurnal setelah 1

		$kredit = DB::table('pendapatan_jasa')
		->selectRaw("(SELECT perkiraan.id FROM setting_coa  JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.jenis='Piutang Pasien Masih Dirawat RI' AND
		setting_coa.type='RI' AND setting_coa.tipe_pasien='$request->tipe_pasien') AS id_perkiraan,
		(SELECT perkiraan.nama FROM setting_coa  JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.jenis='Piutang Pasien Masih Dirawat RI' AND setting_coa.type='RI' AND
		setting_coa.tipe_pasien='$request->tipe_pasien') AS rekening, 0 AS debit ,  SUM(tagihan.piutang) AS kredit")
		->leftJoin('tagihan','tagihan.id_pendapatan_jasa','pendapatan_jasa.id')
		->leftJoin('visit','visit.id', 'tagihan.no_kunjungan')
		->where('jenis', 'RI')
		->where('flag_discharge','Y')
		->where('tipe_pasien', $request->tipe_pasien)
		->where('waktu_pulang', $request->tanggal);

		$debet = DB::table('pendapatan_jasa')
		->selectRaw("(SELECT perkiraan.id FROM setting_coa JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.jenis='Piutang Pasien Pulang Rawat RI' AND setting_coa.type='RI' AND
		setting_coa.tipe_pasien='$request->tipe_pasien') AS id_perkiraan,
		(SELECT perkiraan.nama FROM setting_coa JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
		WHERE setting_coa.jenis='Piutang Pasien Pulang Rawat RI' AND setting_coa.type='RI' AND
		setting_coa.tipe_pasien='$request->tipe_pasien') AS rekening, SUM(tagihan.piutang) AS debit, 0 AS kredit")
		->leftJoin('tagihan','tagihan.id_pendapatan_jasa','pendapatan_jasa.id')
		->leftJoin('visit','visit.id', 'tagihan.no_kunjungan')
		->where('jenis', 'RI')
		->where('flag_discharge','Y')
		->where('waktu_pulang', $request->tanggal)
		->unionAll($kredit)
		->get();

		return view ('jurnal-pasien-ri-pulang-rawat/jurnal', compact('debet', 'tipe_jurnal', 'kode_jurnal', 'id_pendapatan_jasa'));
	}

	public function simpan (Request $request)
	{
		$id_user = Auth::user()->id;
		DB::beginTransaction();

    	try {

			if ($request->id_pendapatan_jasa == null)
			{
				message(false, 'Gagal simpan jurnal pulang rawat', 'Gagal simpan jurnal pulang rawat');
				return redirect('jurnal-pasien-ri-pulang-rawat/index');
			}

			$act = new Jurnal;
			$act->kode_jurnal = $request->kode_jurnal;
			$act->tanggal_posting = $request->tanggal;
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

				DetailJurnal::insert($insert);
				transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet',  $data['debet'][$i]);
				transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit', $data['kredit'][$i]);
			}

			DB::table('pendapatan_jasa')->whereIn('id', $request->id_pendapatan_jasa)->update(['ref_discharge'=>'Y', 'no_jurnal'=>$id_jurnal]);

			DB::commit();
			message($act, 'Jurnal Penagihan Pasien Rawat Inap Berhasil disimpan', 'Jurnal Penagihan Pasin Rawat Inap Gagal Disimpan');
			return redirect ('jurnal-pasien-ri-pulang-rawat/index');


		} catch (Exception $e) {
			DB::rollback();
			message(false, '', 'Gagal simpan');
			return redirect ('jurnal-pasien-ri-pulang-rawat/index');
		}
	}
}
