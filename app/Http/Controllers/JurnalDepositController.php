<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Voucher;
use App\Models\Deposit;
use App\Models\Pelanggan;
use App\Models\TipeJurnal;
use App\Jurnal;
use App\DetailJurnal;
use App\transaksi;
use App\Models\PeriodeKeuangan;
use Illuminate\Http\Request;

class JurnalDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-deposit');
    }

    public function index ()
    {
        $tanggal = date('Y-m-d');
        $rekapitulasi = DB::table('deposit')
        ->select('deposit.id','pelanggan.nama as nama_pasien', 'waktu', 'kredit')
        ->leftJoin('pelanggan', 'pelanggan.id', 'deposit.id_pelanggan')
        ->whereDate('waktu', $tanggal)
        ->where('kredit', '>', '0')
        ->where('ref', 'N')
        ->paginate(25);

        return view ('jurnal-deposit/index', compact('rekapitulasi'));
    }

    public function rekapitulasi (Request $request)
    {
        $rekap = DB::table('deposit')->select('id')->where('waktu', $request->tanggal)->first();

        if ($rekap == null):
            message(false, '', 'Rekapitulasi deposit pada tanggal tersebut kosong');
        endif;

        $rekapitulasi = DB::table('deposit')
        ->select('deposit.id','pelanggan.nama as nama_pasien', 'waktu', 'kredit')
        ->leftJoin('pelanggan', 'pelanggan.id', 'deposit.id_pelanggan')
        ->whereDate('waktu', $request->tanggal)
        ->where('kredit', '>', '0')
        ->where('ref', 'N')
        ->paginate(25);

        return view ('jurnal-deposit/index', compact('rekapitulasi'));
    }

    public function jurnal (Request $request)
    {
        if ($request->id == null):
            message(false, '', 'ID Deposit kosong');
            return redirect('jurnal-deposit/rekapitulasi');
        endif;

        $id_deposit = $request->id;
        $jurnal = collect(DB::select("
        SELECT (SELECT id FROM perkiraan WHERE ID=3) AS id_perkiraan,
        (SELECT NAMA FROM perkiraan WHERE ID=3) AS perkiraan, d.kredit AS debet , 1-1 AS kredit FROM deposit d
        WHERE d.ref='N' AND d.id='$id_deposit'
        UNION
        SELECT
        (SELECT p.id FROM setting_coa s LEFT JOIN perkiraan p ON p.id=s.id_perkiraan WHERE s.keterangan='Deposit') AS id_perkiraan,
        (SELECT p.nama FROM setting_coa s LEFT JOIN perkiraan p ON p.id=s.id_perkiraan WHERE s.keterangan='Deposit') AS perkiraan,
        1-1 AS debet, d.kredit AS KREDIT FROM deposit d WHERE d.ref='N' AND d.id='$id_deposit'"));

        $total_debet = $jurnal->sum('debet');
        $total_kredit = $jurnal->sum('kredit');

        $tipe_jurnal = Jurnal::gjCode();

        $id_periode = PeriodeKeuangan::where('status_aktif', 'Y')->first();
        $id_jurnal = TipeJurnal::select('id')->where('kode_jurnal', 'GJ')->first();

        // untuk mendapat kode yang di input ke tabel voucher
        $kode_voucher = Deposit::KodeVoucher();

        return view ('jurnal-deposit/jurnal-umum', compact('jurnal', 'tipe_jurnal', 'id_jurnal', 'id_deposit', 'id_periode', 'kode_voucher', 'total_debet', 'total_kredit'));
    }

    public function simpan (Request $request)
    {
        $id_user = Auth::user()->id;
        DB::beginTransaction();

        try {

            if (in_array(null, $request->id_perkiraan)):
                message(false, '', 'Maaf tidak bisa input jurnal deposit');
                return redirect ('jurnal-deposit/index');
            endif;

            if ($request->balance === null || $request->balance > 0):
				message(false, '', 'Maaf tidak bisa input jurnal deposit karena tidak balance/ debet atau kredit kosong');
                return redirect('jurnal-deposit/index');
            endif;

            if (isset($request->id_periode)):
                $act = new Jurnal;
                $act->kode_jurnal  = $request->kode_jurnal;
                $act->tanggal_posting = $request->tanggal;
                $act->keterangan = $request->keterangan;
                $act->id_tipe_jurnal = $request->id_tipe_jurnal;
                $act->no_dokumen = $request->no_dokumen;
                $act->id_user = $id_user;
                $act->save();

                $id_jurnal = $act->id;
                $data = $request->all();

                for ($i=0; $i<count($data['id_perkiraan']); $i++):
                    $insert = array (
                        'id_jurnal'=>$id_jurnal,
                        'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'ref'=>'N',
                        'debet'=>$data['debet'][$i],
                        'kredit'=>$data['kredit'][$i],);

                    DetailJurnal::insert($insert);
                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])
                    ->where('id_periode', $request->id_periode)
                    ->increment('debet', $data['debet'][$i]);

                    transaksi::where('id_perkiraan', $data['id_perkiraan'][$i])
                    ->where('id_periode', $request->id_periode)
                    ->increment('kredit', $data['kredit'][$i]);
                endfor;

                Deposit::where('id', $request->id_deposit)->update(['ref'=>'Y', 'id_jurnal'=>$id_jurnal]);

                $voucher = new Voucher;
                $voucher->kode = $request->kode_voucher;
                $voucher->id_jurnal = $id_jurnal;
                $voucher->save();
                //update tabel depoist  kolom ref dan no_jurnal
                DB::commit();
                message($act, 'Jurnal Deposit berhasil disimpan', 'Jurnal Deposit berhasil gagal disimpan');
                return redirect ('jurnal-deposit/index');
            endif;

        } catch (Exception $e) {
            DB::rollback();
            message(false, '', 'Gagal simpan jurnal deposit');
            return redirect ('jurnal-deposit/index');
        }
    }
}
