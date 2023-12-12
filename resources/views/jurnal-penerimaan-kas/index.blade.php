@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Jurnal Penerimaan Kas</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">

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
            <h3 class="panel-title">Data Penerimaan Kas</h3>
            <div class="panel-actions panel-actions-keep">
                <button class="btn btn-primary" id="btnCreateJurnal">Buat Jurnal</button>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-hover dataTable table-striped w-full" id="pengeluaran-kas-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Keterangan</th>
                        <th>Pemasukan</th>
                        <th>Pembayaran</th>
                        <th>Nominal</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
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
                document.location = '{{ url("jurnal-penerimaan-kas?status=trash") }}';
            } else {
                document.location = '{{ url("jurnal-penerimaan-kas") }}';
            }
        });
        var table = $('#pengeluaran-kas-table').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            pageLength: 20,
            ajax: {
                url: "{{ url('jurnal-penerimaan-kas/load-data') }}",
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
                    data: 'tanggal', name: 'tanggal', type: 'date', render:
                    function (data, type, row) {
                        return data ? moment(data).format('DD/MM/YYYY') : '';
                    }
                },
                { data: 'kode', name: 'kode' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'perkiraan', name: 'perkiraan' },
                { data: 'kas_bank', name: 'kas_bank' },
                { data: 'nominal', name: 'nominal', render: $.fn.dataTable.render.number(".", ".", 0, 'Rp. '), className: 'text-right' },
                // { data: 'action', name: 'action', orderable: false, searchable: false },
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

        $('#btnCreateJurnal').click(function(){
            const tgl = $('#start_date').data('datepicker').getFormattedDate('yyyy-mm-dd');
            // alert(tgl);
            location.href = "{{ url('jurnal-penerimaan-kas/create-jurnal') }}/" + tgl;
        });
    });
</script>
@endpush
