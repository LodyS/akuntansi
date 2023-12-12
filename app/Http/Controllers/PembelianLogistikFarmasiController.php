<?php

namespace App\Http\Controllers;
use App\Models\InstansiRelasi;
use App\Models\TarifPajak;
use App\Models\KasBank;
use App\JenisPembelian;
use App\Models\TerminPembayaran;
use App\Barang;
use App\Stok;
use Auth;
use DB;
use App\Models\Perkiraan;
use App\PackingBarang;
use App\pembelian;
use App\DetailPembelian;
use App\LogStok;
use App\Models\PeriodeKeuangan;
use App\BukuBesarPembantuHutang;
use App\MutasiKas;
use Illuminate\Http\Request;

class PembelianLogistikFarmasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-pembelian-logistik-farmasi');
    }

    public function index ()
    {
        $kodeBkm = MutasiKas::selectRaw('CONCAT("BKK-", SUBSTR(kode, 5)+1) AS kode')->where('kode', 'like', 'BKK%')->orderByDesc('id')->first();
        $instansiRelasi = InstansiRelasi::select('id', 'nama')->get();
        $barang = Barang::select('id', 'nama', 'id_sub_kategori_barang')->get();
        $jenisPembelian = JenisPembelian::select('id', 'nama')->get();
        $KasBank = KasBank::select('id', 'nama')->get();
        $PeriodeKeuangan = PeriodeKeuangan::select('id')->where('status_aktif', 'Y')->first();

        return view('pembelian-logistik-farmasi/index', compact('instansiRelasi','KasBank','jenisPembelian', 'barang', 'PeriodeKeuangan', 'kodeBkm'));
    }

    public function CariPemasok ($id_pemasok)
    {
        $data = InstansiRelasi::select('termin_pembayaran.jumlah_hari', 'tarif_pajak.nama_pajak', 'tarif_pajak.persentase_pajak', 
        'perkiraan.nama as perkiraan')
        ->leftJoin('perkiraan', 'perkiraan.id', 'instansi_relasi.id_perkiraan')
        ->leftJoin('termin_pembayaran', 'termin_pembayaran.id', 'instansi_relasi.id_termin')
        ->leftJoin('tarif_pajak', 'tarif_pajak.id', 'instansi_relasi.id_tarif_pajak')
        ->where('instansi_relasi.id', $id_pemasok)
        ->first();

        echo json_encode ($data);
        exit;
    }

    public function CariBarang ($barcode)
    {
        $data = DB::table('packing_barang')
        ->selectRaw('barang.nama AS barang, barang.id as id_barang, packing_barang.id AS id_packing_barang, stok.id AS id_stok, satuan, jumlah_stok')
        ->leftJoin('barang', 'barang.id', 'packing_barang.id_barang')
        ->leftJoin('stok', 'stok.id_packing_barang', 'packing_barang.id')
        ->where('barcode', $barcode)
        ->first();

        echo json_encode ($data);
        exit;
    }

    public function SimpanPembelianLogistik(Request $request)
    {
        $data = $request->all();
        $id_user = Auth::user()->id;

        DB::beginTransaction();

        try {

            $request->validate([
                'jenis_pembelian'=>'required',
                'id_pemasok'=>'required',
                'total_diskon'=>'required',
                'biaya_materai'=>'required',
                'biaya_charge'=>'required',
                'tanggal_jatuh_tempo'=>'required',
                'jenis_pembayaran'=>'required',
                'sisa_tagihan'=>'required',
                'harga'=>'required',
                'diskon'=>'required',
            ]);

            $materai = str_replace('.', '', $request->biaya_materai); 
            $biaya_materai = str_replace(',', '.', $materai);
            $charge = str_replace('.', '', $request->biaya_charge); 
            $biaya_charge = str_replace(',', '.', $charge);            
            $bayar = str_replace('.', '', $request->pembayaran); 
            $pembayaran = str_replace(',', '.', $bayar);
            $sisa = str_replace('.', '', $request->sisa_tagihan); 
            $sisa_tagihan = str_replace(',', '.', $sisa);

            $act = new pembelian;
            $act->no_faktur = $request->no_faktur;
            $act->keterangan = 2;
            $act->id_jenis_pembelian = $request->jenis_pembelian;
            $act->waktu = $request->tanggal_pembelian;
            $act->id_instansi_relasi = $request->id_pemasok;
            $act->ppn = $request->ppn;
            $act->diskon = $request->total_diskon;
            $act->materai = $biaya_materai;
            $act->charge = $biaya_charge;
            $act->jatuh_tempo = $request->tanggal_jatuh_tempo;
            $act->jumlah_nominal = $request->total_before_diskon;
            $act->status = $request->jenis_pembayaran;
            $act->jumlah_tagihan = $request->sisa_tagihan;
            $act->id_bank = $request->id_bank;
            $act->status_bayar = $request->jenis_pembayaran;
            $act->user_input = $id_user;
            $act->save();

            $id_pembelian = $act->id;

            for ($i=0; $i<count($data['id_barang']); $i++)
            {
                $hargaa = str_replace('.', '', $data['harga'][$i]); 
                $harga = str_replace(',', '.', $hargaa);
            
                $insert = array (
                'id_pembelian'=>$id_pembelian,
                'id_barang'=>$data['id_barang'][$i],
                'id_packing_barang'=>$data['id_packing_barang'][$i],
                'harga_pembelian'=>$harga,
                'diskon'=>$data['diskon'][$i],
                'jumlah_pembelian'=>$data['qty'][$i],);

                DetailPembelian::create($insert);
            }

            if ($request->jenis_pembayaran == 1)
            { 
                $MutasiKas = new MutasiKas;
                $MutasiKas->kode = $request->kode_mutasi_kas;
                $MutasiKas->id_arus_kas = 1;
                $MutasiKas->tanggal = $request->tanggal_pembelian;
                $MutasiKas->id_kas_bank = $request->id_bank;
                $MutasiKas->nominal = $pembayaran;
                $MutasiKas->tipe = 1;
                $MutasiKas->user_input = $id_user;
                $MutasiKas->save(); //bayar tunai
            } else { 
                $BukuBesarPembantuHutang = new BukuBesarPembantuHutang;
                $BukuBesarPembantuHutang->tanggal = $request->tanggal_pembelian;
                $BukuBesarPembantuHutang->id_instansi_relasi = $request->id_pemasok;
                $BukuBesarPembantuHutang->id_periode = $request->id_periode;
                $BukuBesarPembantuHutang->keterangan = 'Pembelian';
                $BukuBesarPembantuHutang->debet = 0;
                $BukuBesarPembantuHutang->kredit = $sisa_tagihan;
                $BukuBesarPembantuHutang->user_input = $id_user;
                $BukuBesarPembantuHutang->save(); // bayar kredit
            } 

            for ($i=0; $i<count($data['id_stok']); $i++)
            {
                $insert = array (
                'id_stok'=>$data['id_stok'][$i],
                'waktu'=>$request->waktu,
                'stok_awal'=>$data['stok_awal'][$i],
                'selisih'=>$data['qty'][$i],
                'stok_akhir'=>$data['stok_akhir'][$i],
                //'id_transaksi'=>2,
                'user_input'=>$id_user,);

                LogStok::create($insert);
                Stok::where('id_packing_barang', $data['id_packing_barang'][$i])->increment('jumlah_stok' , $data['qty'][$i]);
            }

            DB::commit();
            message($act, 'Pembelian logistik berhasil disimpan', 'Pembelian logistik gagal disimpan');
            return redirect('pembelian-logistik-farmasi/index');
        }
        catch (Exception $e){
            DB::rollback();
        }
    }
}
