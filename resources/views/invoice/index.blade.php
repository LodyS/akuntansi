@extends('layouts.app')

@section('content')
<style>
    .select2-container {
        z-index: 5 !important;
    }
</style>
<div class="page-header">
    <h1 class="page-title">Invoice</h1>
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
        <div class="panel-body">
            <div id="formCari">
                <div class="form-group row">
                    <label for="tgl_awal" class="col-md-auto control-label" style="width: 130px">Tanggal Awal</label>
                    <div class="col-md-4">
                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tgl_akhir" class="col-md-auto control-label" style="width: 130px">Tanggal Akhir</label>
                    <div class="col-md-4">
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pelanggan" class="col-md-auto control-label" style="width: 130px">Customer</label>
                    <div class="col-md-4">
                        <select name="customer" id="customer" class="form-control" style="width: 100%">
                            <option value="0">Semua Pelanggan</option>
                            @foreach ($pelanggan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-auto control-label" style="width: 130px"></label>
                    <div class="col-md-auto">
                        <a class="btn btn-block btn-primary" id="btnCari" href="#" onclick="">Cari</a>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-auto">
                        <a class="btn btn-block btn-primary" href="{{ route('invoice.create') }}">Tambah</a>
                    </div>
                </div>
            </div>


            <hr>
            <table class="table table-hover dataTable table-striped w-full" id="invoice-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Number</th>
                        <th>Invoice date</th>
                        <th>Due date</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>PPN</th>
                        <th>Grand Total</th>
                        <th>Detail</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- End Panel Table Tools -->
</div>
<div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>

@endsection

@push('js')
    <script type="text/javascript">
        $(function () {
            $('#customer').select2({
                placeholder: "Pilih Customer",
                allowClear: true,
                dropdownParent: $('#formCari')
            });

            $('.trash-ck').click(function () {
                if ($('.trash-ck').prop('checked')) {
                    document.location = '{{ url("invoice?status=trash") }}';
                } else {
                    document.location = '{{ url("invoice") }}';
                }
            });
            let table = $('#invoice-table').DataTable({
                stateSave: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: {
                    url: "{{ url('invoice/load-data') }}",
                    data: function (d) {
                        d.tgl_awal = $('#tgl_awal').val();
                        d.tgl_akhir = $('#tgl_akhir').val();
                        d.customer = $('#customer').val();
                    }
                },
                columns: [{
                        data: 'nomor',
                        name: 'nomor',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'invoice_date',
                        name: 'invoice_date'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        class: 'text-nowrap'
                    },
                    {
                        data: 'ppn',
                        name: 'ppn',
                        class: 'text-nowrap'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal',
                        class: 'text-nowrap'
                    },
                    {
                        data: 'null',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) { return `<a class="btn btn-sm btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('/invoice/${row.id}')" >Detail</a>`  }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-nowrap'
                    },
                ],
                language: {
                    lengthMenu: '{{ "Menampilkan _MENU_ data" }}',
                    zeroRecords: '{{ "Data tidak ditemukan" }}',
                    info: '{{ "_PAGE_ dari _PAGES_ halaman" }}',
                    infoEmpty: '{{ "Data tidak ditemukan" }}',
                    infoFiltered: '{{ "(Penyaringan dari _MAX_ data)" }}',
                    loadingRecords: '{{ "Memuat data dari server" }}',
                    processing: '{{ "Memuat data data" }}',
                    search: '{{ "Pencarian:" }}',
                    paginate: {
                        first: '{{ "<" }}',
                        last: '{{ ">" }}',
                        next: '{{ ">>" }}',
                        previous: '{{ "<<" }}'
                    }
                },
                aoColumnDefs: [{
                    bSortable: false,
                    aTargets: [-1]
                }],
                iDisplayLength: 5,
                aLengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                scrollX: true,
                // sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
                // buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            });

            $('#btnCari').click( e => {
                table.ajax.reload();
            });
        });
    </script>
@endpush
