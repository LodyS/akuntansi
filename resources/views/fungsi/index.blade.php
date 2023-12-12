@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Master Group</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('fungsi.create') }}')" >Tambah</a>
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
       @include('flash-message')
         <table class="table table-hover dataTable table-striped w-full" id="fungsi-table">
           <thead>
               <tr>
                 <th>No</th>
                  <th>Nama Fungsi</th>
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
      document.location = '{{ url("fungsi?status=trash") }}';
    } else {
      document.location = '{{ url("fungsi") }}';
    }
  });
    $('#fungsi-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
        ajax : {
                  url:"{{ url('fungsi/load-data') }}",
                data: function (d) {

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                                      { data: 'nama_fungsi', name: 'nama_fungsi' },
                      { data: 'status_aktif', name: 'status_aktif', searchable:false, orderable:false , "render":function(data,type,row){
       
       if(data.status_aktif =="Y")
            {
      return '<button class="btn btn-success btn-xs" style="color:white; font-family:Arial" " title="Nonaktifkan User" onclick="con(\'' + data.id + '\',\'' + data.status_aktif + '\')">Aktif</a>';
            }
            else
            {
              return '<a class="btn btn-danger btn-xs" style="color:white;font-family:Arial"" title="Aktifkan User" onclick="con(\'' + data.id + '\',\'' + data.status_aktif + '\')">Tidak Aktif</a>';
            }
          } },
                   
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

function reload_table()
{
    fungsi.ajax.reload(null,false); //reload datatable ajax 
}

function con(id,status)
{
  if(status=='Y')
  {
    var con=confirm("Apakah anda yakin untuk menonaktifkan group ini?");
    if(con==true)
  {
    window.location.href = "{{ url('fungsi/deactivate/')}}/"+id+"";
  } 
  else
  {
    return false;
  }
  }
  else
  {
    var con1=confirm(" Anda yakin akan mengaktifkan group ini?");
    if(con1==true)
  {
     window.location.href = "{{ url('fungsi/activate/')}}/"+id+"";
  } 
  else
  {
    return false;
  }
  }
  
}
</script>
@endpush
