@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Perubahan Ekuitas</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">Edit Setting Perubahan Ekuitas</h3><br/>
        <form action="{{ url('update-perubahan-ekuitas') }}" method="post">{{ @csrf_field() }}

        	<input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="id_set_lap_ekuitas" value="{{ $data->id_set_lap_ekuitas }}">

				<div class="form-group row">
					<label class="col-md-3">Kode</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->kode : ''}}" readonly>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Induk</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->induk : ''}}" readonly>
					</div>
				</div>

        		<div class="form-group row">
					<label class="col-md-3">Jenis Perubahan Ekuitas</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->nama : ''}}" readonly>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Surplus Defisit</label>
						<div class="col-md-7">
							<select name="id_set_surplus_defisit" id="id_set_surplus_defisit" class="form-control select">
        					<option value="">Pilih</option>
        					@foreach ($setting_surplus as $setting)
        					<option value="{{ $setting->id }}" {{ ($data->id_set_surplus_defisit== $setting->id)?'selected':''}}>{{ $setting->nama }}</option>
        					@endforeach
        				</select>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Rekening</label>
						<div class="col-md-7">
							<select name="id_perkiraan" id="id_perkiraan" class="form-control select">
        					<option value="">Pilih</option>
        					@foreach ($perkiraan as $setting)
        					<option value="{{ $setting->id }}" {{ ($data->id_perkiraan== $setting->id)?'selected':''}}>{{ $setting->nama }}</option>
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
$('#id_perkiraan').select2({
 	width : '100%'
});

$('#id_set_surplus_defisit').select2({
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

	    id_unit : {
			validators: {
			notEmpty: {
				message: 'Kolom Unit tidak boleh kosong'
			}
		}
	}
},

err: {
	clazz: 'invalid-feedback'
},
control: {
	valid: 'is-valid',
	invalid: 'is-invalid'
},
row: {
	invalid: 'has-danger'
}
});

});
</script>
@endpush
