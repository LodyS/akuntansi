@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pengeluaran Kas</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
         <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('pengeluaran-kas.create') }}')">Tambah</a>
    </div>
</div>

<div class="page-content">

    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">


            </div>
            </div>
        </header>

    <div class="panel-body">
    @include('flash-message')
        <form name="formCari" action="" method="post">

        <div class="form-group row">
            <label class="col-md-3">Tanggal Awal : </label>
                <div class="col-md-7">
                <input type="date" name="tanggal_awal" value="{{ old('tanggal') ?? date('Y-m-d')}}" class="form-control">
             </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Tanggal Akhir : </label>
                <div class="col-md-7">
                <input type="date" name="tanggal_akhir" value="{{ old('tanggal') ?? date('Y-m-d')}}" class="form-control">
            </div>
        </div>

        <div class="form-group row">
			<label class="col-md-3">Bank</label>
				<div class="col-md-7">
				    <select name="id_bank" class="form-control" required>
                    <option value="">Pilih Bank</option>
                    @foreach ($KasBank as $bang)
                    <option {{ old('id_kas_bank') == $bang->id ? 'selected' : '' }} value="{{ $bang->id }}">{{ $bang->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>

          
        <div class="form-group row">
			<label class="col-md-3">COA</label>
				<div class="col-md-7">
				    <select name="id_perkiraan" class="form-control" required>
                    <option value="">Pilih Perkiraan</option>
                    @foreach ($perkiraan as $kira)
                    <option {{ old('id_perkiraan') == $kira->id ? 'selected' : '' }} value="{{ $kira->id }}">{{ $kira->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>

            <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button>

            <table class="table table-hover dataTable table-striped w-full" id="pengeluaran-kas-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Keterangan</th>
                        <th>Pengeluaran</th>
                        <th>Pembayaran</th>
                        <th>Nominal</th>
                        <th>Status</th>
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
       /* $('.input-daterange input').each(function() {
            $(this).datepicker({
                format: "dd/mm/yyyy",
                todayBtn:"linked",
                autoclose: true,
            });
        }); */

        $('.trash-ck').click(function () {
            if ($('.trash-ck').prop('checked')) {
                document.location = '{{ url("pengeluaran-kas?status=trash") }}';
            } else {
                document.location = '{{ url("pengeluaran-kas") }}';
            }
        });
        var table = $('#pengeluaran-kas-table').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            pageLength: 25,
            searching:false,
            ajax: {
                url: "{{ url('pengeluaran-kas/load-data') }}",
                data: function ( d ) {
                    const form = document.forms.namedItem("formCari");
                    d.tanggal_awal = form.tanggal_awal.value;
                    d.tanggal_akhir = form.tanggal_akhir.value;
                    d.id_bank = form.id_bank.value;
                    d.id_perkiraan = form.id_perkiraan.value;
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
                { data: 'nominal', name: 'nominal' },
                // { data: 'nominal', name: 'nominal', render: $.fn.dataTable.render.number(",", ".", 2, 'Rp. ') },
                { data: 'status', name:'status'},
                { data: 'action', name: 'action', orderable: false, searchable: false },
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
             //sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
             dom : 'Bfrtip',
         buttons: ['copy', 'excelHtml5', 'csv', 'pdfHtml5', 'print'],
        });

        $('#submit').click(function(){
            table.ajax.reload();
        });
    });
</script>
@endpush
