@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Set Surplus Defisit</h1>
    @include('layouts.inc.breadcrumb')
        <div class="page-header-actions">
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
            <table class="table table-hover dataTable table-striped w-full" id="set-surplus-defisit-detail-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Induk</th>
                        <th>Jenis</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
       </div>
    </div>
 </div>
@endsection

@push('js')
<script type="text/javascript">
$(function() {
    $('.trash-ck').click(function(){
        if ($('.trash-ck').prop('checked')) {
            document.location = '{{ url("set-surplus-defisit-detail?status=trash") }}';
        } else {
            document.location = '{{ url("set-surplus-defisit-detail") }}';
        }
    });
    
    $('#set-surplus-defisit-detail-table').DataTable({
        stateSave: true,
        processing : true,
        serverSide : true,
        pageLength:20,
        searching:false,
            ajax : {
                url:"{{ url('set-surplus-defisit-detail/load-data') }}",
                data: function (d) {

                }
            },
        
            columns: [
                { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                //{ data: 'komponen_surplus_deposit', name: 'komponen_surplus_deposit' },
                { data: 'nama', name: 'nama' },
                { data: 'induk', name: 'induk' },
                { data: 'jenis', name: 'jenis' },
                { data: 'level',  name: 'level', orderable: false, searchable: false, "render":function(data,type,row)
                {
                    if(data.level == '0')
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
