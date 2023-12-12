<?php

namespace App\Http\Controllers;
use App\Models\ProdukAsuransi;
use App\Models\Pelanggan;
use App\MutasiKas;
use App\BukuBesarPembantu;
use App\pembayaran;
use App\Models\PeriodeKeuangan;
use App\Models\KasBank;
use App\ArusKas;
use App\visit;
use App\penjualan;
use Auth;
use DB;
use App\pendapatan_jasa;
use App\pendapatan_jasa_langganan;
use App\DetailPembayaran;
use App\Tagihan;
use Illuminate\Http\Request;

class PenerimaanPiutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-penerimaan-piutang');
    }

    public function index ()
    {
        $ProdukAsuransi = ProdukAsuransi::select('id', 'nama')->get();
        $cek_kode_bkm = pembayaran::select('id')->first();

        return view ('penerimaan-piutang/index', compact('ProdukAsuransi', 'cek_kode_bkm'));
    }

    public function cariPasien ($id)
    {
        $data = Pelanggan::select('nama')->where('id',  $id)->first();
        echo json_encode($data);
        exit;
    }

    public function LaporanPenerimaanPiutang(Request $request)
    {
        $KasBank = KasBank::select('id', 'nama')->get();
        $jenis = $request->jenis_pasien;
        $tipe_pasien = $request->tipe_pasien;
        $id_asuransi = $request->id_asuransi;
        $id_pasien = $request->id_pasien;

        if (isset($request->cek_kode_bkm))
        {
            $MutasiKas = pembayaran::selectRaw('SUBSTR(kode_bkm, 5)+1 AS kode')
            ->where('kode_bkm', 'like', 'BKM-%')
            ->orderByDesc('id')
            ->first();
        }

        if ($request->cek_kode_bkm == null)
        {
            $MutasiKas = (object) array('kode'=>'1');
        }

        $kode_mutasi_kas = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')
        ->where('kode', 'like', 'BKM%')
        ->orderByDesc('id')
        ->first();

        if ($tipe_pasien == 2)
        {
            $obat = DB::table('tagihan')
            ->selectRaw("tagihan.id AS id_tagihan, SUM(detail_pembayaran.total_pembayaran) AS total_bayar,tagihan.id_pelanggan, pelanggan.id_perkiraan, 
            tagihan.tanggal, tagihan.no_kunjungan, pelanggan.nama AS pasien, 0 AS jasa, SUM(obat) AS penjualan, SUM(obat) AS total, 'Penjualan' AS jenis")
            ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
            ->leftJoin('pembayaran', 'pembayaran.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
            ->where('pendapatan_jasa.tipe_pasien', 2)
            ->where('tagihan.type', $jenis)
            ->where('tagihan.id_pelanggan', $request->id_pasien)
            ->whereBetween('tagihan.tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
            ->groupBy('tagihan.no_kunjungan');

            $jasa = DB::table('tagihan')
            ->selectRaw('tagihan.id as id_tagihan, SUM(detail_pembayaran.total_pembayaran) AS total_bayar, tagihan.id_pelanggan, pelanggan.id_perkiraan,
            tagihan.tanggal, tagihan.no_kunjungan, pelanggan.nama AS pasien, SUM(tarif) AS jasa, 0 AS penjualan, SUM(tarif)AS total, "Jasa" AS jenis')
            ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
            ->leftJoin('pembayaran', 'pembayaran.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
            ->where('pendapatan_jasa.tipe_pasien', 2)
            ->where('tagihan.type', $jenis)
            ->whereBetween('tagihan.tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
            ->where('tagihan.id_pelanggan', $request->id_pasien)
            ->groupBy('tagihan.no_kunjungan')
            ->unionAll($obat)
            ->get();

        } else {

            $obat = DB::table('tagihan')
            ->selectRaw("(SELECT tagihan.id FROM tagihan JOIN pendapatan_jasa ON pendapatan_jasa.no_kunjungan = tagihan.no_kunjungan
            WHERE obat >0 AND tipe_pasien='$tipe_pasien' AND pendapatan_jasa.jenis='$jenis') AS id_tagihan, 
            SUM(detail_pembayaran.total_pembayaran) AS total_bayar,tagihan.id_pelanggan, pelanggan.id_perkiraan, tagihan.tanggal, 
            tagihan.no_kunjungan, pelanggan.nama AS pasien, 0 AS jasa, SUM(obat) AS penjualan, SUM(obat) AS total, 'Penjualan' AS jenis")
            ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
            ->leftJoin('pembayaran', 'pembayaran.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
            ->leftJoin('pendapatan_jasa_langganan', 'pendapatan_jasa_langganan.id_pendapatan_jasa', 'pendapatan_jasa.id')
            ->where('pendapatan_jasa.tipe_pasien', 1)
            ->where('tagihan.type', $jenis)
            ->Orwhere('pendapatan_jasa_langganan.id_asuransi_produk', $id_asuransi)
            ->whereBetween('tagihan.tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
            ->groupBy('tagihan.no_kunjungan');

            $jasa = DB::table('tagihan')
            ->selectRaw('tagihan.id as id_tagihan, SUM(detail_pembayaran.total_pembayaran) AS total_bayar, tagihan.id_pelanggan, pelanggan.id_perkiraan,
            tagihan.tanggal, tagihan.no_kunjungan, pelanggan.nama AS pasien, SUM(tarif) AS jasa, 0 AS penjualan, SUM(tarif)AS total, "Jasa" AS jenis')
            ->leftJoin('pendapatan_jasa', 'pendapatan_jasa.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
            ->leftJoin('pembayaran', 'pembayaran.no_kunjungan', 'tagihan.no_kunjungan')
            ->leftJoin('detail_pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
            ->leftJoin('pendapatan_jasa_langganan', 'pendapatan_jasa_langganan.id_pendapatan_jasa', 'pendapatan_jasa.id')
            ->where('pendapatan_jasa.tipe_pasien', 1)
            ->where('tagihan.type', $jenis)
            ->Orwhere('pendapatan_jasa_langganan.id_asuransi_produk', $id_asuransi)
            ->whereBetween('tagihan.tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
            ->groupBy('tagihan.no_kunjungan')
            ->unionAll($obat)
            ->get();
        }
    
        return view ('penerimaan-piutang/laporan-penerimaan-piutang', 
        compact('KasBank', 'jasa', 'MutasiKas', 'jenis', 'kode_mutasi_kas', 'tipe_pasien'));
    }

    public function SimpanPenerimaanPiutang (Request $request)
    {
        $data = $request->all();
        $id_user = Auth::user()->id;

        DB::beginTransaction();

        try {

            for ($i=0; $i<count($data['no_kunjungan']); $i++ )
            {
                $insert = array (
                    'id_tagihan'=>$data['id_tagihan'][$i],
                    'id_pelanggan'=>$data['id_pelanggan'][$i],
                    'kode_bkm'=>$data['kode_bkm'][$i],
                    'no_kunjungan'=>$data['no_kunjungan'][$i],
                    'total_tagihan'=>$data['total_setelah_diskon'][$i] ?: 0,
                    'jumlah_bayar'=>$data['total_pembayaran'][$i],
                    'sisa_tagihan'=>0,
                    'klaim_bpjs'=>$data['klaim_bpjs'][$i],
                    'diskon'=>$data['diskon'][$i],
                    'waktu'=>$request->tanggal,
                    'flag_batal'=>'Y',
                    'id_bank'=>$request->id_bank,
                    'tipe_pasien'=>$request->tipe_pasien,
                    'flag_ak'=>'Y');

                pembayaran::create($insert);
            } //input ke pembayaran sebelum di group by berdasarkan no kunjungan dan id pelanggan

            $Pembayaran = DB::table('pembayaran')
            ->selectRaw('kode_bkm, id_pelanggan, id_tagihan, no_kunjungan, SUM(total_tagihan) AS total_tagihan, SUM(klaim_bpjs) AS klaim_bpjs,
            SUM(total_tagihan - diskon - klaim_bpjs) AS sisa_tagihan, waktu, id_bank, SUM(jumlah_bayar) AS jumlah_bayar, tipe_pasien')
            ->whereIn('kode_bkm', $request->kode_bkm)
            ->groupBy('no_kunjungan', 'id_pelanggan')
            ->get();

            foreach($Pembayaran as $pembayaran)
            {
                $kode_bkm = $pembayaran->kode_bkm;
                $id_pelanggan = $pembayaran->id_pelanggan;
                $id_tagihan = $pembayaran->id_tagihan;
                $no_kunjungan = $pembayaran->no_kunjungan;
                $waktu = $pembayaran->waktu;
                $total_tagihan = $pembayaran->total_tagihan;
                $jumlah_bayar = $pembayaran->jumlah_bayar;
                $klaim_bpjs = $pembayaran->klaim_bpjs;
                $sisa_tagihan = $pembayaran->sisa_tagihan;
                $id_bank = $pembayaran->id_bank;
                $tipe_pasien = $pembayaran->tipe_pasien;
               
                $simpan_pembayaran = array(
                    'kode_bkm'=>$kode_bkm,
                    'id_pelanggan'=>$id_pelanggan,
                    'id_tagihan'=>$id_tagihan,
                    'no_kunjungan'=>$no_kunjungan,
                    'waktu'=>$waktu,
                    'total_tagihan'=>$total_tagihan,
                    'jumlah_bayar'=>$jumlah_bayar,
                    'klaim_bpjs'=>$klaim_bpjs,
                    'sisa_tagihan'=>$sisa_tagihan,
                    'flag_batal'=>'N',
                    'id_bank'=>$id_bank,
                    'tipe_pasien'=>$tipe_pasien,
                    'flag_ak'=>'Y');

                $act = pembayaran::create($simpan_pembayaran);
            } //input ke pembayaran setelah di group by berdasarkan no kunjungan dan id pelanggan
            
            DB::table('pembayaran')
            ->whereIn('kode_bkm', $request->kode_bkm)
            ->where('flag_batal', 'Y')
            ->delete(); // hapus data yang pembayaran belum di group by

            for ($i=0; $i<count($data['no_kunjungan']); $i++)
            {
                $simpan_detail_pembayaran = array (
                    'no_kunjungan'=>$data['no_kunjungan'][$i],
                    'jenis'=>$request->jenis,
                    'total_pembayaran'=>$data['total_pembayaran'][$i],);

                DetailPembayaran::create($simpan_detail_pembayaran);
            }

            $a = 0;
            $count = count($data['no_kunjungan']);

            while ($a < $count)
            {
                $all[] = array ('no_kunjungan' =>$data['no_kunjungan'][$a],);
                $a++;
            }

            $b = 0;
            $jumlah = count($data['no_kunjungan']);

            while ($b <$jumlah)
            {
                $nomor_kunjungan = $all[$b]['no_kunjungan'];
                $update = "UPDATE detail_pembayaran JOIN pembayaran ON pembayaran.no_kunjungan = detail_pembayaran.no_kunjungan
                SET id_pembayaran = pembayaran.id WHERE detail_pembayaran.no_kunjungan = '$nomor_kunjungan' AND date(waktu)= '$request->tanggal'";

                $statement = DB::statement($update);
                $b++;
            } // untuk update id pembayaran sesuai dengan data di pembayaran yang telah di group by
            
            $aruskas = ArusKas::where('nama', 'Kas dari piutang')->first();
            $id_arus_kas = $aruskas->id;

            $mutasi = new MutasiKas;
            $mutasi->kode = $request->kode_bkm_mutasi_kas;
            $mutasi->id_arus_kas = $id_arus_kas;
            $mutasi->tanggal = $request->tanggal;
            $mutasi->id_kas_bank = $request->id_bank;
            $mutasi->nominal = 0;
            $mutasi->tipe = 2;
            $mutasi->user_input = $id_user;
            $mutasi->save(); //insert arus kas

            $periodeKeuangan = PeriodeKeuangan::where('status_aktif', 'Y')->first();
            $id_periode = $periodeKeuangan->id;

            $PembayaranSatu = pembayaran::selectRaw('id_pelanggan, id_perkiraan, jumlah_bayar')
            ->join('pelanggan', 'pelanggan.id', 'pembayaran.id_pelanggan')
            ->whereIn('no_kunjungan', $request->no_kunjungan)
            ->whereIn('id_pelanggan', $request->id_pelanggan)
            ->whereDate('waktu', $request->tanggal)
            ->get();

            foreach ($PembayaranSatu as $pembayar)
            {
                $id_pelanggan = $pembayar->id_pelanggan;
                $id_perkiraan = $pembayar->id_perkiraan;
                $jumlah_bayar = $pembayar->jumlah_bayar;

                $simpan_buku_besar_pembantu = array (
                    'tanggal'=>$request->tanggal,
                    'id_pelanggan'=>$id_pelanggan,
                    'id_periode'=>$id_periode,
                    'id_perkiraan'=>$id_perkiraan,
                    'keterangan'=>'Cash Receipt Journal',
                    'debet'=>0,
                    'kredit'=>$jumlah_bayar,
                    'user_input'=>$id_user);

                BukuBesarPembantu::create($simpan_buku_besar_pembantu); // insert ke buku besar pembantu
            } 

            DB::commit();
            message(true, 'Master data penerimaan piutang berhasil disimpan', 'Master data penerimaan piutang gagal disimpan');
            return redirect('penerimaan-piutang');
        }
        catch (Exception $e){
            DB::rollback();
            message(false, '', 'Master data penerimaan piutang gagal disimpan');
            return redirect('penerimaan-piutang');
        }
    }
}
