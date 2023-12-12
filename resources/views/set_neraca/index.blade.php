@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Set Neraca</h1>
    @include('layouts.inc.breadcrumb')
        <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('set-neraca.create') }}')" >Tambah</a>
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
            <table class="table table-hover dataTable table-striped w-full" id="set-neraca-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Jenis Neraca</th>
                        <th>Nama</th>
                        <th>Induk</th>
                        <th>Level</th>
                        <th>Jenis</th>
                        <th>Action</th>
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
    $('.trash-ck').click(function(){
        if ($('.trash-ck').prop('checked')) {
            document.location = '{{ url("set-neraca?status=trash") }}';
        } else {
            document.location = '{{ url("set-neraca") }}';
        }
    });

    $('#set-neraca-table').DataTable({
        stateSave: true,
        processing : true,
        serverSide : true,
        pageLength:20,
            ajax : {
                url:"{{ url('set-neraca/load-data') }}",
                data: function (d) {
                }
            },
        columns: [
            { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
            { data: 'kode', name: 'kode' },
            { data: 'jenis_neraca', name: 'jenis_neraca' },
            { data: 'nama', name: 'nama' },
            { data: 'induk', name: 'induk' },
            { data: 'level', name: 'level' },
            { data: 'jenis', name: 'jenis' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        language: {
            lengthMenu     : '{{ "Menampilkan _MENU_ data" }}',
            zeroRecords    : '{{ "Data tidak ditemukan" }}' ,
            info           : '{{ "_PAGE_ dari _PAGES_ halaman" }}',
            infoEmpty      : '{{ "Data tidak ditemukan" }}',
            infoFiltered   : '{{ "(Penyaringan dari _MAX_ data)" }}',
            loadingRecords : '{{ "Memuat data dari server" }}' ,
            processing     : '{{ "Memuat data data" }}',
            search         : '{{ "Pencarian:" }}',
            paginate       : 
            {
                first      : '{{ "<" }}',
                last       : '{{ ">" }}',
                next       : '{{ ">>" }}',
                previous   : '{{ "<<" }}'
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
});
</script>
@endpush
