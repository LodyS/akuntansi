<?php

namespace App\Http\Controllers;
use App\pembelian;
use App\Models\KasBank;
use App\MutasiKas;
use App\Models\PeriodeKeuangan;
use App\BukuBesarPembantuHutang;
use DB;
use Auth;
use App\Models\InstansiRelasi;
use App\PembayaranSupplier;
use Illuminate\Http\Request;

class PembayaranHutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-pembayaran-hutang');
    }

    public function index ()
    {
        $InstansiRelasi = InstansiRelasi::select('id', 'nama')->get();

        return view('pembayaran-hutang/index', compact('InstansiRelasi'));
    }

    public function rekapitulasi (Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $pemasok = $request->id_pemasok;
        $no_faktur = $request->no_faktur;

        $rekapitulasi = DB::table('pembelian')
        ->selectRaw('pembelian.id, date(pembelian.waktu) as tanggal_pembelian, pembelian.no_faktur, instansi_relasi.nama, 
        pembelian.jumlah_nominal as total_pembelian, pembelian.materai, pembelian.diskon, pembelian.ppn, 
        pembelian.jumlah_tagihan as total_tagihan,  
        (IFNULL ( SUM(pembayaran_supplier.pembayaran),0 )) as pembayaran, 
        (IFNULL ((pembelian.jumlah_tagihan- (SUM(pembayaran_supplier.pembayaran))),0)) as sisa_hutang')
        ->leftJoin('pembayaran_supplier', 'pembayaran_supplier.id_pembelian', 'pembelian.id')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->where(function ($query) use ($no_faktur, $pemasok, $tanggal_awal, $tanggal_akhir){
            if (isset($tanggal_akhir) && isset($tanggal_awal) && isset($no_faktur) && isset($pemasok))
            {
                $query->whereBetween(DB::raw('DATE(pembelian.waktu)'), [$tanggal_awal, $tanggal_akhir])
                ->where('pembelian.no_faktur',  $no_faktur)
                ->where('pembelian.id_instansi_relasi', $pemasok)
                ->where('pembelian.status', 1);
            }
        })
        ->where('pembelian.status', 1)
        ->groupBy('pembelian.id')
        ->paginate(20);

        return view('pembayaran-hutang/rekapitulasi', compact('rekapitulasi'));
    }

    public function LaporanAngsuran (Request $request)
    {
        $supplier = pembelian::select('instansi_relasi.nama as supplier')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->where('pembelian.id', $request->id)
        ->firstOrFail();

        if (is_null($supplier))
        {
            message('Data tidak ditemukan', 'Data tidak ditemmukan');
            return redirect('/pembayaran-hutang/index');
        } else {
            $angsuran = PembayaranSupplier::selectRaw('waktu, 0 AS hutang, pembayaran, sisa_tagihan')->where('id_pembelian', $request->id);
            $hutang = DB::table('pembelian')
            ->selectRaw('waktu AS waktu, jumlah_tagihan AS hutang, 0 AS angsuran, 
            (SELECT SUM(jumlah_tagihan) - IFNULL(SUM(pembayaran),0) FROM pembayaran_supplier WHERE pembayaran_supplier.id >= pembelian.id) AS saldo')
            ->where('pembelian.id', $request->id)
            ->unionAll($angsuran)
            ->paginate(15);

            return view('pembayaran-hutang/laporan-angsuran', compact('hutang', 'supplier'));
        }
    }

    public function pembayaran (Request $request)
    {
        $KasBank = KasBank::select('id', 'nama')->get();
        $no_bukti = MutasiKas::selectRaw('CONCAT("BKK-", SUBSTR(kode, 5)+1) AS bukti_pembayaran')
        ->where('kode', 'like', 'BKK-%')
        ->orderByDesc('id')
        ->first();

        $pembelian = pembelian::selectRaw('pembelian.id, instansi_relasi.nama, DATE(pembelian.waktu) AS tanggal, rekening, jumlah_nominal, 
        instansi_relasi.id_perkiraan, instansi_relasi.id as id_pemasok, pembelian.diskon, materai, ppn, 
        materai + ppn + jumlah_nominal - pembelian.diskon as total_tagihan')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->leftJoin('pembayaran_supplier', 'pembayaran_supplier.id_pembelian', 'pembelian.id')
        ->where('pembelian.id', $request->id)
        ->firstOrFail();

        return view ('pembayaran-hutang/pembayaran', compact('pembelian', 'KasBank', 'no_bukti'));
    }

    public function SimpanPembayaranHutang (Request $request)
    {
        $id_user = Auth::user()->id;
        DB::beginTransaction();

        $act = $request->validate([
            'id_bank'=>'required',
            'dibayar_oleh'=>'required',
            'pembayaran'=>'required',
        ]);

        try {

            $pembayarann = str_replace('.', '', $request->pembayaran);
            $pembayaran = str_replace(',', '.', $pembayarann);

            $act = new PembayaranSupplier;
            $act->bukti_pembayaran = $request->no_bukti;
            $act->id_pembelian = $request->id;
            $act->keterangan = 'Pembayaran';
            $act->waktu = $request->tanggal_pembayaran;
            $act->tagihan = $request->total_tagihan;
            $act->pembayaran = $pembayaran;
            $act->diskon = $request->diskon;
            $act->sisa_tagihan = $request->sisa_tagihan;
            $act->id_bank = $request->id_bank;
            $act->dibayar_oleh = $request->dibayar_oleh;
            $act->id_user = $id_user;
            $act->flag_ak = 'Y';
            $act->save();

            $periodeKeuangan = PeriodeKeuangan::where('status_aktif','Y')->first();
            $id_periode = $periodeKeuangan->id;

            $buku = new BukuBesarPembantuHutang;
            $buku->tanggal = $request->tanggal_pembayaran;
            $buku->id_instansi_relasi = $request->id_pemasok;
            $buku->id_periode = $id_periode;
            $buku->keterangan = 'Pelunasan';
            $buku->debet = $pembayaran;
            $buku->kredit = 0;
            $buku->user_input = $id_user;
            $buku->save();

            $mutasi = new MutasiKas;
            $mutasi->kode = $request->no_bukti;
            $mutasi->id_arus_kas = 10;
            $mutasi->tanggal = $request->tanggal_pembayaran;
            $mutasi->id_kas_bank = $request->id_bank;
            $mutasi->nominal = $pembayaran;
            $mutasi->tipe = 1;
            $mutasi->user_input = $id_user;
            $mutasi->save();

            if ($request->sisa_tagihan == 0)
            {
                Pembelian::where('id', $request->id)->update(['status_bayar'=>1]);
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
        }
        DB::commit();

        message($act, 'Data penerimaan hutang berhasil disimpan', 'Data penerimaan hutang gagal disimpan');
        return redirect('pembayaran-hutang/index');
    }
}