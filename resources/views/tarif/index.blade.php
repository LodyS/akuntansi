@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tarif</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('tarif.create') }}')">Tambah</a>
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
            <table class="table table-hover dataTable table-striped w-full" id="tarif-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Layanan</th>
                        <th>Kelas</th>
                        <th>Jasa Sarana</th>
                        <th>BHP</th>
                        <th>Total Utama</th>
                        <th>Persen Nakes Utama</th>
                        <th>Persen RS Utama</th>
                        <th>Pendamping</th>
                        <th>Persen Nakes Pendamping</th>
                        <th>Persen RS Pendamping</th>
                        <th>Pendukung</th>
                        <th>Persen Nakes Pendukung</th>
                        <th>Persen RS Pendukung</th>
                        <th>Alkes</th>
                        <th>KR</th>
                        <th>Ulup</th>
                        <th>Adm</th>
                        <th>Total</th>
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
        $('#tarif-table').DataTable({
            stateSave: true,
            processing : true,
            serverSide : true,
            scrollX: true,
            pageLength:20,
            ajax : {
                    url:"{{ url('tarif/load-data') }}",
            },
            columns: [
                { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                { data : 'layanan.nama', name: 'layanan.nama'},
                { data : 'kelas.nama', name: 'kelas.nama'},
                {
                    data : 'jasa_sarana',
                    name: 'jasa_sarana',
                    render: function (data) {
                        return 'Rp '+ data;
                    }
                },
                {
                    data : 'bhp',
                    name: 'bhp',
                    render: function (data) {
                        return 'Rp '+ data;
                    },
                },
                {
                    data : 'total_utama',
                    name: 'total_utama',
                    render: function (data) {
                        return 'Rp '+ data;
                    },
                },
                { data : 'persen_nakes_utama', name: 'persen_nakes_utama'},
                { data : 'persen_rs_utama', name: 'persen_rs_utama'},
                { data : 'total_pendamping', name: 'total_pendamping'},
                { data : 'persen_nakes_pendamping', name: 'persen_nakes_pendamping'},
                { data : 'persen_rs_pendamping', name: 'persen_rs_pendamping'},
                { data : 'total_pendukung', name: 'total_pendukung'},
                { data : 'persen_nakes_pendukung', name: 'persen_nakes_pendukung'},
                { data : 'persen_nakes_pendukung', name: 'persen_nakes_pendukung'},
                { data : 'alkes', name: 'alkes'},
                { data : 'kr', name: 'kr'},
                { data : 'ulup', name: 'ulup'},
                { data : 'adm', name: 'adm'},
                {
                    data : 'total',
                    name: 'total',
                    render: function (data) {
                        return 'Rp '+ data;
                    },
                }
            ],
            language: {
                lengthMenu : '{{ "Menampilkan _MENU_ data" }}',
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
