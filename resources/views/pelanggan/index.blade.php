@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Pelanggan</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('pelanggan.create') }}')" >Tambah</a>
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


         <table class="table table-hover dataTable table-striped w-full" id="pelanggan-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Keterangan</th>

                    <th>Termin</th>
                    <th>Saldo Piutang</th>
                    <th>Batas Kredit</th>
                    <th>Rekening Kontrol</th>
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
      document.location = '{{ url("pelanggan?status=trash") }}';
    } else {
      document.location = '{{ url("pelanggan") }}';
    }
  });
    const table = $('#pelanggan-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
    searching:false,
        ajax : {
                  url:"{{ url('pelanggan/load-data') }}",
                data: function (d) {
                  const form = document.forms.namedItem("formCari");
                    d.nama = form.nama.value;
                    d.kode = form.kode.value;
                }
      },
        columns: [
        { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
        { data: 'nama', name: 'nama' },
        { data: 'keterangan', name: 'keterangan' },
        { data: 'termin', name: 'termin' },
        /*{ data: 'saldo_piutang', name: 'saldo_piutang', render: function(data, type,row, meta){
                return meta.settings.fnFormatNumber(row.saldo_piutang);
            }
        },*/
        { data: 'saldo_piutang', name: 'saldo_piutang' },
        { data: 'batas_kredit', name: 'batas_kredit', render: function(data, type,row, meta){
                return meta.settings.fnFormatNumber(row.batas_kredit);
            }
        },
        { data: 'rekening_kontrol', name: 'rekening_kontrol' },
        { data: 'flag_aktif', name: 'flag_aktif', searchable:false,orderable:false , "render":function(data,type,row){

            if(data.flag_aktif =='Y') {
                return '<a class="btn btn-success btn-xs" href="#" style="color:white; font-family:Arial" title="Nonaktifkan Pelanggan" onclick="con(\'' + data.id + '\',\'' + data.flag_aktif + '\') ">Aktif</a>';
            }
            else {
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
         sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
         buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });

    $('#submit').click(function(){
        table.ajax.reload();
    });
});

function con(id,flag_aktif)
{
  if(flag_aktif=='Y')
  {
    var con=confirm("Apakah anda yakin untuk menonaktifkan Pelanggan ini?");
    if(con==true)
  {
    window.location.href = "{{ url('pelanggan/deactivate/')}}/"+id+"";
  }
  else
  {
    return false;
  }
  }
  else
  {
    var con1=confirm(" Anda yakin akan mengaktifkan Pelanggan ini?");
    if(con1==true)
  {
     window.location.href = "{{ url('pelanggan/activate/')}}/"+id+"";
  }
  else
  {
    return false;
  }
  }

}
</script>
@endpush
