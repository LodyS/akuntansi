@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Syarat Penggajuan Anggaran</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-syarat-penggajuan-anggaran')}}" method="post">{{ @csrf_field() }}

    <div class="form-group row">
		<label class="col-md-3">Nama</label>
			<div class="col-md-7">
            <input type="text" name="nama" class="form-control" required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Keterangan</label>
			<div class="col-md-7">
			<textarea class="form-control" name="keterangan" rows="4" required></textarea>
		</div>
	</div>

    <button class="btn btn-dark" type="button" id="add"> <i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i>Tambah</button>

    <table class="table table-hover" id="tambah_form">
        <tr>
            <th>Persyaratan</th>
        </tr>
    </table>

        <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-save" aria-hidden="true"></i>Simpan</button>

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
        <td><input type="text" id="persyaratan-' + i + '" name="syarat[]" class="form-control" required></td>\n\
        <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove"> \n\
        <i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i></button></td></tr>');

    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        }); // untuh hapus form dinamis
    });
});
</script>
@endpush
