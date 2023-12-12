<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\PendapatanJasa;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\pendapatan_jasa_langganan;
use App\detail_pendapatan_jasa;
use App\Models\PeriodeKeuangan;
use App\BukuBesarPembantu;
use App\Models\Unit;
use App\Models\Nakes;
use App\Models\KasBank;
use App\Models\Layanan;
use App\tarif;
use App\Tagihan;
use App\SettingCoa;
use DB;
use App\ArusKas;
use App\MutasiKas;
use Auth;
use App\Models\ProdukAsuransi;
use App\visit;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Datatables;

class PendapatanJasaController extends Controller
{
    public $viewDir = "pendapatan_jasa";
    public $breadcrumbs = array(
        'permissions' => array('title' => 'Pendapatan-jasa', 'link' => "#", 'active' => false, 'display' => true),
    );

    public function __construct()
    {
        $this->middleware('permission:read-pendapatan-jasa');
    }

    public function index()
    {
        $visit = visit::select('id', 'id_pelanggan', 'waktu')->get();
        $bank = KasBank::select('id', 'nama')->get();
        $asuransi = ProdukAsuransi::select('id', 'nama')->get();
        $unit = Unit::select('id', 'nama')->get();
        $nakes = Nakes::select('id', 'nama')->get();
        $periodeKeuangan = PeriodeKeuangan::where('status_aktif', 'Y')->first();
        $MutasiKas = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')
        ->where('kode', 'like', 'BKM%')
        ->orderByDesc('id')
        ->first();

        $bukti_transaksi = PendapatanJasa::selectRaw('concat("PJ-", substr(no_bukti_transaksi, 4) +1) as bukti_transaksi')
        ->orderByDesc('id')
        ->first();

        $tarif = DB::table('tarif')->select('tarif.id', 'kelas.nama as kelas', 'layanan.nama as layanan')
        ->leftJoin('kelas', 'kelas.id', 'tarif.id_kelas')
        ->leftJoin('layanan', 'layanan.id', 'tarif.id_layanan')
        ->get();

        return $this->view("index", compact('visit', 'bank', 'asuransi', 'periodeKeuangan', 'unit', 'bukti_transaksi', 'nakes', 'tarif', 'MutasiKas'));
    }

    public function isiTarif($id_layanan)
    {
        $data = DB::table('tarif')
        ->selectRaw(' total AS tarif, jasa_sarana, bhp, alkes, kr, ulup, adm,
        (total_utama *persen_nakes_utama/100)+(total_pendamping * persen_nakes_pendamping/100)+(total_pendukung * persen_nakes_pendukung/100) AS jasa_medis,
        (total_utama *persen_nakes_utama/100)+(total_pendamping * persen_nakes_pendamping/100)+(total_pendukung * persen_nakes_pendukung/100) AS jasa_rs')
        ->where('id', $id_layanan)
        ->first();

        echo json_encode($data);
        exit;
    }

    public function isiPasien($no_kunjungan)
    {
        $data = visit::select('visit.id', 'id_pelanggan', 'pelanggan.nama', 'visit.flag_discharge')
        ->join('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
        ->where('visit.id', $no_kunjungan)
        ->first();

        $pembayaran_deposit = Deposit::selectRaw("(kredit - pemakaian) as deposit")
        ->where('id', Deposit::where("id_visit", $no_kunjungan)->max("id"))
        ->first();

        $deposit = $pembayaran_deposit && $pembayaran_deposit->deposit ? $pembayaran_deposit->deposit : 0;
        $data = array_merge($data->toArray(), array('deposit' => $deposit));

        echo json_encode($data);
        exit;
    }

    public function SimpanPendapatanJasa (Request $request)
    {
        $id_user = Auth::user()->id;
        $sekarang = Carbon::now();
        $data = $request->all();

        DB::beginTransaction();

        try {

            $request->validate([
                'no_bukti_transaksi'=>'required',
                'no_kunjungan'=>'required',
                'tanggal'=>'required',
                'id_pelanggan'=>'required',
                'jenis'=>'required',
                'tipe_pembayaran'=>'required',
                'sisa_bayar'=>'required',
                //'id_periode'=>'required',
                'dibayar'=>'required',
            ]);

            $bayar_deposit = str_replace(',', '', $request->bayar_deposit);

            if ($request->id_asuransi == null):
        
                $act = new PendapatanJasa;
                $act->keterangan = '2';
                $act->no_bukti_transaksi = $request->no_bukti_transaksi;
                $act->no_kunjungan = $request->no_kunjungan;
                $act->tanggal = $request->tanggal;
                $act->id_pelanggan = $request->id_pelanggan;
                $act->jenis = $request->jenis;
                $act->tipe_bayar = $request->tipe_pembayaran;
                $act->tipe_pasien = 2; // antar unit
                $act->id_user = $id_user;
                $act->total_tagihan = str_replace(',', '', $request->sisa_bayar);
                $act->dibayar = str_replace(',', '', $request->dibayar);
                $act->id_bank = $request->id_bank;
                $act->discharge = 'N';
                $act->waktu_pulang = '0000-00-00';
                $act->deposit = str_replace(',', '', $request->bayar_deposit);
                $act->charge = str_replace(',', '', $request->biaya_charge);
                $act->adm = str_replace(',', '', $request->biaya_adm);
                $act->materai = str_replace(',', '', $request->biaya_materai);
                $act->flag_ak = 'Y';
                $act->save();

            else:

                $act = new PendapatanJasa;
                $act->keterangan = '2';
                $act->no_bukti_transaksi = $request->no_bukti_transaksi;
                $act->no_kunjungan = $request->no_kunjungan;
                $act->tanggal = $request->tanggal;
                $act->id_pelanggan = $request->id_pelanggan;
                $act->jenis = $request->jenis;
                $act->tipe_bayar = $request->tipe_pembayaran;
                $act->tipe_pasien = 1; // perusahaan langganan
                $act->id_user = $id_user;
                $act->total_tagihan = str_replace(',', '', $request->sisa_bayar);
                $act->dibayar = str_replace(',', '', $request->dibayar);
                $act->id_bank = $request->id_bank;
                $act->discharge = 'N';
                $act->waktu_pulang = '0000-00-00';
                $act->deposit = str_replace(',','', $request->bayar_deposit);
                $act->charge = str_replace(',', '', $request->biaya_charge);
                $act->adm = str_replace(',', '', $request->biaya_adm);
                $act->materai = str_replace(',', '', $request->biaya_materai);
                $act->flag_ak = 'Y';
                $act->save();

            $id_pendapatan_jasa = $act->id;
            $pendapatan_jasa_langganan = new pendapatan_jasa_langganan;
            $pendapatan_jasa_langganan->id_pendapatan_jasa = $id_pendapatan_jasa;
            $pendapatan_jasa_langganan->id_asuransi_produk = $request->id_asuransi;
            $pendapatan_jasa_langganan->perusahaan = $request->perusahaan;
            $pendapatan_jasa_langganan->save();
        endif;

        $id_pendapatan_jasa = $act->id;

        for ($i=0; $i<count($data['id_unit']); $i++):
            $tarif = str_replace(',', '', $data['tarif'][$i]);

			$insert = array (
				'id_pendapatan_jasa'=>$id_pendapatan_jasa,
				'id_unit'=>$data['id_unit'][$i],
                'id_nakes_1'=>$data['id_nakes_1'][$i],
                'id_nakes_2'=>$data['id_nakes_2'][$i],
                'id_nakes_3'=>$data['id_nakes_3'][$i],
                'id_tarif'=>$data['id_layanan'][$i],
                'jasa_sarana'=>$data['jasa_sarana'][$i],
                'bhp'=>$data['bhp'][$i],
                'jasa_medis'=>$data['jasa_medis'][$i],
                'jasa_rs'=>$data['jasa_rs'][$i],
                'alkes'=>$data['alkes'][$i],
                'kr'=>$data['alkes'][$i],
                'ulup'=>$data['ulup'][$i],
                'adm'=>$data['adm'][$i],
                'tarif'=>$tarif,
                'ref'=>'N',);
			detail_pendapatan_jasa::insert($insert);
        endfor;

        $setting_coa = SettingCoa::where('keterangan', 'Piutang')->where('jenis',  'Penagihan Piutang RI')->first();
        $arusKas = ArusKas::where('nama', 'Kas dari pendapatan jasa')->first();

        if ($request->tipe_pembayaran == 'Kredit' && $setting_coa == null):
            message(false, '', 'Gagal simpan pendapatan jasa karena setting coa kosong');
            return redirect ('pendapatan-jasa');
        endif;

        if ($request->tipe_pembayaran == 'Kredit' && $setting_coa != null):
            //get id perkiraan dengan keterangan piutang dan jenis penagihan piutang RI
            $BukuBesarPembantu = new BukuBesarPembantu;
            $BukuBesarPembantu->tanggal = $request->tanggal;
            $BukuBesarPembantu->id_pelanggan = $request->id_pelanggan;
            $BukuBesarPembantu->id_periode = $request->id_periode;
            $BukuBesarPembantu->id_perkiraan = $setting_coa->id_perkiraan;
            $BukuBesarPembantu->keterangan = 'Piutang';
            $BukuBesarPembantu->debet = str_replace(',', '', $request->total);
            $BukuBesarPembantu->id_pendapatan_jasa = $id_pendapatan_jasa;
            $BukuBesarPembantu->kredit = 0;
            $BukuBesarPembantu->user_input = $id_user;
            $BukuBesarPembantu->save();

            $detail_pendapatan_jasa = DB::table('detail_pendapatan_jasa')
            ->selectRaw('detail_pendapatan_jasa.id, jasa_medis, jasa_rs+jasa_sarana+bhp AS tindakan, alkes,kr, ulup, detail_pendapatan_jasa.adm,
            tarif, tarif AS piutang, id_unit, no_kunjungan')
            ->join('pendapatan_jasa', 'pendapatan_jasa.id', 'detail_pendapatan_jasa.id_pendapatan_jasa')
            ->where('id_pendapatan_jasa', $id_pendapatan_jasa)
            ->get();

            foreach ($detail_pendapatan_jasa as $detail_pendapatan_jasa_id):
        
                $id_detail_pendapatan_jasa = $detail_pendapatan_jasa_id->id;
                $no_kunjungan = $detail_pendapatan_jasa_id->no_kunjungan;
                $id_unit = $detail_pendapatan_jasa_id->id_unit;
                $tarif = $detail_pendapatan_jasa_id->tarif;
                $dokter = $detail_pendapatan_jasa_id->jasa_medis;
                $tindakan = $detail_pendapatan_jasa_id->tindakan;
                $alkes = $detail_pendapatan_jasa_id->alkes;
                $kr = $detail_pendapatan_jasa_id->kr;
                $ulup = $detail_pendapatan_jasa_id->ulup;
                $adm = $detail_pendapatan_jasa_id->adm;
                $piutang = $detail_pendapatan_jasa_id->piutang;

                $save = array (
                    'id_pendapatan_jasa'=>$id_pendapatan_jasa,
                    'id_detail_pendapatan_jasa'=>$id_detail_pendapatan_jasa,
                    'tanggal'=>$request->tanggal,
                    'no_kunjungan'=>$no_kunjungan,
                    'id_unit'=>$id_unit,
                    'dokter'=>$dokter,
                    'tindakan'=>$tindakan,
                    'alkes'=>$alkes,
                    'kr'=>$alkes,
                    'kr'=>$kr,
                    'ulup'=>$ulup,
                    'adm'=>$adm,
                    'piutang'=>$piutang,
                    'tarif'=>$tarif,
                    'id_pelanggan'=>$request->id_pelanggan,
                    'type'=>$request->jenis,
                    'status_tagihan'=>'N',
                    'id_user'=>$id_user,
                    'ref'=>'N',
                    'no_jurnal'=>0,);

                Tagihan::insert($save); // insert tabel tagihan
            endforeach;
        endif;

            if ($request->tipe_pembayaran == 'Tunai' && isset($arusKas)):
            
                $mutasiKas = new MutasiKas;
                $mutasiKas->kode = $request->kode_mutasi_kas;
                $mutasiKas->id_arus_kas = $arusKas->id;
                $mutasiKas->tanggal = $request->tanggal;
                $mutasiKas->id_perkiraan = 1;
                $mutasiKas->id_kas_bank = $request->id_bank;
                $mutasiKas->id_pendapatan_jasa = $id_pendapatan_jasa;
                $mutasiKas->nominal = str_replace(',', '', $request->dibayar);
                $mutasiKas->tipe = 2;
                $mutasiKas->user_input = $id_user;
                $mutasiKas->save();
            endif;

            if ($request->bayar_deposit > 0):
                Deposit::where('id_visit', $request->no_kunjungan)->whereDate('waktu', $sekarang)->increment('pemakaian', $bayar_deposit);
            endif;

            DB::commit();
            message(true, 'Pendapatan Jasa berhasil disimpan', 'Pendapatan Jasa gagal disimpan');
            return redirect ('pendapatan-jasa');
        }
        catch (Exception $e){
            DB::rollback();
            message(false, 'Pendapatan Jasa gagal disimpan', 'Pendapatan Jasa gagal disimpan');
            return redirect ('pendapatan-jasa');
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }
}
