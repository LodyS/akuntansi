<?php

namespace App\Http\Controllers;

use App\BukuBesarPembantu;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Pelanggan;
use App\Models\TerminPembayaran;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class InvoiceController extends Controller
{
    public $viewDir = "invoice";
    public $breadcrumbs = array(
        'permissions' => array('title' => 'invoice', 'link' => "#", 'active' => false, 'display' => true),
    );

    public function __construct()
    {
        $this->middleware('permission:read-invoice');
    }

    public function index()
    {
        $pelanggan = Pelanggan::join('invoice','pelanggan.id','invoice.id_pelanggan')->select('pelanggan.id','pelanggan.nama')->distinct()->get();
        return view("invoice.index")->with(['pelanggan' => $pelanggan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $termin = TerminPembayaran::all('id','kode','termin','jumlah_hari');
        $pelanggan = Pelanggan::all('id','nama');
        $item = Item::all('id','nama','harga');
        return view("invoice.form",compact(['termin','pelanggan','item']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$this->validate($request, Invoice::validationRules());
        DB::beginTransaction();
        try {
            $id_invoice = Invoice::insertGetId([
                'id_termin_pembayaran' => $request->input('id_termin_pembayaran'),
                'id_pelanggan' => $request->input('id_pelanggan'),
                'number' => $request->input('number'),
                'invoice_date' => ($request->input('invoice_date')),
                'payment' => $request->input('payment'),
                'due_date' => dbDate($request->input('due_date')),
                'pesan' => $request->input('pesan'),
                'total' => $request->input('grandTotal', 0),
                'ppn' => $request->input('ppn', 0),
                'subtotal' => $request->input('subTotal', 0),
                'user_input' => Auth::user()->id,
                'flag_cetak' => 'N',
                'flag_jurnal' => 'N',
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            ]);

            $data = array();
            foreach ($request->input('item') as $key => $value) {
                $data[$key]['id_invoice'] = $id_invoice;
                $data[$key]['id_item'] = $value['id_item'];
                $data[$key]['keterangan'] = $value['explantation'];
                $data[$key]['harga'] = $value['unit_price'];
                $data[$key]['total'] = $value['unit_price'];
                $data[$key]['created_at'] = Carbon::now();
                $data[$key]['created_at'] = Carbon::now();
                $data[$key]['user_input'] = Auth::user()->id;
            }
            DB::table('detail_invoice')->insert($data);

            BukuBesarPembantu::insert([
                'tanggal' => $request->input('invoice_date'),
                'id_pelanggan' => $request->input('id_pelanggan'),
                'id_invoice' => $id_invoice,
                'debet' => $request->input('grandTotal'),
                'kredit' => 0,
                'user_input' => Auth::user()->id,
                "created_at" =>  \Carbon\Carbon::now()
            ]);

            DB::commit();
            message(true, 'Data Invoice berhasil ditambahkan', 'Data Invoice gagal ditambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            message(false,  'Data Invoice berhasil ditambahkan', 'Data Invoice gagal ditambahkan');
        }
        return redirect('invoice');
    }

    /**
     * Display the specified resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, $kode)
    {
        $invoice = Invoice::find($kode);
        $detail_invoice = DB::table('detail_invoice')
        ->join('item','detail_invoice.id_item','item.id')
        ->where('id_invoice',$kode)->get();
        return view("invoice.detail")->with(['invoice' => $invoice, 'detail_invoice' => $detail_invoice]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function edit(Request $request, $kode)
    {
        // $invoice = "";
        // return view("invoice.detail")->with(['invoice' => $invoice]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        // $infoPembayaranInvoice = InfoPembayaranInvoice::find($kode);
        // if ($request->isXmlHttpRequest()) {
        //     $data = [$request->name  => $request->value];
        //     $validator = \Validator::make($data, InfoPembayaranInvoice::validationRules($request->name));
        //     if ($validator->fails())
        //         return response($validator->errors()->first($request->name), 403);
        //     $infoPembayaranInvoice->update($data);
        //     return "Record updated";
        // }
        // $this->validate($request, InfoPembayaranInvoice::validationRules());

        // $act = $infoPembayaranInvoice->update($request->all());
        // message($act, 'Data Info Pembayaran Invoice berhasil diupdate', 'Data Info Pembayaran Invoice gagal diupdate');

        // return redirect('/invoice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Request $request, $kode)
    {
        $Invoice = Invoice::find($kode);
        $Invoice->user_delete = Auth::user()->id;
        $Invoice->deleted_at = Carbon::now();
        $Invoice->save();
        // $act = $Invoice->delete();
    }

    protected function view($view, $data = [])
    {
        // return view($this->viewDir . "." . $view, $data);
    }
    public function loadData()
    {
        // dd(request()->all());
        $startDate    = request()->get('tgl_awal');
        $endDate      = request()->get('tgl_akhir');
        $id_pelanggan = request()->get('customer');

        $GLOBALS['nomor'] = \Request::input('start', 1) + 1;
        $dataList = Invoice::select('*');
        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }

        if ($id_pelanggan != 0) {
            $dataList->where('id_pelanggan',$id_pelanggan);
        }

        if ($startDate && $endDate) {
            $dataList->whereBetween('invoice_date', [$startDate,$endDate]);
        }


        return Datatables::of($dataList)
            ->addColumn('nomor', function ($kategori) {
                return $GLOBALS['nomor']++;
            })
            ->addColumn('nama', function ($data) {
                return $data->pelanggan->nama;
            })
            ->addColumn('total', function ($data) {
                return (isset($data->total)) ? nominalKoma($data->total) : 0;
            })
            ->addColumn('ppn', function ($data) {
                return (isset($data->ppn)) ? nominalKoma($data->ppn) : 0;
            })
            ->addColumn('subtotal', function ($data) {
                return (isset($data->subtotal)) ? nominalKoma($data->subtotal) : 0;
            })
            ->addColumn('action', function ($data) {
                $edit = url("invoice/" . $data->pk()) . "/edit";
                $delete = url("invoice/" . $data->pk());
                $content = '';
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                return $content;
            })
            ->make(true);
    }
}
