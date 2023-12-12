@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Jurnal Invoice</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        {{-- <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('invoice.create') }}')">Tambah</a> --}}
    </div>
</div>
<div class="page-content">

    {{-- panel form filter pencarian --}}
    <div class="row">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal</label>
                        <div class="col-md">

                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" name="start_date" id="start_date" value="{{ date('d/m/Y') }}">
                                {{-- <div class="input-group-addon">s/d</div>
                                <input type="text" class="form-control" name="end_date" id="end_date" value="{{ date('d/m/Y') }}"> --}}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button type="button" class="btn btn-primary" id="cari">Cari</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Table Tools -->
    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">Data Invoice</h3>
        </div>
        <div class="panel-body">
            <table class="table table-hover dataTable table-striped w-full" id="invoice-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Nominal</th>
                        <th>PPN</th>
                        <th>Aksi</th>
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
        $('.input-daterange input').each(function() {
            $(this).datepicker({
                format: "dd/mm/yyyy",
                todayBtn:"linked",
                autoclose: true,
            });
        });

        $('.trash-ck').click(function () {
            if ($('.trash-ck').prop('checked')) {
                document.location = '{{ url("jurnal-invoice?status=trash") }}';
            } else {
                document.location = '{{ url("jurnal-invoice") }}';
            }
        });
        var table = $('#invoice-table').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            pageLength: 20,
            ajax: {
                url: "{{ url('jurnal-invoice/load-data') }}",
                data: function ( d ) {
                    return $.extend( {}, d, {
                        "startDate": $('#start_date').val(),
                        // "endDate": $('#end_date').val(),
                    } );
                }

            },
            columns: [
                { data: 'nomor', name: 'nomor', searchable: false, orderable: false },
                {
                    data: 'invoice_date', name: 'invoice_date', type: 'date', render:
                    function (data, type, row) {
                        return data ? moment(data).format('DD/MM/YYYY') : '';
                    }
                },
                { data: 'number', name: 'number' },
                { data: 'nama', name: 'pelanggan.nama' },
                { data: 'subtotal', name: 'subtotal', render: $.fn.dataTable.render.number(".", ".", 0, 'Rp. '), className: 'text-right' },
                { data: 'ppn', name: 'ppn', render: $.fn.dataTable.render.number(".", ".", 0, 'Rp. '), className: 'text-right' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
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
            aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            // sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
            // buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
        });

        $('#cari').click(function(){
            table.ajax.reload();
        });
    });
</script>
@endpush
