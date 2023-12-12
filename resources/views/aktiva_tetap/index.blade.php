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
    <h1 class="page-title">Aktiva Tetap</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('aktiva-tetap.create') }}')" >Tambah</a>
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

<div style="overflow-x:auto;">
    <div class="panel-body">

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

        <table class="table table-hover dataTable table-striped w-full" id="aktiva-tetap-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kode</th>
                    <!--<th>Tanggal Pembelian</th>
                    <th>HP</th>
                    <th>Nilai Residu</th>
                    <th>Umur Eknomis</th>
                    <th>Sudah Didepresiasi</th>
                    <th>Akumulasi Penyusutan</th>
                    <th>Nilai Buku</th>-->
                    <th>Aksi</th>
               </tr>
            </thead>
        </table>
            </div>
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
        document.location = '{{ url("aktiva-tetap?status=trash") }}';
    } else {
        document.location = '{{ url("aktiva-tetap") }}';
    }
});

const table = $('#aktiva-tetap-table').DataTable({
    stateSave: true,
    processing : true,
    serverSide : true,
    searching : false,
    pageLength:20,
        ajax : {
                url:"{{ url('aktiva-tetap/load-data') }}",
                data: function (d) {
                    const form = document.forms.namedItem("formCari");
                    d.nama = form.nama.value;
                    d.kode = form.kode.value;
            }
      },
        columns: [
            { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
            { data: 'nama', name: 'nama' },
            { data: 'kode', name: 'kode' },
            /*{ data: 'tanggal_pembelian', name: 'tanggal_pembelian' },
            { data: 'harga_perolehan', name: 'harga_perolehan' },
            { data: 'nilai_residu', name: 'nilai_residu' },
            { data: 'umur_ekonomis', name: 'umur_ekonomis' },
            { data: 'depreciated', name: 'depreciated' },
            { data: 'penyusutan_berjalan', name: 'penyusutan_berjalan' },
            { data: 'nilai_buku', name: 'nilai_buku' }, */
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
