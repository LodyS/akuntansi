@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Invoice</h1>
    @include('layouts.inc.breadcrumb')
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
        <form action="{{ route('invoice.store') }}" method="post">
            @csrf
            <div class="panel-body">

                <div class="form-group row">
                    <label for="number" class="col-md-auto control-label" style="width: 130px">Number</label>
                    <div class="col-md-4 col-sm-6">
                        <input type="text" name="number" id="number" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="invoice_date" class="col-md-auto control-label" style="width: 130px">Invoice Date</label>
                    <div class="col-md-4 col-sm-6">
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_termin_pembayaran" class="col-md-auto control-label" style="width: 130px">Termin</label>
                    <div class="col-md-4 col-sm-6">
                        <select name="id_termin_pembayaran" id="id_termin_pembayaran" class="form-control select2" style="width: 100%">
                            <option></option>
                            @foreach ($termin as $ter)
                            <option data-jumlahHari="{{ $ter->jumlah_hari }}" value="{{ $ter->id }}">{{ $ter->kode.'-'.$ter->termin }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jumlah_hari" id="jumlah_hari">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="payment" class="col-md-auto control-label" style="width: 130px">Payment</label>
                    <div class="col-md-4 col-sm-6">
                        <input type="text" name="payment" id="payment" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="due_date" class="col-md-auto control-label" style="width: 130px">Due Date</label>
                    <div class="col-md-4 col-sm-6">
                        <input readonly type="text" name="due_date" id="due_date" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pesan" class="col-md-auto control-label" style="width: 130px">Pesan</label>
                    <div class="col-md-4 col-sm-6">
                        <textarea name="pesan" id="pesan" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_pelanggan" class="col-md-auto control-label" style="width: 130px">Customer</label>
                    <div class="col-md-4 col-sm-6">
                        <select name="id_pelanggan" id="id_pelanggan" class="form-control select2" style="width: 100%">
                            <option></option>
                            @foreach ($pelanggan as $pel)
                            <option value="{{ $pel->id }}">{{ $pel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total" class="col-md-auto control-label" style="width: 130px">Total</label>
                    <div class="col-md-4 col-sm-6">
                        <input readonly type="text" id="total_rupiah" class="form-control rupiah">
                        <input readonly type="hidden" name="total" id="total" class="form-control">
                    </div>
                </div>

                {{-- detail --}}
                <button class="btn btn-success" type="button" id="add">Tambah</button>

                <table class="table table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 40%">Item</th>
                            <th>Unit Price</th>
                            <th>Explanation</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tambah_form"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Sub Total</td>
                            <td>
                                <input readonly type="text" id="subTotal_rupiah" class="form-control text-right rupiah">
                                <input readonly type="hidden" name="subTotal" id="subTotal" class="form-control text-right">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right">PPN</td>
                            <td>
                                <input readonly type="text" id="ppn_rupiah" class="form-control text-right rupiah">
                                <input type="hidden" name="ppn" id="ppn" class="form-control text-right">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right">Total</td>
                            <td>
                                <input readonly type="text" id="grandTotal_rupiah" class="form-control text-right rupiah">
                                <input readonly type="hidden" name="grandTotal" id="grandTotal" class="form-control text-right">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <button type="submit" id="submit" align="right" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <!-- End Panel Table Tools -->
</div>

@endsection

@push('js')
    <script type="text/javascript">
        function zerofill(n){
            if(n <= 9){
                return "0" + n;
            }
            return n
        }

        function formatRupiah(number) {
            return number.toString().replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function hitungTotal() {
            let totalHargaItem = 0;
            $('.hitung').each( (i,el) => {
                totalHargaItem += parseFloat(el.value || 0)
            });
            $('#subTotal').val(totalHargaItem);
            $('#subTotal_rupiah').val(formatRupiah(totalHargaItem));
            const ppn = parseFloat(totalHargaItem * 0.1);
            $('#ppn_rupiah').val(formatRupiah(ppn));
            $('#ppn').val(ppn);
            const grandTotal = totalHargaItem + ppn;
            $('#grandTotal').val(grandTotal);
            $('#grandTotal_rupiah').val(formatRupiah(grandTotal));
            $('#total_rupiah').val(formatRupiah(grandTotal));
            $('#total').val(grandTotal);
            // console.log(grandTotal);
        }

        $(function () {
            $('.select2').select2({
                placeholder: "-- Pilih --",
                tags: "true",
                allowClear: true
            });


            $('#id_termin_pembayaran').on('select2:select', function (e) {
                const jmlHari = e.params.data.element.dataset.jumlahhari;
                $('#jumlah_hari').val(jmlHari);
                const invoiceDate = $('#invoice_date').val();
                if (!invoiceDate) {
                    swal('Silahkan isi Invoice Date', ' ', 'warning');
                } else {
                    let date = new Date(invoiceDate);
                    date.setDate(date.getDate() + 1)
                    $('#due_date').val( zerofill(date.getDate()) + '/' + zerofill(date.getMonth()+1) + '/' + date.getFullYear() );
                }
            });

            let i = 0;
            $('#add').click(function (e) {
                i++;
                let html = /*html*/`
                <tr id="row${i}">
                    <td>
                        <select class="form-control select2" name="item[${i}][id_item]" id="id_item${i}">
                            <option></option>
                            @foreach ($item as $itm) <option data-harga="{{ $itm->harga }}" value="{{ $itm->id }}">{{ $itm->nama }}</option> @endforeach
                        </select>
                    </td>
                    <td>
                        <input readonly type="text" name="item[${i}][unit_price]" id="unit_price${i}" class="form-control text-right rupiah">
                    </td>
                    <td>
                        <textarea name="item[${i}][explantation]" id="explantation${i}" rows="1" class="form-control"></textarea>
                    </td>
                    <td>
                        <input readonly type="text" id="total_rupiah${i}" class="form-control text-right rupiah">
                        <input readonly type="hidden" name="item[${i}][total]" id="total${i}" class="form-control hitung text-right">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-remove">X</button>
                    </td>
                </tr>`;
                $('#tambah_form').append(html);

                $(`#id_item${i}`).select2({
                    placeholder: "-- Pilih --",
                    allowClear: true,
                    width: '100%',
                    tags: "true",
                });

                $(`#id_item${i}`).on('select2:select', function (e) {
                    const id = this.id.replace('id_item','');
                    const harga = e.params.data.element.dataset.harga;
                    $(`#unit_price${id}`).val(harga);
                    $(`#total_rupiah${id}`).val(formatRupiah(harga));
                    $(`#total${id}`).val(harga);
                    hitungTotal();
                });

                $('#tambah_form').click( e => {
                    if (e.target.classList.contains('btn-remove')) {
                        $(e.target).parent().parent().remove();
                        hitungTotal();
                    }
                });

            }); //end add event


        });
    </script>
@endpush
