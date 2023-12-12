<?php

namespace App\Http\Controllers;
use App\Models\ProdukAsuransi;
use App\Models\KasBank;
use App\Models\Nakes;
use App\kelas;
use App\Stok;
use App\LogStok;
use App\Barang;
use App\penjualan;
use App\penjualan_resep;
use DB;
use App\Models\PeriodeKeuangan;
use Auth;
use Carbon\carbon;
use App\BukuBesarPembantu;
use App\ArusKas;
use App\Tagihan;
use App\MutasiKas;
use App\visit;
use App\Models\Pelanggan;
use App\DetailPenjualan;
use App\PackingBarang;
use App\SubKategoriBarang;
use Illuminate\Http\Request;

class PenjualanObatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-penjualan-obat');
    }

    public function index ()
    {
        $ProdukAsuransi = ProdukAsuransi::select('id', 'nama')->get();
        $arusKas = ArusKas::selectRaw('id')->where('id', 2)->first();
        $KasBank = KasBank::select('id', 'nama')->get();
        $Nakes = Nakes::select('id', 'nama')->get();
        $Kelas = kelas::select('id', 'nama')->get();
        $PeriodeKeuangan = PeriodeKeuangan::select('id')->where('status_aktif', 'Y')->first();
        $MutasiKas = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', 'BKM%')->orderByDesc('id')->first();
        $Penjualan = Penjualan::selectRaw('CONCAT("SR-", SUBSTR(kode, 4)+1) AS kode')->where('kode', 'like', 'SR%')->orderByDesc('id')->first();
        // Mendapat kode SB setelah 1
        return view('penjualan-obat/index', compact('ProdukAsuransi', 'KasBank', 'arusKas', 'Nakes','Penjualan', 'Kelas', 'MutasiKas', 'PeriodeKeuangan'));
    }

    public function PenjualanObatBebas ()
    {
        $arusKas = ArusKas::selectRaw('id')->where('id', 2)->first();
        $KasBank = KasBank::where('nama', '<>', 'Kredit')->get();
        $Penjualan = Penjualan::selectRaw('CONCAT("SB-", SUBSTR(kode, 4)+1) AS kode')->where('kode', 'like', 'SB%')->orderByDesc('id')->first();
        $MutasiKas = MutasiKas::selectRaw('CONCAT("BKM-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', 'BKM%')->orderByDesc('id')->first();
          // Mendapat kode SB setelah 1
        return view('penjualan-obat/penjualan-obat-bebas', compact('KasBank', 'arusKas', 'Penjualan', 'MutasiKas'));
    }

    public function isiBarang ($barcode)
    {
        $data = PackingBarang::select('packing_barang.id as id_packing_barang','barang.nama as barang','barang.id as id_barang', 'satuan', 'stok.hna')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->leftJoin('stok', 'stok.id_packing_barang', 'packing_barang.id')
        ->where('barcode', $barcode)
        ->first();

        echo json_encode($data);
        exit;
    }

    public function cariPasien ($id_visit)
    {
        $data = visit::select('visit.id', 'id_pelanggan', 'id_perkiraan', 'pelanggan.nama as nama_pasien', 'visit.flag_discharge')
        ->join('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
        ->where('visit.id', $id_visit)
        ->first();

        echo json_encode($data);
        exit;
    }

    public function SimpanPenjualanObat (Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'status_pajak'=>'required',
        ]);
        
        $id_user = Auth::user()->id;
        $data = $request->all();
        $diskon = $request->total_penjualan - $request->total_after_diskon;
        $sekarang = Carbon::now()->toDateTimeString();

        try {

            if (is_null($request->status_pajak))
            {
                message(false, '', 'Gagal simpan penjualan obat');
                return redirect('penjualan-obat/penjualan-obat-bebas');
            }

            if ($request->status_pajak == 'Y')
            {
                $act = new penjualan;
                $act->kode = $request->kode_penjualan;
                $act->jenis_pasien = $request->jenis_pasien;
                $act->jenis_pembayaran = $request->jenis_pembayaran;
                $act->id_kelas = $request->id_kelas;
                $act->id_produk_asuransi = $request->id_asuransi;
                $act->jenis = 'Resep';
                $act->id_bank = $request->id_bank;
                $act->waktu = $sekarang;
                $act->pajak = $request->pajak;
                $act->total_penjualan = $request->total_penjualan;
                $act->diskon = $diskon;
                $act->total_tagihan = $request->tagihan_pajak;
                $act->ref = 'N';
                $act->flag_ak = 'Y';
                $act->save();
            }

            if ($request->status_pajak == 'N')
            {
                $act = new penjualan;
                $act->kode = $request->kode_penjualan;
                $act->jenis_pasien = $request->jenis_pasien;
                $act->jenis_pembayaran = $request->jenis_pembayaran;
                $act->id_kelas = $request->id_kelas;
                $act->id_produk_asuransi = $request->id_asuransi;
                $act->jenis = 'Resep';
                $act->id_bank = $request->id_bank;
                $act->waktu = $sekarang;
                $act->pajak = 0;
                $act->total_penjualan = $request->total_penjualan;
                $act->diskon = $diskon;
                $act->total_tagihan = $request->tagihan_tanpa_pajak;
                $act->ref	= 'N';
                $act->flag_ak = 'Y';
                $act->save();
            }

            $id_penjualan  = $act->id;
            $total_tagihan = $act->total_tagihan;

            $penjualan_resep= new penjualan_resep;
            $penjualan_resep->id_penjualan = $id_penjualan;
            $penjualan_resep->id_visit = $request->id_visit;
            $penjualan_resep->id_dokter = $request->id_nakes;
            $penjualan_resep->save();

            for ($i=0; $i<count($data['id_barang']); $i++)
            {
                $hna = str_replace(',', '', $data['hna'][$i]); 
                $harga = str_replace(',', '', $data['harga'][$i]); 

                $insert = array (
                    'id_penjualan'=>$id_penjualan,
                    'id_barang'=>$data['id_barang'][$i],
                    'hna'=>$hna,
                    'margin'=>$data['margin'][$i],
                    'jumlah_penjualan'=>$data['qty'][$i],
                    'total'=>$harga,
                    'diskon'=>$data['diskon'][$i],
                    'id_user'=>$id_user,);

                DetailPenjualan::create($insert);
            }

            if ($total_tagihan == 0 || $request->jenis_pembayaran == 'Tunai' && isset($request->id_arus_kas))
            {
                $mutasiKas = new MutasiKas;
                $mutasiKas->kode = $request->kode_mutasi_kas;
                $mutasiKas->id_arus_kas = $request->id_arus_kas;
                $mutasiKas->tanggal = $request->tanggal_penjualan;
                $mutasiKas->id_kas_bank = $request->id_bank;
                $mutasiKas->id_penjualan = $id_penjualan;
                $mutasiKas->nominal = str_replace(',','', $request->pembayaran_tanpa_pajak);
                $mutasiKas->tipe = 2;
                $mutasiKas->user_input = $id_user;
                $mutasiKas->save();
            }

            if ($total_tagihan > 0 || $request->jenis_pembayaran == 'Kredit' && isset($request->id_arus_kas))
            {
                $tagihan = new tagihan;
                $tagihan->tanggal = $request->tanggal_penjualan;
                $tagihan->no_kunjungan = $request->id_visit;
                $tagihan->obat = str_replace(',', '', $total_tagihan);
                $tagihan->piutang = str_replace(',', '', $total_tagihan);
                $tagihan->id_pelanggan = $request->id_pelanggan;
                $tagihan->type = $request->jenis_pasien;
                $tagihan->status_tagihan = 'N';
                $tagihan->id_user = $id_user;
                $tagihan->ref = 'N';
                $tagihan->save();
            }

            for ($i=0; $i<count($data['id_packing_barang']); $i++) // awal untuk input ke log stok
            { 
                $insert  = array ('id_packing_barang'=>$data['id_packing_barang'][$i],);
                $selisih = array('selisih'=>$data['qty'][$i],);

                $stok = DB::table('stok')
                ->selectRaw('stok.id, jumlah_stok as stok_awal, jumlah_stok - jumlah_penjualan as stok_akhir')
                ->join('packing_barang', 'packing_barang.id', 'stok.id_packing_barang')
                ->join('detail_penjualan', 'detail_penjualan.id_barang', 'packing_barang.id_barang')
                ->where('id_packing_barang', $insert)
                ->where('id_penjualan', $id_penjualan)
                ->get();

                foreach ($stok as $Stok)
                {
                    $create = array(
                        'id_stok'=>$Stok->id,
                        'waktu'=>$sekarang,
                        'stok_awal'=>$Stok->stok_awal,
                        'selisih'=>$data['qty'][$i],
                        'stok_akhir'=>$Stok->stok_akhir,
                        //'id_transaksi'=>1,
                        'user_input'=>$id_user,);
                }
                LogStok::create($create); 
            }
            //akhir input ke log stok
            
            $a = 0; // awal untuk update stok
            $count = count($data['id_packing_barang']);

            while ($a < $count)
            {
                $all[] = array ('id_packing_barang'=>$data['id_packing_barang'][$a], 'jumlah_stok'=>$data['qty'][$a],);
                $a++;
            }

            $b =0;
            $jumlah = count($data['id_packing_barang']);

            while ($b <$jumlah)
            {
                DB::table('stok')->where('id_packing_barang', $all[$b]['id_packing_barang'])->decrement('jumlah_stok' , $all[$b]['jumlah_stok']);
                $b++;
            } //Akhir untuk update stok

            if ($request->id_periode == null && $request->id_perkiraan == null)
            {
                message(false, '', 'Gagal input tabel Buku Besar Pembantu');
            } // jika id periode dan id perkiraan kosong maka tidak bisa update

            if (isset($request->id_periode) && isset($request->id_perkiraan))
            {
                $buku_besar_pembantu = new BukuBesarPembantu;
                $buku_besar_pembantu->tanggal = $request->tanggal_penjualan;
                $buku_besar_pembantu->id_pelanggan = $request->id_pelanggan;
                $buku_besar_pembantu->id_periode = $request->id_periode;
                $buku_besar_pembantu->id_perkiraan = $request->id_perkiraan;
                $buku_besar_pembantu->keterangan = 'Sales Journal';
                $buku_besar_pembantu->debet  = $total_tagihan;
                $buku_besar_pembantu->kredit = 0;
                $buku_besar_pembantu->user_input = $id_user;
                $buku_besar_pembantu->save();
            } //validasi jika id periode ada dan id perkiraan ada maka bisa insert ke buku besar pembantu

            DB::commit();
            message($act, 'Berhasil simpan Penjualan Obat Resep', 'Gagal simpan Penjualan Obat Resep');
            return redirect('penjualan-obat/index');
        }
        catch (Exception $e)
        {
            DB::rollback();
            message(false, '', 'Penjualan Obat Bebas berhasil disimpan');
            return redirect('penjualan-obat/index');
        }
    }

    public function SimpanPenjualanObatBebas (Request $request)
    {
        $id_user = Auth::user()->id;
        $data = $request->all();
        $diskon = $request->total_penjualan - $request->total_setelah_diskon;
        $sekarang = Carbon::now()->toDateTimeString();
        DB::beginTransaction();

        try {

            if (is_null($request->status_pajak))
            {
                message(false, '', 'Gagal simpan penjualan obat');
                return redirect('penjualan-obat/penjualan-obat-bebas');
            }

            if ($request->status_pajak == 'Y')
            {
                $act = new penjualan;
                $act->kode = $request->kode_penjualan;
                $act->jenis = 'Bebas';
                $act->id_bank = $request->id_bank;
                $act->waktu = $sekarang;
                $act->pajak = $request->pajak;
                $act->total_penjualan = $request->total_penjualan;
                $act->diskon = $diskon;
                $act->total_tagihan = $request->tagihan_pajak;
                $act->ref = 'N';
                $act->flag_ak = 'Y';
                $act->save();
            }

            if ($request->status_pajak == 'N')
            {
                $act = new penjualan;
                $act->kode = $request->kode_penjualan;
                $act->id_kelas = $request->id_kelas;
                $act->jenis = 'Bebas';
                $act->id_bank = $request->id_bank;
                $act->waktu = $sekarang;
                $act->pajak = 0;
                $act->total_penjualan = $request->total_penjualan;
                $act->diskon = $diskon;
                $act->total_tagihan = $request->tagihan_tanpa_pajak;
                $act->ref = 'N';
                $act->flag_ak = 'Y';
                $act->save();
            }

            $id_penjualan = $act->id;

            if($id_penjualan == null && $act == null)
            {
                message(false, '', 'Gagal simpan penjualan obat');
                return redirect('penjualan-obat/penjualan-obat-bebas');
            }

            for ($i=0; $i<count($data['id_barang']); $i++)
            {
                $hna = str_replace(',', '', $data['hna'][$i]); 
                $harga = str_replace(',', '', $data['harga'][$i]); 

                $insert = array (
                    'id_penjualan'=>$id_penjualan,
                    'id_barang'=>$data['id_barang'][$i],
                    'hna'=>$hna,
                    'margin'=>$data['margin'][$i],
                    'jumlah_penjualan'=>$data['qty'][$i],
                    'total'=>$harga,
                    'diskon'=>$data['diskon'][$i],
                    'id_user'=>$id_user,);

                DetailPenjualan::insert($insert);
            }

            for ($i=0; $i<count($data['id_packing_barang']); $i++)
            {
                $insert = array('id_packing_barang' => $data['id_packing_barang'][$i],);
                $selisih = array('selisih' =>$data['qty'][$i],);

                $stok = DB::table('stok')
                ->selectRaw('stok.id, jumlah_stok as stok_awal, jumlah_stok - jumlah_penjualan as stok_akhir')
                ->join('packing_barang', 'packing_barang.id', 'stok.id_packing_barang')
                ->join('detail_penjualan', 'detail_penjualan.id_barang', 'packing_barang.id_barang')
                ->where('id_packing_barang', $insert)
                ->where('id_penjualan', $id_penjualan)
                ->get();

                foreach ($stok as $Stok)
                {
                    $create = array(
                        'id_stok'=>$Stok->id,
                        'waktu'=>$sekarang,
                        'stok_awal'=>$Stok->stok_awal,
                        'selisih'=>$data['qty'][$i],
                        'stok_akhir'=>$Stok->stok_akhir,
                        //'id_transaksi'=>1,
                        'user_input'=>$id_user,);
                }
                LogStok::create($create); //input ke log stok
            }

            $a = 0; // awal untuk update stok
            $count = count($data['id_packing_barang']);

            while ($a < $count)
            {
                $all[] = array ('id_packing_barang'=>$data['id_packing_barang'][$a], 'jumlah_stok'=>$data['qty'][$a],);
                $a++;
            }

            $b = 0;
            $jumlah = count($data['id_packing_barang']);

            while ($b <$jumlah)
            {
                DB::table('stok')->where('id_packing_barang', $all[$b]['id_packing_barang'])->decrement('jumlah_stok' , $all[$b]['jumlah_stok']);
                $b++;
            } //Akhir untuk update stok

            if ($request->id_arus_kas == null)
            {
                message(false, '', 'Gagal simpan Mutasi Kas karena Arus Kas Kosong');
                return redirect('penjualan-obat/penjualan-obat-bebas');
            }

            if (isset($request->id_arus_kas) && $request->status_pajak == 'N')
            {
                $mutasiKas = new MutasiKas;
                $mutasiKas->kode = $request->kode_mutasi_kas;
                $mutasiKas->id_arus_kas = $request->id_arus_kas;
                $mutasiKas->id_penjualan = $id_penjualan;
                $mutasiKas->tanggal = $request->tanggal_penjualan;
                $mutasiKas->id_kas_bank = $request->id_bank;
                $mutasiKas->nominal = str_replace(',', '', $request->pembayaran_tanpa_pajak);
                $mutasiKas->tipe = 2;
                $mutasiKas->user_input = $id_user;
                $mutasiKas->save();
            }

            if (isset($request->id_arus_kas) && $request->status_pajak == 'Y')
            {
                $mutasiKas = new MutasiKas;
                $mutasiKas->kode = $request->kode_mutasi_kas;
                $mutasiKas->id_arus_kas = $request->id_arus_kas;
                $mutasiKas->id_penjualan = $id_penjualan;
                $mutasiKas->tanggal = $request->tanggal_penjualan;
                $mutasiKas->id_kas_bank = $request->id_bank;
                $mutasiKas->nominal = str_replace(',', '', $request->pembayaran_dengan_pajak);
                $mutasiKas->tipe = 2;
                $mutasiKas->user_input = $id_user;
                $mutasiKas->save();
            }

            DB::commit();
            message($act, 'Penjualan Obat Bebas Berhasil Disimpan', 'Penjualan Obat Bebas berhasil disimpan');
            return redirect('penjualan-obat/penjualan-obat-bebas');
        }
        catch (Exception $e)
        {
            DB::rollback();
            message(false, '', 'Penjualan Obat Bebas berhasil disimpan');
            return redirect('penjualan-obat/penjualan-obat-bebas');
        }
    }
}