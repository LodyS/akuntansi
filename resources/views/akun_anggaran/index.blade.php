@extends('layouts.app')

@section('content')
   
<div class="page-header">
    <h1 class="page-title">Akun Anggaran</h1>
        @include('layouts.inc.breadcrumb')
        <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('akun-anggaran.create') }}')" >Tambah</a>
    </div>
</div>

<div class="page-content">
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
            <div class="form-group">
        </div>
    </div>
</header>

        <div class="panel-body">
            @include('flash-message')
                <form name="formCari" action="" method="post">
                
                    <div class="form-group row">
			            <label class="col-md-3">Tipe Akun Anggaran</label>
				            <div class="col-md-7">
				                <select name="tipe" class="form-control">
                                <option value="">Pilih Tipe Akun Anggaran</option>
                                <option value="1">Header</option>
                                <option value="2">Detail</option>
                            </select>
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Nama</label>
				            <div class="col-md-7">
				            <input type="text" name="nama" class="form-control">
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Induk</label>
				            <div class="col-md-7">
				            <input type="text" name="induk" class="form-control">
			            </div>
		            </div>

                <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button>
                    <table class="table table-hover dataTable table-striped w-full" id="akun-anggaran-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Induk</th>
                                <th>Perkiraan</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    <div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
 </div>

@endsection

@push('js')
<script type="text/javascript">
$(function() {
$('.trash-ck').click(function(){
    if ($('.trash-ck').prop('checked')) {
      document.location = '{{ url("akun-anggaran?status=trash") }}';
    } else {
      document.location = '{{ url("akun-anggaran") }}';
    }
});
    
const table = $('#akun-anggaran-table').DataTable({
    stateSave: true,
    processing : true,
    serverSide : true,
    searching : false,
    pageLength:20,
    ajax : {
        url:"{{ url('akun-anggaran/load-data') }}",
        data: function (d) {
        const form = document.forms.namedItem("formCari");
            d.nama = form.nama.value;
            d.tipe = form.tipe.value;
            d.induk = form.induk.value;
        }
    },
        
    columns: [
        { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
        { data: 'kode', name: 'kode' },
        { data: 'nama', name: 'nama' },
        { data: 'tipe', name: 'tipe' },              
        { data: 'induk', name: 'induk' },
        { data: 'perkiraan', name: 'perkiraan' },
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
