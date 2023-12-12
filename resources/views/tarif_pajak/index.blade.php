@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Tarif Pajak</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('tarif-pajak.create') }}')" >Tambah</a>
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
         <table class="table table-hover dataTable table-striped w-full" id="tarif-pajak-table">
           <thead>
               <tr>
                 <th>No</th>
                                    <th>ID Pajak</th>
                                    <th>Nama</th>
                                    <th>Persentase (%)</th>
                                    <th>Rekening</th>
                                    <th>Status Aktif</th>
                       <th>Aksi</th>
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
      document.location = '{{ url("tarif-pajak?status=trash") }}';
    } else {
      document.location = '{{ url("tarif-pajak") }}';
    }
  });
    $('#tarif-pajak-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
    searching : false,
        ajax : {
                  url:"{{ url('tarif-pajak/load-data') }}",
                data: function (d) {

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                      { data : 'id', name: 'id'},
                      { data: 'nama_pajak', name: 'nama_pajak' },
                      { data: 'persentase_pajak', name: 'persentase_pajak' },
                      { data: 'rekening', name: 'rekening' },
                      { data: 'status_aktif', name: 'status_aktif', searchable:false,orderable:false , "render":function(data,type,row){

       if(data.status_aktif =='Y')
            {
      return '<a class="btn btn-success btn-xs" href="#" style="color:white; font-family:Arial" title="Nonaktifkan User" onclick="con(\'' + data.id + '\',\'' + data.status_aktif + '\')">Aktif</a>';
            }
            else
            {
              return '<a class="btn btn-danger btn-xs" href="#" style="color:white;font-family:Arial" title="Aktifkan User" onclick="con(\'' + data.id + '\',\'' + data.status_aktif + '\')">Tidak Aktif</a>';
            }
          }
                      },
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
        //sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
        //buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });
});

function con(id,status)
{
  if(status=='Y')
  {
    var con=confirm("Apakah anda yakin untuk menonaktifkan tarif pajak ini?");
    if(con==true)
  {
    window.location.href = "{{ url('tarif-pajak/deactivate/')}}/"+id+"";
  }
  else
  {
    return false;
  }
  }
  else
  {
    var con1=confirm(" Anda yakin akan mengaktifkan tarif pajak ini?");
    if(con1==true)
  {
     window.location.href = "{{ url('tarif-pajak/activate/')}}/"+id+"";
  }
  else
  {
    return false;
  }
  }

}
</script>
@endpush
