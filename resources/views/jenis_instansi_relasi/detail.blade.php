@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Detail Jenis Supplier ({{$jenis_instansi->nama}})</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">

     </div>
   </div>
   <div class="page-content">
     <!-- Panel Table Tools -->
     <div class="panel">
       <header class="panel-heading">
         <div class="form-group col-md-12">
           <div class="form-group">
                {{-- <h3 class="panel-title">Jenis Supplier : </h3> --}}
           </div>
         </div>
       </header>
       <div class="panel-body">
         <table class="table table-hover dataTable table-striped w-full" id="jenis-instansi-relasi-table">
           <thead>
               <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Alamat</th>
               </tr>
           </thead>
           <tbody>
               @foreach ($instansi_relasi as $key => $item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$item->kode}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->alamat}}</td>
                    </tr>
               @endforeach
           </tbody>
         </table>
       </div>
     </div>
     <!-- End Panel Table Tools -->
 </div>

@endsection

@push('js')
<script type="text/javascript">
$(function() {

    $('#jenis-instansi-relasi-table').DataTable({
      stateSave: true,
    processing : false,
    serverSide : false,
    pageLength:20,
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
        iDisplayLength: 5,
        aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        // sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
        // buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });
});
</script>
@endpush
