<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Jurnal;
use App\DetailJurnal;
use App\Models\TipeJurnal;
use App\Models\Perkiraan;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Voucher;
use Datatables;

// controller pengeluaran kas
class JurnalInvoiceController extends Controller
{
    public $viewDir = "jurnal-invoice";
    public $breadcrumbs = array(
        'permissions' => array('title' => 'jurnal-invoice', 'link' => "#", 'active' => false, 'display' => true),
    );

    public function __construct()
    {
        $this->middleware('permission:read-jurnal-invoice');
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir . "." . $view, $data);
    }

    public function index()
    {
        return $this->view("index");
    }

    public function createJurnal($idInvoice)
    {
        $tipe_jurnal = TipeJurnal::find(1); //untuk mendapatan nama tipe jurnal Sales Journal
        $kode = Jurnal::selectRaw('CONCAT("SJ-", SUBSTR(kode_jurnal, 4)+1) AS kode')->where('kode_jurnal', 'like', 'SJ%')->orderByDesc('id')->first();

        if ($kode == null) {
            $kode = (object) array("kode"=>"SJ-1");
        }

        $pendapatan_jasa = Perkiraan::selectRaw('perkiraan.id, perkiraan.kode, setting_coa.jenis, 0 as debit, (select subtotal from invoice where id='.$idInvoice.') as kredit')
        ->join('setting_coa', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('setting_coa.keterangan', 'invoice')
        ->where('setting_coa.jenis', 'Pendapatan Jasa');

        $ppn = Perkiraan::selectRaw('perkiraan.id, perkiraan.kode, setting_coa.jenis, 0 as debit, (select ppn from invoice where id='.$idInvoice.') as kredit')
        ->join('setting_coa', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('setting_coa.keterangan', 'invoice')
        ->where('setting_coa.jenis', 'PPN');

        $data = Perkiraan::selectRaw('perkiraan.id, perkiraan.kode, setting_coa.jenis, (select total from invoice where id='.$idInvoice.') as debit, 0 as kredit')
            ->join('setting_coa', 'perkiraan.id', 'setting_coa.id_perkiraan')
            ->where('setting_coa.keterangan', 'invoice')
            ->where('setting_coa.jenis', 'piutang')
            ->union($pendapatan_jasa)
            ->union($ppn)
            ->get();

        // dd( $data->toSql());
        $data_json = response()->json($data)->getContent();
        return $this->view("form-jurnal", [
            "kode" => $kode,
            "tipe_jurnal" => $tipe_jurnal,
            "data" => $data_json,
            "id_invoice" => $idInvoice,
        ]);
    }

    public function storeJurnal(Request $request)
    {
        // dd($request->all());
        if (empty($request->detail)):
            message(false, '', 'Tidak dapat input data perkiraan kosong!');
            return redirect()->back();
        endif;

        $id_user = Auth::user()->id;
        DB::beginTransaction();

        try {
            // insert jurnal
            $jurnal = new Jurnal;
            $jurnal->kode_jurnal        = $request->kode_jurnal;
            $jurnal->tanggal_posting    = $request->tanggal;
            $jurnal->keterangan         = $request->keterangan;
            $jurnal->id_tipe_jurnal     = $request->id_tipe_jurnal;
            $jurnal->no_dokumen         = $request->no_dokumen;
            $jurnal->id_user            = $id_user;
            $jurnal->save();

            $id_jurnal = $jurnal->id;

            // insert DetailJurnal
            foreach ($request->detail as $key => $value):
                $data = [
                    'id_jurnal'     => $id_jurnal,
                    'id_perkiraan'  => $value['id_perkiraan'],
                    'debet'         => $value['debit'],
                    'kredit'        => $value['kredit']
                ];
                DetailJurnal::insert($data);
            endforeach;

            // insert voucher
            Voucher::insert([
                'kode' => Voucher::generateKode(),
                'id_jurnal' => $id_jurnal
            ]);

            //update flag_jurnal
            Invoice::where('id', $request->id_invoice)
            ->update([
                'flag_jurnal' => 'Y',
                'id_jurnal' => $id_jurnal
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        message($jurnal, 'Jurnal Pengeluaran Kas berhasil disimpan', 'Jurnal Pengeluaran Kas gagal disimpan');
        return redirect('/jurnal-invoice');
    }

    public function loadData()
    {
        $startDate  = \Request::input('startDate');
        $endDate    = \Request::input('endDate');

        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = Invoice::select('invoice.id', 'invoice.invoice_date', 'invoice.number', 'invoice.subtotal', 'invoice.ppn', 'pelanggan.nama')
            ->join('pelanggan', 'invoice.id_pelanggan', 'pelanggan.id')
            ->where('invoice.flag_jurnal', 'N');

        if ($startDate && $endDate):
            $dataList->whereBetween('invoice.invoice_date', [dbDate($startDate), dbDate($endDate)]);
        elseif ($startDate && !$endDate):
            $dataList->where('invoice.invoice_date', dbDate($startDate));
        endif;

        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;

        return Datatables::of($dataList)->addColumn('nomor', function ($kategori) {
            return $GLOBALS['nomor']++;
        })
        ->addColumn('action', function ($data) {
            $create = url("jurnal-invoice/create-jurnal/" . $data->pk());
            
            $content = '';
            $content .= " <a href='".$create."' class='btn btn-sm btn-primary'>Buat Jurnal</a>";
              
            return $content;
        })
        ->make(true);
    }
}
