@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Set Lap Ekuitas</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
    <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('set-lap-ekuita.create') }}')" >Tambah</a>
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
            <table class="table table-hover dataTable table-striped w-full" id="set-lap-ekuita-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
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
        document.location = '{{ url("set-lap-ekuita?status=trash") }}';
    } else {
        document.location = '{{ url("set-lap-ekuita") }}';
    }
});

    $('#set-lap-ekuita-table').DataTable({
        stateSave: true,
        processing : true,
        serverSide : true,
        pageLength:20,
            ajax : {
                url:"{{ url('set-lap-ekuita/load-data') }}",
                data: function (d) {
            }
        },
        
        columns: [
            { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
            { data: 'kode', name: 'kode' },
            { data: 'nama', name: 'nama' },
            { data: 'induk', name: 'induk' },         
            { data: 'level', name: 'level' },
            { data: 'jenis', name: 'jenis' },  
            { data: 'jenis_data',  name: 'jenis_data', orderable: false, searchable: false, "render":function(data,type,row)
                {
                    if(data.jenis_data == 'Y')
                    {
                        return '';
                    } else {
                        return row.action;
                    }
                }
            },
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
