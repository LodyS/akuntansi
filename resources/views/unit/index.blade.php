@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Kode Cost Centre</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
    <!--<a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('unit.create') }}')" >Tambah</a>-->
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
                    <label class="col-md-3">Kode Cost Center</label>
                        <div class="col-md-7">
                        <input type="text" name="code_cost_centre" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Nama</label>
                        <div class="col-md-7">
                        <input type="text" name="nama" class="form-control">
                    </div>
                </div>

                <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button><br/><br/>

                    <table class="table table-hover dataTable table-striped w-full" id="unit-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Profit</th>
                                <th>Kode Cost Centre</th>
                                <th>Induk Cost Centre</th>
                                <th>Level</th>
                                <th>Urutan</th>
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
            document.location = '{{ url("unit?status=trash") }}';
        } else {
            document.location = '{{ url("unit") }}';
        }
    });
    
    const table = $('#unit-table').DataTable({
        stateSave: true,
        processing : true,
        serverSide : true,
        searching : false,
        pageLength:20,
        ajax : {
                url:"{{ url('unit/load-data') }}",
                data: function (d) {
                const form = document.forms.namedItem("formCari");
                d.nama = form.nama.value;
                d.code_cost_centre = form.code_cost_centre.value;
            }
        },
        
        columns: [
            { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
            { data: 'nama', name: 'nama' },
			{ data: 'profit', name: 'profit' },
            { data: 'code_cost_centre', name: 'code_cost_centre' },
            { data: 'induk_cost_centre', name: 'induk_cost_centre' },
            { data: 'level', name: 'level' },
            { data: 'urutan', name: 'urutan' },
            //{ data: 'action', name: 'action', orderable: false, searchable: false },
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

function con(id,status)
{
    if(status=='Y')
    {
        var con=confirm("Apakah anda yakin untuk menonaktifkan Departemen ini?");
        if(con==true)
        {
            window.location.href = "{{ url('unit/deactivate/')}}/"+id+"";
        } else {
            return false;
        }
    } else {
        var con1=confirm(" Anda yakin akan mengaktifkan Departemen ini?");
        if(con1==true)
        {
            window.location.href = "{{ url('unit/activate/')}}/"+id+"";
        } else {
            return false;
        }
    }
}
</script>
@endpush