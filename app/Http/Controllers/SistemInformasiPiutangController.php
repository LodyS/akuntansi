<?php

namespace App\Http\Controllers;
use App\penjualan;
use App\penjualan_resep;
use App\pembayaran;
use App\DetailPembayaran;
use App\Models\Pelanggan;
use App\Models\Perkiraan;
use App\tipe_pasien;
use App\visit;
use Auth;
use App\pendapatan_jasa;
use App\Tagihan;
use App\Models\ProdukAsuransi;
use App\pendapatan_jasa_langganan;
use App\Models\PeriodeKeuangan;
use App\BukuBesarPembantu;
use App\Models\TerminPembayaran;
use Illuminate\Http\Request;
use DB;

class SistemInformasiPiutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-sistem-informasi-piutang');
    }

    public function index ()
    {
        $pelanggan = DB::table('pelanggan')
        ->select('pelanggan.id', 'pelanggan.nama as pelanggan', 'keterangan', 'flag_aktif', 'saldo_piutang', 'perkiraan.nama as rekening_kontrol')
        ->leftJoin('perkiraan', 'perkiraan.id', 'pelanggan.id_perkiraan')
        ->paginate(20);

        return view ('sistem-informasi-piutang/index', compact('pelanggan'));
    }

    public function mutasiPiutang (Request $request)
    {
        $pelanggan = Pelanggan::where('id', $request->id)->firstOrFail();
        $mutasi = DB::table('buku_besar_pembantu')
        ->selectRaw('buku_besar_pembantu.tanggal, buku_besar_pembantu.keterangan, buku_besar_pembantu.debet, 
        buku_besar_pembantu.kredit, (SELECT SUM(debet) - SUM(kredit) FROM buku_besar_pembantu b WHERE b.id <= buku_besar_pembantu.id ) AS saldo')
        ->where('id_pelanggan', $request->id)
        ->orderBy('buku_besar_pembantu.id', 'ASC')
        ->get();

        return view ('sistem-informasi-piutang/mutasi-piutang', compact('mutasi', 'pelanggan'));
    }

    public function detailPiutang (Request $request)
    {
        $pelanggan = Pelanggan::where('id', $request->id)->firstOrFail();
        $query = DB::table('penjualan')
        ->selectRaw('"Penjualan Farmasi" AS keterangan, date(penjualan.waktu) AS tanggal, DATEDIFF(CURDATE(), 
        penjualan.waktu) AS umur_piutang, DATE_ADD(date(penjualan.waktu), INTERVAL termin_pembayaran.jumlah_hari DAY) AS jatuh_tempo, 
        CASE WHEN detail_pembayaran.id_pembayaran IS NULL THEN penjualan.total_tagihan ELSE 
        penjualan.total_tagihan - detail_pembayaran.total_pembayaran AND  detail_pembayaran.jenis = "penjualan" END AS piutang')
        ->leftJoin('penjualan_resep', 'penjualan_resep.id_penjualan', 'penjualan.id')
        ->leftJoin('visit', 'visit.id', 'penjualan_resep.id_visit')
        ->leftJoin('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id', 'pelanggan.id_termin')
        ->leftJoin('pembayaran', 'pembayaran.id_pelanggan', 'visit.id_pelanggan')
        ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
        ->where('pelanggan.id', $request->id);

        $DetailPiutang = DB::table('pelanggan')
        ->selectRaw('pendapatan_jasa.keterangan,  pendapatan_jasa.tanggal, DATEDIFF(CURDATE(), pendapatan_jasa.tanggal) 
        AS umur_piutang, DATE_ADD(pendapatan_jasa.tanggal, INTERVAL termin_pembayaran.jumlah_hari DAY) AS jatuh_tempo, 
	    CASE WHEN detail_pembayaran.id_pembayaran IS NULL THEN pendapatan_jasa.total_tagihan ELSE 
        pendapatan_jasa.total_tagihan - detail_pembayaran.total_pembayaran AND  detail_pembayaran.jenis = "jasa" END AS piutang')            
        ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.id_pelanggan', 'pelanggan.id')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id', 'pelanggan.id_termin')
        ->leftJoin('pembayaran', 'pembayaran.no_kunjungan', 'pendapatan_jasa.no_kunjungan')
        ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
        ->where('pelanggan.id', $request->id)
        ->unionAll($query)
        ->get();

        return view('sistem-informasi-piutang/detail-piutang', compact('pelanggan', 'DetailPiutang'));
    }

    public function tambahSaldo (Request $request)
    {    
        $id = $request->id;
        $pelanggan = Pelanggan::where('id', $id)->firstOrFail();
        $tipe_pasien = tipe_pasien::all(['id', 'tipe_pasien']);
        $periodeKeuangan = PeriodeKeuangan::where('status_aktif', 'Y')->first();
        $produkAsuransi = ProdukAsuransi::select('id', 'nama')->get();
        $bukti = pendapatan_jasa::selectRaw("concat('PJ-', substr(no_bukti_transaksi, 4) +1) as bukti_transaksi")
        ->orderByDesc('id')
        ->firstOrFail();

        return view('sistem-informasi-piutang/tambah-saldo', compact('pelanggan','tipe_pasien','produkAsuransi','periodeKeuangan', 'bukti'));
    }

    public function simpanSaldo (Request $request)
    {
        DB::beginTransaction();

        try {

            $saldo_piutangg = str_replace('.', '', $request->saldo_piutang);
            $saldo_piutang = str_replace(',','.',$saldo_piutangg);
            
            $pelanggan = Pelanggan::where('id', $request->id_pelanggan)->update([
                'saldo_piutang'=>$saldo_piutang,
                'jatuh_tempo'=>$request->tanggal_jatuh_tempo,
                'tanggal_piutang'=>$request->tanggal_piutang]);

            $visit = new visit;
            $visit->id_pelanggan = $request->id_pelanggan;
            $visit->waktu = $request->tanggal_piutang;
            $visit->status = 2;
            $visit->user_input = Auth::user()->id;
            $visit->save();

            $no_kunjungan = $visit->id;
            $id_user = Auth::user()->id;
    
            if ($request->tipe_pasien == 1) 
            { // jika tipe pasien perusahaan langganan

                $act = new pendapatan_jasa;
                $act->keterangan = 1;
                $act->no_bukti_transaksi = $request->bukti_transaksi;
                $act->no_kunjungan = $no_kunjungan;
                $act->tanggal = $request->tanggal_piutang;
                $act->id_pelanggan = $request->id_pelanggan;
                $act->jenis = $request->jenis_pasien;
                $act->tipe_bayar = 'Kredit';
                $act->tipe_pasien = $request->tipe_pasien;
                $act->id_user = $id_user;
                $act->total_tagihan = $saldo_piutang;
                $act->id_bank = 1;
                $act->discharge = 'Y';
                $act->save();

                $id_pendapatan_jasa = $act->id;
                $act_langganan = new pendapatan_jasa_langganan;
                $act_langganan->id_pendapatan_jasa = $id_pendapatan_jasa;
                $act_langganan->id_asuransi_produk = $request->id_asuransi;
                $act_langganan->perusahaan = $request->perusahaan;
                $act_langganan->save();       
            }   
        
            if ($request->tipe_pasien == 2) 
            { //jika tipe pasien antar unit

                $act = new pendapatan_jasa;
                $act->keterangan = 1;
                $act->no_bukti_transaksi = $request->bukti_transaksi;
                $act->no_kunjungan = $no_kunjungan;
                $act->tanggal = $request->tanggal_piutang;
                $act->id_pelanggan = $request->id_pelanggan;
                $act->jenis = $request->jenis_pasien;
                $act->tipe_bayar = 'Kredit';
                $act->tipe_pasien = $request->tipe_pasien;
                $act->id_user = $id_user;
                $act->total_tagihan = $saldo_piutang;
                $act->id_bank = 1;
                $act->discharge = 'Y'; 
                $act->save();
            } 
        
            $id_pendapatan_jasa = $act->id;
            $tagihan = new tagihan;
            $tagihan->id_pendapatan_jasa = $id_pendapatan_jasa;
            $tagihan->tanggal = $request->tanggal_piutang;
            $tagihan->no_kunjungan = $no_kunjungan;
            $tagihan->piutang = $saldo_piutang;
            $tagihan->id_pelanggan = $request->id_pelanggan;
            $tagihan->type = $request->tipe_pasien;
            $tagihan->status_tagihan = 'Y';
            $tagihan->id_user = $id_user;
            $tagihan->ref = 'Y';
            $tagihan->save();

            $buku_besar_pembantu = new BukuBesarPembantu;
            $buku_besar_pembantu->tanggal = $request->tanggal_piutang;
            $buku_besar_pembantu->id_pelanggan = $request->id_pelanggan;
            $buku_besar_pembantu->id_perkiraan = $request->id_perkiraan;
            $buku_besar_pembantu->id_periode = $request->id_periode;
            $buku_besar_pembantu->keterangan = 'Saldo Awal';
            $buku_besar_pembantu->debet = $saldo_piutang;
            $buku_besar_pembantu->kredit = 0;
            $buku_besar_pembantu->user_input = $id_user;
            $buku_besar_pembantu->save();

            $perkiraan = DB::table('perkiraan')->where('id', $request->id_perkiraan)->increment('debet', $saldo_piutang);
            // menambah saldo di kolom debet pada id perkiraan yang dilih,
            DB::commit();
            message($act, 'Tambah Saldo Piutang Berhasil', 'Tambah Saldo Piutang Gagal');
            return redirect ('sistem-informasi-piutang');
        } 
        catch (Exception $e) {
            DB::rollback();
        }
    }
}