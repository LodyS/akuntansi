@extends('layouts.app')

@section('content')

<style>
table 
{
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
}

th, td 
{
    text-align: left;
    padding: 8px;
}

tr:nth-child(even)
{
    background-color: #f2f2f2
}
</style>

<div class="page-header">
    <h1 class="page-title">Kas Bank</h1>
        @include('layouts.inc.breadcrumb')
        <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('kas-bank.create') }}')" >Tambah</a>
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
                    <div style="overflow-x:auto;">
                        <table class="table table-hover dataTable table-striped w-full" id="kas-bank-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Bank</th>
                                    <th>Nama</th>
                                    <th>Rekening</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
      document.location = '{{ url("kas-bank?status=trash") }}';
    } else {
      document.location = '{{ url("kas-bank") }}';
    }
});
    
$('#kas-bank-table').DataTable({
    stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
    ajax : {
        url:"{{ url('kas-bank/load-data') }}",
        data: function (d) {
    }
},
    
    columns: [
        { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
        { data: 'kode_bank', name: 'kode_bank' },
        { data: 'nama', name: 'nama' },
        { data: 'rekening', name: 'rekening' },           
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
