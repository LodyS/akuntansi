<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\DetailJurnal;
use App\Jurnal;
use App\TipeJurnal;
use App\Models\Perkiraan;
use App\SettingCoa;
use App\Models\InstansiRelasi;
use App\pembelian;
use App\JenisPembelian;
use App\transaksi;
use App\Models\KasBank;
use Illuminate\Http\Request;

class JurnalPembelianLogistikFarmasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-pembelian-logistik-farmasi');
    }

    public function index ()
    {
        $tanggal = date('Y-m-d');
        $rekapitulasi = DB::table('pembelian')
        ->selectRaw("pembelian.no_faktur AS bukti_pembelian, pembelian.id as id_pembelian,
        instansi_relasi.nama AS supplier, pembelian.jumlah_nominal AS total_pembelian, pembelian.diskon AS diskon, pembelian.materai AS materai,
        pembelian.ppn AS ppn, pembelian.jumlah_tagihan AS total_yang_harus_dibayar, kas_bank.nama AS cara_pembayaran, perkiraan.nama AS perkiraan")
        ->leftJoin('jenis_pembelian', 'jenis_pembelian.id', 'pembelian.id_jenis_pembelian')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->leftJoin('kas_bank', 'kas_bank.id', 'pembelian.id_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'jenis_pembelian.id_perkiraan_pembelian')
        //->where('pembelian.status', $request->status)
        ->where('pembelian.waktu', $tanggal)
        //->where('pembelian.id_jenis_pembelian', $request->jenis_pembelian)
        ->paginate(30);

        $jenisPembelian = JenisPembelian::select('id', 'nama')->get();

        return view ('jurnal-pembelian-logistik-farmasi/index', compact('jenisPembelian', 'rekapitulasi'));
    }

    public function rekapitulasi (Request $request)
    {
        $tanggal = $request->tanggal;
        $status = $request->status;
        $jenis_pembelian = $request->jenis_pembelian;
        $jenisPembelian = JenisPembelian::select('id', 'nama')->get();
        $pembelian_jenis = DB::table('jenis_pembelian')
        ->selectRaw('pajak.nama as pajak, materai.nama as materai, diskon.nama as diskon')
        ->leftJoin('perkiraan as pajak', 'pajak.id', 'jenis_pembelian.id_perkiraan_pajak')
        ->leftJoin('perkiraan as materai', 'materai.id', 'jenis_pembelian.id_perkiraan_materai')
        ->leftJoin('perkiraan as diskon', 'diskon.id', 'jenis_pembelian.id_perkiraan_diskon')
        ->where('jenis_pembelian.id', $jenis_pembelian)
        ->first();

        $rekapitulasi = DB::table('pembelian')
        ->selectRaw("pembelian.no_faktur AS bukti_pembelian, pembelian.id as id_pembelian,
        instansi_relasi.nama AS supplier, pembelian.jumlah_nominal AS total_pembelian, pembelian.diskon AS diskon, pembelian.materai AS materai,
        pembelian.ppn AS ppn, pembelian.jumlah_tagihan AS total_yang_harus_dibayar, kas_bank.nama AS cara_pembayaran, perkiraan.nama AS perkiraan")
        ->leftJoin('jenis_pembelian', 'jenis_pembelian.id', 'pembelian.id_jenis_pembelian')
        ->leftJoin('instansi_relasi', 'instansi_relasi.id', 'pembelian.id_instansi_relasi')
        ->leftJoin('kas_bank', 'kas_bank.id', 'pembelian.id_bank')
        ->leftJoin('perkiraan', 'perkiraan.id', 'jenis_pembelian.id_perkiraan_pembelian')
        ->where('pembelian.status', $request->status)
        ->where('pembelian.waktu', $request->tanggal)
        ->where('pembelian.id_jenis_pembelian', $request->jenis_pembelian)
        ->paginate(30);

        return view('jurnal-pembelian-logistik-farmasi/index', compact('jenisPembelian', 'rekapitulasi','pembelian_jenis','tanggal','jenis_pembelian','status'));
    }

    public function jurnal (Request $request)
    {
        $status = $request->status;
        $tanggal = $request->tanggal;
        $id_pembelian = $request->id_pembelian;
        $tipe_jurnal = TipeJurnal::find('2'); //untuk mendapatan id data jurnal Cash Dishburtment Journal
        //jika status bayar 2/tunai maka id perkiraan bagian bank adalah kas, jika 1/kredit maka id_perkiraan bagian bank adalah hutang usaha

        $jurnal = Jurnal::selectRaw('CONCAT("CDJ-", SUBSTR(kode_jurnal, 5)+1) AS kode_jurnal')
        ->where('kode_jurnal', 'like', '%CDJ%')
        ->orderByDesc('id')
        ->first();

        if ($status == 2)
        {
            $pembelian = DB::select("SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan,
            pembelian.jumlah_nominal AS debit, 1-1 AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_pembelian
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan, pembelian.materai AS debit, 1-1 AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_materai
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan, pembelian.ppn AS debit, 1-1 AS kredit
            FROM pembelian LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_pajak WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan, 1-1 AS debit, pembelian.jumlah_nominal AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN setting_coa ON setting_coa.id_bank = pembelian.id_bank
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan, 1-1 AS debit, pembelian.diskon
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_diskon
            WHERE pembelian.id = '$request->id_pembelian'");

        }

        if ($status ==1)
        {
            $pembelian = DB::select("SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan,
            pembelian.jumlah_nominal AS debit, 1-1 AS kredit
            FROM  pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_pembelian
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan, pembelian.materai AS debit, 1-1 AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_materai
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan, pembelian.ppn AS debit, 1-1 AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_pajak
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode,perkiraan.nama AS perkiraan,  1-1 AS debit, pembelian.jumlah_nominal AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_hutang
            WHERE pembelian.id = '$request->id_pembelian'

            UNION ALL

            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode,perkiraan.nama AS perkiraan, 0 AS debet, pembelian.diskon AS kredit
            FROM pembelian
            LEFT JOIN jenis_pembelian ON jenis_pembelian.id = pembelian.id_jenis_pembelian
            LEFT JOIN perkiraan ON perkiraan.id = jenis_pembelian.id_perkiraan_diskon
            WHERE pembelian.id = '$request->id_pembelian'");
        }
        // untuk mendapat kode jurnal sesuai urutan
        return view ('jurnal-pembelian-logistik-farmasi/jurnal', compact('pembelian','tanggal','jurnal','tipe_jurnal', 'id_pembelian', 'status'));
    }

    public function SimpanJurnalPembelianLogistik (Request $request)
    {
        $id_user = Auth::user()->id;
        DB::beginTransaction();

        try {

            if ($request->balance > 0 || $request->balance === null)
            {
                message(false, '', 'Maaf tidak bisa input jurnal logistik dan farmasi karena debet dan kredit beda');
                return redirect('jurnal-pembelian-logistik-farmasi/index');
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
                    'ref'=>'N',
                    'debet'=>$data['debet'][$i],
                    'kredit'=>$data['kredit'][$i],);

                DetailJurnal::insert($insert);
                transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('debet' , $data['debet'][$i]);
                transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])->increment('kredit', $data['kredit'][$i]);
            }

            pembelian::where('id', $request->id_pembelian)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);
            //update tabel pembelian kolom ref dan no_jurnal

            DB::commit();
            message($act, 'Jurnal Pembelian Logistik Farmasi berhasil disimpan', 'Jurnal Pembelian Logistik gagal disimpan');
            return redirect ('jurnal-pembelian-logistik-farmasi/index');
        }
        catch (Exception $e)
        {
            DB::rollback();
            return redirect ('jurnal-pembelian-logistik-farmasi/index');
        }
    }
}
