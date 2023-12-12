@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Manajemen Anggaran</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	        <form action="{{ url('simpan-manajemen-anggaran')}}" method="post">{{ @csrf_field() }}

                <div class="form-group row">
		            <label class="col-md-3">Nama</label>
			            <div class="col-md-7">
                        <input type="text" name="nama" class="form-control" required>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Periode Anggaran</label>
			            <div class="col-md-7">
                        <input type="month" name="periode_anggaran" class="form-control" required>
		            </div>
	            </div>

                <button class="btn btn-dark" type="button" id="add">Tambah</button><br/>

                <table class="table table-hover" id="tambah_form">
                    <tr>
                        <th>Daftar Akun</th>
                        <th>Unit/Cost Center</th>
                        <th>Nominal</th>
                        <th>Hapus</th>
                    </tr>
                </table>

                <button type="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function() {
var i = 0

$('#add').click(function() {
    i++;
    $('#tambah_form').append('<tr id="row' + i + '">\n\
        <td><select class="form-control select" name="id_perkiraan[]" id="perkiraan-' + i + '" required>\n\
            <option value="">Pilih Unit</option>\n\
            @foreach ($perkiraan as $p)<option value="{{ $p->id }}">{{ $p->kode_rekening }} - {{ $p->nama }}</option> @endforeach\n\
            </select></td>\n\
        <td><select class="form-control select" name="id_unit[]" id="unit-' + i + '" required>\n\
            <option value="">Pilih Unit</option>\n\
            @foreach ($unit as $u)<option value="{{ $u->id }}">{{ $u->code_cost_centre }} - {{ $u->nama }}</option> @endforeach\n\
            </select></td>\n\
        <td><input type="text" id="nominal-' + i + '" name="nominal[]" class="form-control" required></td>\n\
        <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove"> \n\
        <i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i></button></td></tr>');

    $('#nominal-'+i).on('change click keyup input paste',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));

    $('#perkiraan-' + i).select2({ theme: "bootstrap-5", width: '100%' });
    $('#unit-' + i).select2({ theme: "bootstrap-5", width: '100%' });

    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        }); // untuh hapus form dinamis
    });
});
</script>
@endpush
