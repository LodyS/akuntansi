@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Laboratorium</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('laboratorium.create') }}')">Tambah</a>
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
        <form name="formCari" action="" method="post">
        <div class="form-group row">
            <label class="col-md-3">Nama</label>
                <div class="col-md-7">
                <input type="text" name="nama" class="form-control">
            </div>
        </div>

        <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button><br/><br/>

            <table class="table table-hover dataTable table-striped w-full" id="laboratorium-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Layanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>

@endsection

@push('js')
<script type="text/javascript">
    $(function() {
        const table = $('#laboratorium-table').DataTable({
            stateSave: true,
            processing : true,
            serverSide : true,
            pageLength:20,
            searching:false,
                ajax : {
                    url:"{{ url('laboratorium/load-data') }}",
                    data: function (d) {
                        const form = document.forms.namedItem("formCari");
                            d.nama = form.nama.value;
                    }
            },
            columns: [
                { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                { data: 'layanan', name: 'layanan' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                lengthMenu : '{{ "_MENU_" }}',
                zeroRecords : '{{ "Data tidak ditemukan" }}' ,
                info : '{{ "_PAGE_ dari _PAGES_ halaman" }}',
                infoEmpty : '{{ "Data tidak ditemukan" }}',
                infoFiltered : '{{ "(Penyaringan dari _MAX_ data)" }}',
                loadingRecords : '{{ "Memuat data dari server" }}' ,
                processing :    '{{ "Memuat data data" }}',
                search :        '{{ "Pencarian:" }}',
                paginate : {
                    first :     '{{ "<" }}' ,
                    last :      '{{ ">" }}' ,
                    next :      '{{ ">>" }}',
                    previous :  '{{ "<<" }}'
                }
            },
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [-1]
            }],
            iDisplayLength: 5,
            aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        });
        $('#submit').click(function(){
        table.ajax.reload();
    });
    });
</script>
@endpush
