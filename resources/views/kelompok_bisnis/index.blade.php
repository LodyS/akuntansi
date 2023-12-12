@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Kelompok Bisnis</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('kelompok-bisnis.create') }}')" >Tambah</a>
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
         <table class="table table-hover dataTable table-striped w-full" id="kelompok-bisnis-table">
           <thead>
               <tr>
                 <th>No</th>
                                           <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Flag Aktif</th>
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
      document.location = '{{ url("kelompok-bisnis?status=trash") }}';
    } else {
      document.location = '{{ url("kelompok-bisnis") }}';
    }
  });
    $('#kelompok-bisnis-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
        ajax : {
                  url:"{{ url('kelompok-bisnis/load-data') }}",
                data: function (d) {

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                                      { data: 'kode', name: 'kode' },
                      { data: 'nama', name: 'nama' },
                       { data: 'flag_aktif', name: 'flag_aktif', searchable:false,orderable:false , "render":function(data,type,row){
       
       if(data.flag_aktif =='Y')
            {
      return '<a class="btn btn-success btn-xs" href="#" style="color:white; font-family:Arial" title="Nonaktifkan Pelanggan" onclick="con(\'' + data.id + '\',\'' + data.flag_aktif + '\') ">Aktif</a>';
            }
            else
            {
              return '<a class="btn btn-danger btn-xs" href="#" style="color:white;font-family:Arial" title="Aktifkan Pelanggan" onclick="con(\'' + data.id + '\',\'' + data.flag_aktif + '\')">Tidak Aktif</a>';
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
        // sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
        // buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });
});

function con(id,flag_aktif)
{
  if(flag_aktif=='Y')
  {
    var con=confirm("Apakah anda yakin untuk menonaktifkan Kelompok Bisnis ini?");
    if(con==true)
  {
    window.location.href = "{{ url('kelompok-bisnis/deactivate/')}}/"+id+"";
  } 
  else
  {
    return false;
  }
  }
  else
  {
    var con1=confirm(" Anda yakin akan mengaktifkan Kelompok Bisnis ini?");
    if(con1==true)
  {
     window.location.href = "{{ url('kelompok-bisnis/activate/')}}/"+id+"";
  } 
  else
  {
    return false;
  }
  }
  
}
</script>
@endpush
