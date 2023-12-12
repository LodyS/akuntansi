<?php
namespace App\Http\Controllers;
use DB;
use App\BukuBesarPembantu;
use App\Models\PembayaranInvoice;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;
use Auth;

class PembayaranInvoiceController extends Controller
{
    public $viewDir = "pembayaran_invoice";
    public $breadcrumbs = array('permissions'=>array('title'=>'Pembayaran-invoice','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-pembayaran-invoice');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        return $this->view("form",['pembayaranInvoice' => new PembayaranInvoice]);
    }

    public function store( Request $request )
    {
        $this->validate($request, PembayaranInvoice::validationRules());

        $act=PembayaranInvoice::create($request->all());
        message($act,'Data Pembayaran Invoice berhasil ditambahkan','Data Pembayaran Invoice gagal ditambahkan');
        return redirect('pembayaran-invoice');
    }

    public function show(Request $request, $kode)
    {
        $pembayaranInvoice=PembayaranInvoice::find($kode);
        return $this->view("show",['pembayaranInvoice' => $pembayaranInvoice]);
    }

    public function edit(Request $request, $kode)
    {
        $pembayaranInvoice=PembayaranInvoice::find($kode);
        return $this->view( "form", ['pembayaranInvoice' => $pembayaranInvoice] );
    }

    public function pembayaran(Request $request)
    {
        $bank = DB::table('kas_bank')->select('id', 'nama')->get();
        $data = PembayaranInvoice::selectRaw('invoice.id as invoice_id, pembayaran_invoice.id as id_pembayaran_invoice, 
        pelanggan.nama as pelanggan, pelanggan.id as id_pelanggan,
        number, invoice_date, payment, due_date, item.nama as item, detail_invoice.harga, detail_invoice.keterangan, detail_invoice.total, 
        invoice.subtotal, invoice.ppn, sum(pembayaran_invoice.jumlah_bayar) as pembayaran')
        ->RightJoin('invoice', 'invoice.id', 'pembayaran_invoice.id_invoice')
        ->LeftJoin('detail_invoice', 'detail_invoice.id_invoice', 'invoice.id')
        ->LeftJoin('item', 'item.id', 'detail_invoice.id_item')
        ->LeftJoin('pelanggan', 'pelanggan.id', 'invoice.id_pelanggan')
        ->where('invoice.id', $request->id)
        ->firstOrFail();

        return $this->view('pembayaran', compact('data', 'bank'));
    }

    public function save (Request $request)
    {
        $id_user = Auth::user()->id;

        $request->validate([
            'jumlah_pembayaran'=>'required',
            'pph23'=>'required',
            'tanggal_pembayaran'=>'required',
            'id_bank'=>'required',
        ]);

        try {
            
            DB::beginTransaction();

            $jumlah_pembayarann = str_replace('.', '', $request->jumlah_pembayaran);
            $jumlah_pembayaran = str_replace(',', '.', $jumlah_pembayarann);
            $pph_23 = str_replace('.', '', $request->pph23);
            $pph23 = str_replace(',', '.', $pph_23);
            $kurang_bayar = $request->total_bayar - $jumlah_pembayaran;
            //dd($pph23);

            $act = new PembayaranInvoice;
            $act->tanggal = $request->tanggal_pembayaran;
            $act->id_pelanggan = $request->id_pelanggan;
            $act->id_invoice = $request->id_invoice;
            $act->sub_total = $request->subtotal;
            $act->ppn = $request->ppn;
            $act->total = $request->total;
            $act->pph_23 = $pph23;
            $act->jumlah_bayar = $jumlah_pembayaran;
            $act->id_bank = $request->id_bank;
            $act->kurang_bayar = $kurang_bayar;
            $act->flag_jurnal = 'N';
            $act->user_input = $id_user;
            $act->save();

            $bukuBesar = new BukuBesarPembantu;
            $bukuBesar->tanggal = $request->tanggal_pembayaran;
            $bukuBesar->id_pelanggan = $request->id_pelanggan;
            $bukuBesar->id_pembayaran_invoice = $act->id;
            $bukuBesar->kredit = $jumlah_pembayaran;
            $bukuBesar->user_input = $id_user;
            $bukuBesar->save();

            DB::commit();
            message($act, 'Pembayaran Invoice berhasil di simpan', 'Pembayaran Invoice gagal disimpan');

        } catch (Exception $e){
            DB::rollback();
            message(false, 'Gagal simpan', 'Gagal simpan');
        }
        return redirect('/pembayaran-invoice');
    }

    public function update(Request $request, $kode)
    {
        $pembayaranInvoice=PembayaranInvoice::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, PembayaranInvoice::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $pembayaranInvoice->update($data);
                return "Record updated";
           }
        $this->validate($request, PembayaranInvoice::validationRules());
        $act=$pembayaranInvoice->update($request->all());
        message($act,'Data Pembayaran Invoice berhasil diupdate','Data Pembayaran Invoice gagal diupdate');
        return redirect('/pembayaran-invoice');
    }

    public function destroy(Request $request, $kode)
    {
        $pembayaranInvoice=PembayaranInvoice::find($kode);
        $act=false;
        try {
            $act=$pembayaranInvoice->forceDelete();
        } catch (\Exception $e) {
            $pembayaranInvoice=PembayaranInvoice::find($pembayaranInvoice->pk());
            $act=$pembayaranInvoice->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $customer = request()->get('customer');
        $due_date = request()->get('due_date');
        $invoice_date = request()->get('invoice_date');
        $number = request()->get('number');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = PembayaranInvoice::selectRaw('invoice.id, pelanggan.nama as pelanggan, number, invoice_date, due_date, 
        payment, invoice.total, sum(jumlah_bayar) as pembayaran, invoice.total - sum(jumlah_bayar) as sisa_tagihan')
        ->RightJoin('invoice', 'invoice.id', 'pembayaran_invoice.id_invoice')
        ->leftJoin('pelanggan', 'pelanggan.id', 'invoice.id_pelanggan')
        ->groupBy('invoice.id');

        if ($due_date){
            $dataList->where('due_date', $due_date);
        }

        if ($invoice_date){
            $dataList->where('invoice_date', $invoice_date);
        }

        if ($number){
            $dataList->where('number', 'like', $number.'%');
        }

        if ($customer){
            $dataList->where('pelanggan.nama', 'like', $customer.'%');
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('payment', function ($data) {
            
            if (isset($data->payment)){
                $payment = nominalTitik($data->payment);
                return $payment;
            } else {
                return 0;
            }
        })->addColumn('pembayaran', function ($data) {
            
            if (isset($data->pembayaran)){
                $pembayaran = nominalTitik($data->pembayaran);
                return $pembayaran;
            } else {
                return 0;
            }
        })->addColumn('due_date', function ($data) {
            
            if (isset($data->due_date)){
                $due_date = date('d-m-Y', strtotime($data->due_date));
                return $due_date;
            } else {
                return 00-00-0000;
            }
        })->addColumn('invoice_date', function ($data) {
            
            if (isset($data->invoice_date)){
                $due_date = date('d-m-Y', strtotime($data->invoice_date));
                return $due_date;
            } else {
                return 00-00-0000;
            }
        })->addColumn('sisa_tagihan', function ($data) {
            
            if (isset($data->sisa_tagihan)){
                $sisa_tagihan = nominalTitik($data->sisa_tagihan);
                return $sisa_tagihan;
            } else {
                return 0;
            }
        })->addColumn('total', function ($data) {
            
            if (isset($data->total)){
                $total = nominalTitik($data->total);
                return $total;
            } else {
                return 0;
            }
        })->addColumn('action', function ($data) {
            $edit=url("pembayaran-invoice/".$data->id)."/edit";
            $delete=url("pembayaran-invoice/".$data->pk());
            $content = '';
            $content .= "<a href='pembayaran-invoice/pembayaran/".$data->pk()."' class='btn btn-outline-succes btn-sm'>Bayar</a>";
            //$content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            //data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            //data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}