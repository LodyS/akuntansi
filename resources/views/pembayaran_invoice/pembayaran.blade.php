@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pembayaran Invoice</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">

    </div>
</div>


<div class="page-content">
    <!-- Panel Table Tools -->
    <div class="panel">
      <header class="panel-heading">
        <div class="form-group col-md-12">
          <div class="form-group">
        <!-- <h3 class="panel-title">Table Tools</h3> -->

          </div>
        </div>
      </header>
      <div class="panel-body">
            <form action="{{ url('/simpan-pembayaran-invoice') }}" method="post" id="form-pembayaran">{{ @csrf_field() }}

                <div class="form-group row">
                    <label class="col-md-3">Customer</label>
                        <div class="col-md-7">
                        <input type="hidden" name="id_pelanggan" value="{{ $data->id_pelanggan }}" class="form-control">
                        <input type="hidden" name="id_invoice"  value="{{ $data->invoice_id }}" class="form-control">
                        <input type="text" value="{{ $data->pelanggan }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Number</label>
                        <div class="col-md-7">
                        <input type="number" value="{{ $data->number }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Invoice Date</label>
                        <div class="col-md-7">
                        <input type="text" value="{{date('d-m-Y', strtotime($data->invoice_date))}}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Payment</label>
                        <div class="col-md-7">
                        <input type="text" value="{{ $data->payment }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Due Date</label>
                        <div class="col-md-7">
                        <input type="date" value="{{ $data->due_date }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Tanggal Pembayaran</label>
                        <div class="col-md-7">
                        <input type="date" value="{{ date('Y-m-d') }}" name="tanggal_pembayaran" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Bank</label>
                        <div class="col-md-7">
                            <select name="id_bank" id="id_bank" class="form-control" required>
                            <option value="">Pilih Bank</option>
                            @foreach ($bank as $bang)
                            <option value="{{ $bang->id }}">{{ $bang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <table class="table table-hover">
                    <tr>
                        <th>Item</th>
                        <th>Unit Price</th>
                        <th>Explanation</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>{{ $data->item }}</td>
                        <td>Rp. {{ number_format($data->harga,2,",",".") }}</td>
                        <td>{{ $data->keterangan }}</td>
                        <td>Rp. {{ number_format($data->total,2,",",".") }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                        <input type="hidden" name="subtotal" id="subtotal" value="{{ $data->subtotal }}" class="form-control">
                        Sub Total : <input type="text" class="form-control" readonly value="Rp. {{ number_format($data->subtotal,2,",",".")}}"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <input type="hidden" name="ppn" id="ppn" value="{{ $data->ppn }}" class="form-control">
                        <td>PPN : <input type="text" value="{{ number_format($data->ppn,2,",",".")}}" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>PPH 23 : <input type="text" name="pph23" id="pph23" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <input type="hidden" name="total" id="total" class="form-control">
                        <td>Total : <input type="text" id="total_rupiah" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <input type="hidden" value="{{ ($data->pembayaran == null) ? 0 : $data->pembayaran }}" id="pembayaran" name="pembayaran">
                        <td>Pembayaran : <input type="text" value="Rp. {{ ($data->pembayaran == null) ? 0 : number_format($data->pembayaran) }}" 
                        class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <input type="hidden" name="total_bayar" id="total_bayar" class="form-control">
                        <td>Total yang harus dibayar : <input type="text" id="total_bayar_rupiah" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Jumlah Pembayaran : <input type="text" id="jumlah_pembayaran" name="jumlah_pembayaran" class="form-control" required></td>
                    </tr>
                </table>

                <div class="form-group row">
                    <div class="col-xs-1"></div>
                        <div class="col-md-1">
                        <button class="btn btn-primary" type="submit" id="simpan">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')

<script type="text/javascript">
function formatRupiah(number) {
  return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('#pph23').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#jumlah_pembayaran').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).on('change', "#pph23", "#ppn, #subtotal, #pembayaran",  function() {
	var pph = $("#pph23").val()
    var ppn = $("#ppn").val()
	var subtotal = $("#subtotal").val()
    var pembayaran = $("#pembayaran").val()
	var pph_23 = Number(pph.replace(/[^0-9]+/g,""));
    var result = parseFloat(subtotal) - parseFloat(pph_23) + parseFloat(ppn)
    var total_yang_harus_dibayar = parseFloat(subtotal) - parseFloat(pph_23) + parseFloat(ppn) - parseFloat(pembayaran)
    
    $("#total").val(result)
    $("#total_rupiah").val(formatRupiah(result))
    $("#total_bayar").val(total_yang_harus_dibayar)
    $("#total_bayar_rupiah").val(formatRupiah(total_yang_harus_dibayar))
});

</script>
@endpush
