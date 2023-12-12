<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\penjualan;
use App\Models\KasBank;
use App\Models\Perkiraan;
use App\SettingCoa;
use App\TipeJurnal;
use App\Jurnal;
use App\kelas;
use App\transaksi;
use App\Voucher;
use App\Models\Pelanggan;
use App\Models\PeriodeKeuangan;
use App\visit;
use App\DetailJurnal;
use App\penjualan_resep;
use App\tipe_pasien;
use Illuminate\Http\Request;

class JurnalPenjualanObatTunaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-penjualan-obat-tunai');
    }

    public function index ()
    {
        $perkiraan = Perkiraan::select('id', 'nama')->get();

        return view('jurnal-penjualan-obat-tunai/index', compact('perkiraan'));
    }

    public function RekapitulasiPenjualanObatTunai (Request $request)
    {
        $tanggal= $request->tanggal;
        $jenis_pasien = $request->jenis_pasien;
        $jenis_pembayaran = $request->jenis_pembayaran;
        $id_tipe_pasien = $request->tipe_pasien;
        $tipe_obat = $request->tipe_obat;
        $tipe_pasien = tipe_pasien::find($id_tipe_pasien);

        if ($tipe_obat == 'Resep')
        {
            $query = DB::table('penjualan')
            ->selectRaw("penjualan.id as id_penjualan, penjualan.kode AS bukti_penjualan, total_penjualan, diskon, pajak, total_tagihan,
            kas_bank.nama as cara_bayar, penjualan_resep.id_visit AS no_kunjungan, pelanggan.nama AS pasien,
            (
                select p.nama from perkiraan p join setting_coa s on p.id = s.id_perkiraan
                where s.keterangan = 'Pendapatan Obat'
                and s.type_obat = '$tipe_obat'
                and s.type_bayar = '$jenis_pembayaran'
                and s.tipe_pasien = '$id_tipe_pasien'
                and s.type = '$jenis_pasien'
                limit 1
            ) as perkiraan")
            ->leftJoin('penjualan_resep', 'penjualan_resep.id_penjualan', 'penjualan.id')
            ->leftJoin('visit', 'visit.id', 'penjualan_resep.id_visit')
            ->leftJoin('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
            ->leftJoin('kas_bank', 'kas_bank.id', 'penjualan.id_bank')
            ->where('penjualan.ref', 'N')
            ->whereDate('penjualan.waktu', $tanggal)
            ->where('penjualan.jenis', 'Resep')
            ->where('penjualan.jenis_pasien', $jenis_pasien)
            ->where('penjualan.jenis_pembayaran', $jenis_pembayaran);
            if ($id_tipe_pasien == 1) {
                # jika perusahaan langganan
                $query->whereNotNull('id_produk_asuransi');
            } else {
                $query->whereNull('id_produk_asuransi');
            }
            $rekapitulasi = $query->simplePaginate(25);
            // dd($rekapitulasi); */

        } else {

            $rekapitulasi = DB::table('penjualan')
            ->selectRaw("penjualan.id as id_penjualan, penjualan.kode AS bukti_penjualan, visit.id AS no_kunjungan,
            pelanggan.nama AS pasien, total_penjualan, diskon, pajak, kas_bank.nama AS cara_bayar,
            (SELECT perkiraan.nama FROM setting_coa
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE keterangan='Pendapatan Obat' AND type_obat='Bebas') AS perkiraan")
            ->leftJoin('penjualan_resep', 'penjualan_resep.id_penjualan', 'penjualan.id')
            ->leftJoin('visit', 'visit.id', 'penjualan_resep.id_visit')
            ->leftJoin('pelanggan', 'pelanggan.id', 'visit.id_pelanggan')
            ->leftJoin('kas_bank', 'kas_bank.id', 'penjualan.id_bank')
            ->leftJoin('setting_coa', 'setting_coa.id_kelas', 'penjualan.id_kelas')
            ->leftJoin('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
            ->whereDate('penjualan.waktu', $tanggal)
            ->where('penjualan.jenis', 'Bebas')
            ->where('penjualan.ref', 'N')
            ->simplePaginate(25);
        }

        return view('jurnal-penjualan-obat-tunai/rekapitulasi-penjualan-obat-tunai',
        compact('rekapitulasi','tanggal','tipe_obat', 'jenis_pasien', 'jenis_pembayaran', 'tipe_pasien'));
    }

    public function JurnalPenjualanObatTunai(Request $request)
    {
        $id_penjualan = $request->id_penjualan;
        $id_tipe_pasien = $request->id_tipe_pasien;
        $periodeKeuangan = PeriodeKeuangan::where('status_aktif', 'Y')->first();
        $penjualan = penjualan::selectRaw('id_bank, id_kelas, jenis, jenis_pasien, date(waktu) as tanggal, jenis_pembayaran')
        ->where('id', $id_penjualan)
        ->first();

        $jenis_pembayaran = $penjualan->jenis_pembayaran;
        $tanggal_transaksi = $penjualan->tanggal;
        $jenis_pasien = $penjualan->jenis_pasien;
        $tipe_jurnal = TipeJurnal::find(1);
        $jenis_coa = $jenis_pasien == 'RJ' ? 'Pasien Masih Dirawat RJ' : 'Piutang Pasien Masih Dirawat RI';
        $jurnal = Jurnal::selectRaw('CONCAT("SJ-", SUBSTR(kode_jurnal, 4)+1) AS kode_jurnal')
        ->where('kode_jurnal', 'like', 'SJ%')
        ->orderByDesc('id')
        ->first();

        $tanggal = date('Ymd');
        $voucher = Voucher::selectRaw('substr(kode, 13) +1 as kode')->orderByDesc('id')->first();
        $kode_voucher = isset($voucher) ? "KD.".$tanggal.'.'.$voucher->kode : "KD.".$tanggal.".1";

        if ($jenis_pembayaran == 'Kredit')
        {
            $debet_satu = DB::select("SELECT
            (SELECT perkiraan.id FROM setting_coa
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.type='RJ' AND keterangan='Piutang' AND jenis='$jenis_coa' AND tipe_pasien='$id_tipe_pasien') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.type='RJ' AND keterangan='Piutang' AND jenis='$jenis_coa' AND tipe_pasien='$id_tipe_pasien') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.type='RJ' AND keterangan='Piutang' AND jenis='$jenis_coa' AND tipe_pasien='$id_tipe_pasien') AS perkiraan,

            (penjualan.total_penjualan + penjualan.pajak - penjualan.diskon) AS debet, 0 AS kredit FROM penjualan
            WHERE penjualan.id='$id_penjualan'

            UNION ALL

            SELECT (SELECT perkiraan.id FROM setting_coa
            LEFT JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='diskon') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            LEFT JOIN perkiraan ON perkiraan.id =setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='diskon') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            LEFT JOIN perkiraan  ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='diskon') AS perkiraan,

            (penjualan.diskon) AS debet, 0 AS kredit FROM penjualan WHERE penjualan.id = '$id_penjualan'

            UNION ALL

            SELECT
            (SELECT perkiraan.id FROM setting_coa
            JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='pajak') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='pajak') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='pajak') AS perkiraan,

            0 AS debet, penjualan.pajak AS kredit FROM penjualan WHERE penjualan.id = '$id_penjualan'

            UNION ALL

            SELECT
            (SELECT perkiraan.id FROM setting_coa
            JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND
            setting_coa.type_obat='Bebas') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='Bebas') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            JOIN perkiraan perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND
            setting_coa.type_obat='Bebas') AS perkiraan,

            0 AS debet, penjualan.total_penjualan AS kredit FROM penjualan WHERE penjualan.id = '$id_penjualan'");

        } else {

            $debet_satu = DB::select("
            SELECT perkiraan.id AS id_perkiraan, perkiraan.kode, perkiraan.nama AS perkiraan,
            (penjualan.total_penjualan + penjualan.pajak - penjualan.diskon) AS debet, 0 AS kredit FROM penjualan
            LEFT JOIN kas_bank ON kas_bank.id = penjualan.id_bank
            LEFT JOIN setting_coa ON setting_coa.id_bank = kas_bank.id
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE penjualan.id='$id_penjualan'

            UNION ALL

            SELECT (SELECT perkiraan.id FROM setting_coa
            LEFT JOIN perkiraan  ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='diskon') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            LEFT JOIN perkiraan  ON perkiraan.id =setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='diskon') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='diskon') AS perkiraan,

            (penjualan.diskon) AS debet , 0 AS kredit FROM penjualan WHERE penjualan.id = '$id_penjualan'

            UNION ALL

            SELECT
            (SELECT perkiraan.id FROM setting_coa
            JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='pajak') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='pajak') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND
            setting_coa.type_obat='pajak') AS perkiraan,

            0 AS debet, penjualan.pajak AS kredit FROM penjualan WHERE penjualan.id = '$id_penjualan'

            UNION ALL

            SELECT
            (SELECT perkiraan.id FROM setting_coa
            JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan WHERE setting_coa.keterangan='Pendapatan Obat' AND
            setting_coa.type_obat='Bebas') AS id_perkiraan,

            (SELECT perkiraan.kode FROM setting_coa
            JOIN perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND setting_coa.type_obat='Bebas') AS kode,

            (SELECT perkiraan.nama FROM setting_coa
            JOIN perkiraan perkiraan ON perkiraan.id=setting_coa.id_perkiraan
            WHERE setting_coa.keterangan='Pendapatan Obat' AND
            setting_coa.type_obat='Bebas') AS perkiraan,

            0 AS debet, penjualan.total_penjualan AS kredit FROM penjualan WHERE penjualan.id = '$id_penjualan'");
        }

        return view ('jurnal-penjualan-obat-tunai/jurnal-umum',
        compact('id_penjualan', 'debet_satu','tipe_jurnal','jurnal','kode_voucher', 'periodeKeuangan', 'tanggal_transaksi'));
    }

    public function SimpanObatTunai (Request $request)
    {
        $id_user = Auth::user()->id;
        DB::beginTransaction();

        if ($request->balance > 0 || $request->balance == null)
        {
            message(false, '', 'Maaf tidak bisa input jurnal deposit karena balance tidak seimbang');
            return back();
        }

        if ($request->id_penjualan == null || $request->id_periode == null)
        {
            message(false, 'Gagal simpan Jurnal Penjualan Obat', 'Gagal simpan Jurnal Penjualan Obat');
            return back();
        }

        if (in_array(null, $request->id_perkiraan))
        {
            message(false, '', 'Maaf Nama Perkiraan tidak boleh ada yang kosong');
            return back();
        }

        try {

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

                transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])
                ->where('id_periode', $request->id_periode)
                ->increment('debet',  $data['debet'][$i]);

                transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])
                ->where('id_periode', $request->id_periode)
                ->increment('kredit', $data['kredit'][$i]);
            }

            DB::table('penjualan')->where('id', $request->id_penjualan)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);
            //update tabel penjualan kolom ref dan no_jurnal
            $voucher = new Voucher;
            $voucher->kode = $request->kode_voucher;
            $voucher->id_jurnal = $id_jurnal;
            $voucher->save(); // simpan data ke tabel voucher

            DB::commit();
            message($act, 'Jurnal Penjualan Obat Tunai berhasil disimpan', 'Jurnal Penjualan Obat Tunai gagal disimpan');
            return redirect ('jurnal-penjualan-obat-tunai');
        }
        catch (Exception $e)
        {
            DB::rollback();
            message(false, 'Jurnal Penjualan Obat Tunai gagal disimpan', 'Jurnal Penjualan Obat Tunai gagal disimpan');
            return redirect ('jurnal-penjualan-obat-tunai');
        }
    }
}
