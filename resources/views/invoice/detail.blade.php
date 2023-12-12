<div class="modal-dialog modal-content modal-lg">

    <form action="" method="post">


        <div class="modal-header" style="border-bottom: 1px solid gray">
            <button type="button" class="close" style="margin-top: -15px !important" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" >Detail Invoice</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <label class="col-auto" style="width: 180px">Nama</label>
                        <label class="col">: {{ $invoice->pelanggan->nama}}</label>
                    </div>
                    <div class="row">
                        <label class="col-auto" style="width: 180px">Number</label>
                        <label class="col">: {{ $invoice->number}}</label>
                    </div>
                    <div class="row">
                        <label class="col-auto" style="width: 180px">Invoice Date</label>
                        <label class="col">: {{ $invoice->invoice_date}}</label>
                    </div>
                    <div class="row">
                        <label class="col-auto" style="width: 180px">Payment</label>
                        <label class="col">: {{ $invoice->payment}}</label>
                    </div>
                    <div class="row">
                        <label class="col-auto" style="width: 180px">Due Date</label>
                        <label class="col">: {{ $invoice->due_date}}</label>
                    </div>
                    <div class="row">
                        <label class="col-auto" style="width: 180px">Alamat</label>
                        <label class="col">: {{ $invoice->pelanggan->alamat}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table">
                        <tr>
                            <th class="text-left" style="width: 20px">No</th>
                            <th class="text-left" >Item</th>
                            <th class="text-left" style="width: 130px">Explanation</th>
                            <th class="text-right text-nowrap" style="width: 130px">Unit Price</th>
                            <th class="text-right text-nowrap" style="width: 130px">Total</th>
                        </tr>
                        @forelse ($detail_invoice as $key => $di)
                            <tr>
                                <td class="text-left">{{$key + 1}}</td>
                                <td>{{ $di->nama }}</td>
                                <td>{{ $di->keterangan }}</td>
                                <td class="text-right text-nowrap">{{ nominalKoma($di->harga) }}</td>
                                <td class="text-right text-nowrap">{{ nominalKoma($di->total) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Tidak ada data item.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="4" class="text-right">Subtotal</td>
                            <td class="text-right">{{ nominalKoma($invoice->subtotal) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">Sales Tax</td>
                            <td class="text-right">{{ nominalKoma($invoice->ppn) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">Total</td>
                            <td class="text-right">{{ nominalKoma($invoice->total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    </div>

