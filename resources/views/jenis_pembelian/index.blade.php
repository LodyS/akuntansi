@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Jenis Pembelian</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('jenis-pembelian.create') }}')" >Tambah</a>
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
         <form name="formCari" action="" method="post">

                <div class="form-group row">
                    <label class="col-md-3">Nama Jenis Pembelian</label>
                      <div class="col-md-7">
                      <input type="text" name="nama" class="form-control">
                  </div>
                </div>
                <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button>

         <table class="table table-hover dataTable table-striped w-full" id="jenis-pembelian-table">
           <thead>
               <tr>
                 <th>No</th>
                                    <th>Nama</th>
                                    <th>Perkiraan Diskon</th>
                                    <th>Perkiraan Pajak</th>
                                    <th>Perkiraan Materai</th>
                                    <th>Perkiraan Pembelian</th>
                                    <th>Perkiraan Hutang</th>
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
      document.location = '{{ url("jenis-pembelian?status=trash") }}';
    } else {
      document.location = '{{ url("jenis-pembelian") }}';
    }
  });
    const table = $('#jenis-pembelian-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    searching : false,
    pageLength:20,
        ajax : {
                  url:"{{ url('jenis-pembelian/load-data') }}",
                data: function (d) {
                  const form = document.forms.namedItem("formCari");
                  d.nama = form.nama.value;
                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                      { data: 'nama', name: 'nama' },
                      { data: 'diskon', name: 'diskon.nama' },
                      { data: 'pajak', name: 'pajak.nama' },
                      { data: 'materai', name: 'materai.nama' },
                      { data: 'pembelian', name: 'pembelian.nama' },
                      { data: 'hutang', name: 'hutang.nama' },
                      //{ data: 'created_at', name: 'created_at' },
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
    $('#submit').click(function(){
        table.ajax.reload();
    });
});
</script>
@endpush
