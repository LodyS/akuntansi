@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Surplus Defisit Detail</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('surplus-defisit-detail.create') }}')" >Tambah</a>
     </div>
   </div>
   <div class="page-content">
     <!-- Panel Table Tools -->
     <div class="panel">
       <header class="panel-heading">
         <div class="form-group col-md-12">
           <div class="form-group">
         <!-- <h3 class="panel-title">Table Tools</h3> -->

           </div>
         </div>
       </header>
       <div class="panel-body">
         <!-- <table class="table table-bordered" id="users-table">

         </table> -->
         <table class="table table-hover dataTable table-striped w-full" id="surplus-defisit-detail-table">
           <thead>
               <tr>
                 <th>No</th>
                                           <th>Surplus Defisit</th>
                                    <th>nama</th>
                                          <th>list code rekening</th>
                                    <th>list code unit</th>
                                    <th>type</th>
                                    <th>urutan</th>

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
$(function() {
  $('.trash-ck').click(function(){
    if ($('.trash-ck').prop('checked')) {
      document.location = '{{ url("surplus-defisit-detail?status=trash") }}';
    } else {
      document.location = '{{ url("surplus-defisit-detail") }}';
    }
  });
    $('#surplus-defisit-detail-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
        ajax : {
                  url:"{{ url('surplus-defisit-detail/load-data') }}",
                data: function (d) {

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                                      { data: 'surplus_defisit', name: 'surplus_defisit' },
                      { data: 'nama', name: 'nama' },
                            { data: 'list_code_rekening', name: 'list_code_rekening' },
                      { data: 'list_code_unit', name: 'list_code_unit' },
                      { data: 'type', name: 'type' },
                      { data: 'urutan', name: 'urutan' },

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
