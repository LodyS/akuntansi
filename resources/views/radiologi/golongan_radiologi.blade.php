@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Golongan Radiologi</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        <a class="btn btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('radiologi.create_golongan_radiologi') }}')">Golongan Radiologi</a>
        <a class="btn btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('radiologi.create_jenis_radiologi') }}')">Jenis Radiologi</a>
        <a class="btn btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('radiologi.create') }}')">Tambah Radiologi</a>
    </div>
</div>
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <div class="form-group row ml-10">
                <div class="col-md-2">
                    <input class="form-check-input" type="radio" name="radiologi" id="radiologi" value="radiologi" onclick="window.location='{{ route("radiologi.index") }}'">
                    <label class="form-check-label" for="radiologi">
                        Radiologi
                    </label>
                </div>
                <div class="col-md-2">
                    <input class="form-check-input" type="radio" name="jenis_radiologi" id="jenis_radiologi"
                        value="jenis_radiologi" onclick="window.location='{{ route("radiologi.jenis_radiologi") }}'">
                    <label class="form-check-label" for="jenis_radiologi">
                        Jenis Radiologi
                    </label>
                </div>
                <div class="col-md-2">
                    <input class="form-check-input" type="radio" name="golongan_radiologi" id="golongan_radiologi"
                        value="golongan_radiologi" checked onclick="window.location='{{ route("radiologi.golongan_radiologi") }}'">
                    <label class="form-check-label" for="golongan_radiologi">
                        Golongan Radiologi
                    </label>
                </div>
            </div>
            <table class="table table-hover dataTable table-striped w-full" id="radiologi-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Golongan Radiologi</th>
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
        $('#radiologi-table').DataTable({
            stateSave: true,
            processing : true,
            serverSide : true,
            pageLength:20,
                ajax : {
                    url:"{{ url('radiologi/load-data-golongan-radiologi') }}",
                    data: function (d) {

                    }
            },
            columns: [
                { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                { data: 'nama', name: 'nama' },
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
    });
</script>
@endpush
