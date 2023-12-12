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
     <h1 class="page-title">Perusahaan</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('perusahaan.create') }}')" >Tambah</a>
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
                    <label class="col-md-3">Nama</label>
                      <div class="col-md-7">
                      <input type="text" name="nama" class="form-control">
                  </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Kode Unit Usaha</label>
                      <div class="col-md-7">
                      <input type="text" name="kode_unit_usaha" class="form-control">
                  </div>
                </div>

                <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button><br/><br/>
         <div style="overflow-x:auto;">
         <table class="table table-hover dataTable table-striped w-full" id="perusahaan-table">
           <thead>
               <tr>
                 <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama </th>
                                    <th>Unit Usaha</th>
                                    <th>Kode Usaha</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Negara</th>
                                    <th>Kode Pos</th>
                                    <th>Telepon</th>
                                    <th>Fax</th>
                                    <th>Email</th>
                                    <th>NPWP</th>
                       <th>Aksi</th>
               </tr>
           </thead>
         </table>
       </div>
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
      document.location = '{{ url("perusahaan?status=trash") }}';
    } else {
      document.location = '{{ url("perusahaan") }}';
    }
  });
    const table = $('#perusahaan-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    searching : false,
    pageLength:20,
        ajax : {
                  url:"{{ url('perusahaan/load-data') }}",
                data: function (d) {
                  const form = document.forms.namedItem("formCari");
                  d.nama = form.nama.value;
                  d.kode_unit_usaha = form.kode_unit_usaha.value;
                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                      { data:'kode', name:'kode'},
                      { data: 'nama_badan_usaha', name: 'nama_badan_usaha' },
                      { data: 'unit_usaha', name: 'unit_usaha' },
                      { data: 'kode_unit_usaha', name: 'kode_unit_usaha' },
                      { data: 'alamat_perusahaan', name: 'alamat_perusahaan' },
                      { data: 'kota', name: 'kota' },
                      { data: 'negara_perusahaan', name: 'negara_perusahaan' },
                      { data: 'kode_pos', name: 'kode_pos' },
                      { data: 'telepon_perusahaan', name: 'telepon_perusahaan' },
                      { data: 'fax_perusahaan', name: 'fax_perusahaan' },
                      { data: 'email_perusahaan', name: 'email_perusahaan' },
                      { data: 'npwp', name: 'npwp' },
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
        sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
        buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });
    $('#submit').click(function(){
        table.ajax.reload();
    });
});
</script>
@endpush
