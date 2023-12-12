<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Jurnal;
use App\DetailJurnal;
use App\Voucher;
use App\Models\PembayaranInvoice;
use Illuminate\Http\Request;

class JurnalPembayaranInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-pembayaran-invoice');
    }

    public function index()
    {
        $tanggal = date('Y-m-d');
        $rekapitulasi = DB::table('pembayaran_invoice')
        ->selectRaw('pembayaran_invoice.id, invoice.number, pelanggan.nama AS pelanggan, pembayaran_invoice.total,
        invoice.invoice_date as tanggal, pembayaran_invoice.pph_23, pembayaran_invoice.total - pembayaran_invoice.pph_23 AS total_bayar')
        ->leftJoin('invoice', 'invoice.id', 'pembayaran_invoice.id_invoice')
        ->leftJoin('pelanggan', 'pelanggan.id', 'invoice.id_pelanggan')
        ->where('pembayaran_invoice.tanggal', $tanggal)
        ->where('pembayaran_invoice.flag_jurnal', 'N')
        ->paginate(30);

        return view ('jurnal-pembayaran-invoice/index', compact('rekapitulasi'));
    }

    public function rekapitulasi (Request $request)
    {
        $request->validate([
            'tanggal'=>'required',
        ]);

        $rekapitulasi = DB::table('pembayaran_invoice')
        ->selectRaw('pembayaran_invoice.id, invoice.number, pelanggan.nama AS pelanggan, pembayaran_invoice.total,
        invoice.invoice_date as tanggal, pembayaran_invoice.pph_23, pembayaran_invoice.total - pembayaran_invoice.pph_23 AS total_bayar')
        ->leftJoin('invoice', 'invoice.id', 'pembayaran_invoice.id_invoice')
        ->leftJoin('pelanggan', 'pelanggan.id', 'invoice.id_pelanggan')
        ->where('pembayaran_invoice.tanggal', $request->tanggal)
        ->where('pembayaran_invoice.flag_jurnal', 'N')
        ->paginate(30);

        return view('jurnal-pembayaran-invoice/index', compact('rekapitulasi'));
    }

    public function jurnal (Request $request)
    {
        $tipe_jurnal = Jurnal::selectRaw('CONCAT("CDJ-", SUBSTR(kode_jurnal, 5)+1) AS kode_jurnal, id_tipe_jurnal')
        ->where('kode_jurnal', 'like', 'CRJ%')
        ->orderByDesc('id')
        ->first();

        $jurnal = DB::select("SELECT perkiraan.id, perkiraan.kode, perkiraan.nama, pembayaran_invoice.jumlah_bayar AS debet, 0 AS kredit FROM
        pembayaran_invoice
        LEFT JOIN setting_coa ON setting_coa.id_bank = pembayaran_invoice.id_bank
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE pembayaran_invoice.id='$request->id'

        UNION ALL

        SELECT
        (SELECT perkiraan.id FROM setting_coa
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE setting_coa.jenis='Pendapatan Jasa' AND setting_coa.keterangan='invoice') AS id,
        (SELECT perkiraan.kode FROM setting_coa
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE setting_coa.jenis='Pendapatan Jasa' AND setting_coa.keterangan='invoice') AS kode,
        (SELECT perkiraan.nama FROM setting_coa
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE setting_coa.jenis='Pendapatan Jasa' AND setting_coa.keterangan='invoice') AS nama, pembayaran_invoice.pph_23 AS debet, 0 as kredit
        FROM pembayaran_invoice WHERE id='$request->id'

        UNION ALL

        SELECT
        (SELECT perkiraan.id FROM setting_coa
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE setting_coa.jenis='PPN' AND setting_coa.keterangan='invoice') AS id,
        (SELECT perkiraan.kode FROM setting_coa
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE setting_coa.jenis='PPN' AND setting_coa.keterangan='invoice') AS kode,
        (SELECT perkiraan.nama FROM setting_coa
        LEFT JOIN perkiraan ON perkiraan.id = setting_coa.id_perkiraan
        WHERE setting_coa.jenis='PPN' AND setting_coa.keterangan='invoice') AS nama, 0 AS debet, pembayaran_invoice.jumlah_bayar + pph_23 AS kredit
        FROM pembayaran_invoice WHERE id='$request->id'");
        $id_pembayaran_invoice = $request->id;

        return view ('jurnal-pembayaran-invoice/jurnal-umum', compact('jurnal', 'tipe_jurnal', 'id_pembayaran_invoice'));
    }

    public function simpan (Request $request)
    {
        $id_user = Auth::user()->id;
        if ($request->balance >0 || in_array(null, $request->id_perkiraan))
        {
            message(false, 'Gagal simpan jurnal', 'Gagal simpan Jurnal');
            return back();
        }

        DB::beginTransaction();

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
            }
            $tanggal = date('Ymd');
            $voucher = Voucher::selectRaw('substr(kode, 13) +1 as kode')->orderByDesc('id')->first();
            $kode_voucher = isset($voucher) ? "KD.".$tanggal.'.'.$voucher->kode : "KD.".$tanggal.".1";

            //dd($kode_voucher);

            $voucher = new Voucher;
            $voucher->kode = $kode_voucher;
            $voucher->id_jurnal = $id_jurnal;
            $voucher->save();

            PembayaranInvoice::where('id', $request->id_pembayaran_invoice)->update([
            'flag_jurnal'=>'Y',
            'no_jurnal'=>$id_jurnal]);
            DB::commit();

        } catch (Exception $e){
            DB::rollback();
        }
        message(true, 'Jurnal berhasil disimpan', 'Jurnal gagal disimpan');
        return redirect('jurnal-pembayaran-invoice/index');
    }
}
