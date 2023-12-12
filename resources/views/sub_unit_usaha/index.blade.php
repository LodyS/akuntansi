@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Sub Unit Usaha</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('sub-unit-usaha.create') }}')" >Tambah</a>
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
            <label class="col-md-3">Kode</label>
              <div class="col-md-7">
              <input type="text" name="kode" class="form-control">
            </div>
          </div>

          <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button><br/><br/>
         <table class="table table-hover dataTable table-striped w-full" id="sub-unit-usaha-table">
           <thead>
               <tr>
                 <th>No</th>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Unit Usaha</th>
                              
                    <th>Flag Aktif</th>
                       <th>Edit</th>
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
      document.location = '{{ url("sub-unit-usaha?status=trash") }}';
    } else {
      document.location = '{{ url("sub-unit-usaha") }}';
    }
  });
    const table = $('#sub-unit-usaha-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    searching : false,
    pageLength:20,
        ajax : {
                  url:"{{ url('sub-unit-usaha/load-data') }}",
                data: function (d) {
                  const form = document.forms.namedItem("formCari");
                            d.nama = form.nama.value;
                            d.kode = form.kode.value;
                }
                
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                                      { data: 'kode', name: 'kode' },
                                      { data: 'nama', name: 'nama' },
                      { data: 'unit_usaha', name: 'unit_usaha' },
                    
                      { data: 'flag_aktif', name: 'flag_aktif', searchable:false,orderable:false , "render":function(data,type,row){
       
       if(data.flag_aktif =='Y')
            {
      return '<a class="btn btn-success btn-xs" href="#" style="color:white; font-family:Arial" title="Nonaktifkan Jenis Usaha" onclick="con(\'' + data.id + '\',\'' + data.flag_aktif + '\') ">Aktif</a>';
            }
            else
            {
              return '<a class="btn btn-danger btn-xs" href="#" style="color:white;font-family:Arial" title="Aktifkan Jenis Usaha" onclick="con(\'' + data.id + '\',\'' + data.flag_aktif + '\')">Tidak Aktif</a>';
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
    $('#submit').click(function(){
        table.ajax.reload();
    });
});

function con(id,flag_aktif)
{
  if(flag_aktif=='Y')
  {
    var con=confirm("Apakah anda yakin untuk menonaktifkan Sub Unit Usaha ini?");
    if(con==true)
  {
    window.location.href = "{{ url('sub-unit-usaha/deactivate/')}}/"+id+"";
  } 
  else
  {
    return false;
  }
  }
  else
  {
    var con1=confirm(" Anda yakin akan mengaktifkan Sub Unit Usaha ini?");
    if(con1==true)
  {
     window.location.href = "{{ url('sub-unit-usaha/activate/')}}/"+id+"";
  } 
  else
  {
    return false;
  }
  }
  
}
</script>
@endpush
