<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Models\Perkiraan;
use App\PembayaranSupplier;
use App\Models\KasBank;
use App\Models\InstansiRelasi;
use App\transaksi;
use App\Models\pembelian;
use App\Models\TipeJurnal;
use App\Jurnal;
use App\DetailJurnal;
use Illuminate\Http\Request;

class JurnalPembayaranHutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-pembayaran-hutang');
    }

    public function index ()
    {
        $waktu = date('Y-m-d');
        $rekapitulasi = DB::table('pembayaran_supplier')
        ->selectRaw('bukti_pembayaran, jenis_pembelian.nama AS jenis_pembelian, kas_bank.nama as bank,  pembayaran,
        perkiraan.nama as perkiraan, instansi_relasi.nama as pemasok, id_pembelian')
        ->leftJoin('pembelian', 'pembelian.id', 'pembayaran_supplier.id_pembelian')
        ->leftJoin('jenis_pembelian', 'jenis_pembelian.id', 'pembelian.id_jenis_pembelian')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->leftJoin('perkiraan', 'perkiraan.id', 'jenis_pembelian.id_perkiraan_hutang')
        ->leftJoin('kas_bank', 'kas_bank.id', 'pembelian.id_bank')
        ->where('pembayaran_supplier.waktu',  $waktu)
        ->paginate(50);

        $hitung = DB::table('pembayaran_supplier')->selectRaw('count(id) as id')->where('waktu', $waktu)->first();

        return view('jurnal-pembayaran-hutang/index', compact('waktu', 'rekapitulasi', 'hitung'));
    }

    public function rekapitulasi (Request $request)
    {
        if ($request->tanggal == null)
        {
            message(false, '', 'Harap isi tanggal');
            return redirect('jurnal-pembayaran-hutang/index');
        }

        $waktu = $request->tanggal;
        $rekapitulasi = DB::table('pembayaran_supplier')
        ->selectRaw('bukti_pembayaran, jenis_pembelian.nama AS jenis_pembelian, kas_bank.nama as bank,  pembayaran,
        perkiraan.nama as perkiraan, instansi_relasi.nama as pemasok, id_pembelian')
        ->leftJoin('pembelian', 'pembelian.id', 'pembayaran_supplier.id_pembelian')
        ->leftJoin('jenis_pembelian', 'jenis_pembelian.id', 'pembelian.id_jenis_pembelian')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->leftJoin('perkiraan', 'perkiraan.id', 'jenis_pembelian.id_perkiraan_hutang')
        ->leftJoin('kas_bank', 'kas_bank.id', 'pembelian.id_bank')
        ->where('pembayaran_supplier.waktu',  $waktu)
        ->paginate(50);

        $hitung = PembayaranSupplier::selectRaw('count(id) as id')->where('waktu', $waktu)->first();

        return view ('jurnal-pembayaran-hutang/index', compact('rekapitulasi', 'waktu', 'hitung'));
    }

    public function JurnalPembayaranHutang (Request $request)
    {
        $waktu = $request->tanggal;
        $id_pembelian = PembayaranSupplier::where('waktu', $waktu)->get();
        $perkiraan = PembayaranSupplier::selectRaw('perkiraan.id AS id_perkiraan')
        ->leftJoin('pembelian', 'pembelian.id', 'pembayaran_supplier.id_pembelian')
        ->leftJoin('setting_coa', 'setting_coa.id_bank', 'pembelian.id_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_bank')
        ->where('pembayaran_supplier.ref', 'N')
        //->where('pembayaran_supplier.no_jurnal', null)
        ->where('pembayaran_supplier.waktu', $waktu)
        ->first();

        $kredit = PembayaranSupplier::selectRaw('setting_coa.id_perkiraan AS id_perkiraan, perkiraan.kode, perkiraan.nama AS rekening, 0 AS debet,
        SUM(pembayaran_supplier.pembayaran) AS kredit')
        ->leftJoin('pembelian', 'pembelian.id', 'pembayaran_supplier.id_pembelian')
        ->leftJoin('setting_coa', 'setting_coa.id_bank', 'pembayaran_supplier.id_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('pembayaran_supplier.ref', 'N')
        //->where('pembayaran_supplier.no_jurnal', null)
        ->where('pembayaran_supplier.waktu', $waktu);

        $debet = PembayaranSupplier::selectRaw('id_perkiraan_hutang AS id_perkiraan, perkiraan.kode, perkiraan.nama AS rekening,
        SUM(pembayaran_supplier.pembayaran) AS debet, 0 AS kredit')
        ->leftJoin('pembelian', 'pembelian.id', 'pembayaran_supplier.id_pembelian')
        ->leftJoin('jenis_pembelian', 'jenis_pembelian.id', 'pembelian.id_jenis_pembelian')
        ->leftJoin('perkiraan', 'perkiraan.id', 'jenis_pembelian.id_perkiraan_hutang')
        ->where('pembayaran_supplier.waktu', $waktu)
        //->where('pembayaran_supplier.no_jurnal', null)
        ->where('pembayaran_supplier.ref', 'N')
        ->unionAll($kredit)
        ->get();

        $tipe_jurnal = TipeJurnal::find(2); //untuk mendapatan id data jurnal Cash Dishburtment Journal
        $kode_jurnal = Jurnal::selectRaw('CONCAT("CDJ-", SUBSTR(kode_jurnal, 5)+1) AS kode_jurnal')
        ->where('kode_jurnal', 'like', '%CDJ%')
        ->orderByDesc('id')
        ->first();

        return view ('jurnal-pembayaran-hutang/jurnal', compact('debet', 'waktu', 'tipe_jurnal', 'kode_jurnal', 'id_pembelian', 'perkiraan'));
    }

    public function simpan (Request $request)
    {

        $id_user = Auth::user()->id;
        DB::beginTransaction();

        try {

            $request->validate([
                'kode_jurnal'=>'required',
                'keterangan'=>'required',
                'id_tipe_jurnal'=>'required',
            ]);

            if ($request->id_perkiraan == null)
            {
                message(false, '', 'Maaf tidak bisa input jurnal pendapatan jasa');
                return redirect ('jurnal-pembayaran-hutang/index');
            }

            if ($request->balance > 0 || $request->balance == null)
            {
                message(false, '', 'Maaf tidak bisa input jurnal deposit karena debet dan kredit beda atau balance kosong');
                return redirect('jurnal-pembayaran-hutang/index');
            }

            if (isset($request->id_perkiraan))
            {
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
                        'ref'=>'N',
                        'debet'=>$data['debet'][$i],
                        'kredit'=>$data['kredit'][$i],);

                    DetailJurnal::insert($insert);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet' , $data['debet'][$i]);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit' , $data['kredit'][$i]);
                }
                PembayaranSupplier::whereIn('id_pembelian', $request->id_pembelian)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);
                //update tabel pembelian kolom ref dan no_jurnal
            }
            DB::commit();
            message($act, 'Jurnal Pembayaran Hutang berhasil disimpan', 'Jurnal Pembayaran Hutang berhasil gagal disimpan');
            return redirect ('jurnal-pembayaran-hutang/index');
        }
        catch (Exception $e)
        {
            DB::rollback();
        }
    }
}
