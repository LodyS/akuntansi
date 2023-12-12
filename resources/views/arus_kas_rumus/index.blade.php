@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Arus Kas Rumus</h1>
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
                </div>
            </div>
       </header>

        <div class="panel-body">

            <table class="table table-hover dataTable table-striped w-full" id="arus-ka-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Induk</th>
                        <th>Jenis</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($data as $key=>$d)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->induk }}</td>
                        <td>{{ $d->tipe }}</td>
                        <td><a href="{{ url('arus-kas-rumus/detail', $d->id)}}" class="btn btn-success round">Detail</a></td>
                    </tr>
                    @endforeach
                </thead>
            </table>
            {{ $data->appends(request()->toArray())->links() }}
        </div>
    </div>
 </div>
@endsection

@push('js')
<script type="text/javascript">
/*$(function() {
    $('.trash-ck').click(function(){
        if ($('.trash-ck').prop('checked')) {
            document.location = '{{ url("arus-kas-rumus?status=trash") }}';
        } else {
            document.location = '{{ url("arus-kas-rumus") }}';
        }
  });

    $('#arus-ka-table').DataTable({
        stateSave: true,
        processing : true,
        serverSide : true,
        pageLength:20,

            ajax : {
                url:"{{ url('arus-kas-rumus/load-data') }}",
                    data: function (d) {

                }
            },

        columns: [
            { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
            { data: 'nama', name: 'nama' },
            // { data: 'urutan', name: 'urutan' },
            { data: 'id_induk', name: 'id_induk' },
            { data: 'tipe', name: 'tipe' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
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
        // sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
        // buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });
});
</script>
@endpush
