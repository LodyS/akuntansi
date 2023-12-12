@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Rumus Neraca</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">Tambah Setting Rumus Neraca</h3><br/>
        <form action="{{ url('/simpan-setting-rumus-neraca') }}" method="post" id="edit">{{ @csrf_field() }}

        <input type="hidden" name="id_set_neraca" value="{{ $data->id }}">

        <div class="form-group row">
		    <label class="col-md-3">Kode</label>
			    <div class="col-md-7">
			    <input type="text" class="form-control" value="{{ $data->kode }}" readonly>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Jenis Neraca</label>
			    <div class="col-md-7">
			    <input type="text" class="form-control" value="{{ $data->jenis_neraca }}" readonly>
		    </div>
	    </div>

        <div class="form-group row">
			<label class="col-md-3">Induk</label>
			    <div class="col-md-7">
			    <input type="text" class="form-control" value="{{ $data->induk }}" readonly>
		    </div>
	    </div>

            <div class="form-group row">
            	<label class="col-md-3">Neraca</label>
                	<div class="col-md-7">
                		<select name="id_rumus" id="id_rumus" class="form-control">
                        <option value="">Pilih</option>
                        @foreach ($neraca as $neraka)
                        <option value="{{ $neraka->id }}">{{ $neraka->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
            	<label class="col-md-3">Sub Rumus</label>
                	<div class="col-md-7">
                		<select name="id_sub_rumus" id="id_sub_rumus" class="form-control">
                        <option value="">Pilih</option>
                        @foreach ($subRumus as $rumus)
                        <option value="{{ $rumus->id }}">{{ $rumus->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

              <button type="submit" align="right" class="btn btn-primary" id="simpan">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$('#id_rumus').select2({
 	width : '100%'
});

$('#id_sub_rumus').select2({
 	width : '100%'
});

$(document).ready(function(){
	$('#setting').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {

		id_perkiraan : {
			validators: {
				notEmpty: {
				    message: 'Kolom Perkiraan tidak boleh kosong'
				}
			}
		}
},
err: {
	clazz: 'invalid-feedback'
},
control: {
	// The CSS class for valid control
	valid: 'is-valid',

	// The CSS class for invalid control
	invalid: 'is-invalid'
},
row: {
	invalid: 'has-danger'
}
});

});
</script>
@endpush
