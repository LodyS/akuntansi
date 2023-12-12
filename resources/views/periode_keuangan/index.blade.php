@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Periode Keuangan</h1>
     
      
		Tanggal Setup : {{ isset($setup->tanggal_setup) ? $setup->tanggal_setup : 'Tidak ada data' }} <br/>
		Tanggal Transaksi : {{ isset ($setup->tanggal_pertama) ? $setup->tanggal_pertaman : 'Tidak ada data'}}<br/>
		

      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('periode-keuangan.create') }}')" >Tambah</a>
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
         <!-- <table class="table table-bordered" id="users-table">-->

<form action="{{ url('periode-keuangan/pencarian-tahun') }}" method="POST">{{ @csrf_field() }} 
    <div class="form-group row">
		  <label class="col-md-1">Tahun</label>
			  <div class="col-md-3">
			    <select name="tahun" id="tahun" class="form-control select" required>
          <option value="">Pilih</option>
          <option value="">Pilih Semua Tahun</option>
          @for($i=2020; $i<2050; $i++)
          <option value="{{ $i }}">{{ $i}} </option>
          @endfor
        </select> 
		  </div>
	  </div>

    <button type="submit" id="submit" align="right" class="btn btn-primary">Cari</button><br/><br/>

         <!--</table> -->
         <table class="table table-hover dataTable table-striped w-full" id="periode-keuangan-table">
           <thead>
               <tr>
                 <th>No</th>
                  <th>Bulan</th>
                  <th>Tahun</th>
                  <th>Tanggal Awal</th>
                  <th>Tanggal Akhir</th>
                  <th>Status</th>
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
      document.location = '{{ url("periode-keuangan?status=trash") }}';
    } else {
      document.location = '{{ url("periode-keuangan") }}';
    }
  });
    $('#periode-keuangan-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    pageLength:20,
    searching:false,

        ajax : {
                  url:"{{ url('periode-keuangan/load-data') }}",
                data: function (d) {

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                      { data:'bulan', name: 'bulan'},
                      { data: 'tahun', name: 'tahun' },
                      { data: 'tanggal_awal', name: 'tanggal_awal' },
                      { data: 'tanggal_akhir', name: 'tanggal_akhir' },
                      { data: 'status_aktif', name: 'status_aktif', searchable:false, orderable:false , "render":function(data,type,row){
       
       if(data.status_aktif =="Y")
            {
      return '<a class="btn btn-success btn-xs" style="color:white; font-family:Arial" ">Aktif</a>';
            }
            else
            {
              return '<a class="btn btn-danger btn-xs" style="color:white;font-family:Arial"">Tidak Aktif</a>';
            }
          }      },
                    
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
