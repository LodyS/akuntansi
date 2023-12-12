@extends('layouts.app')

@section('content')
   
<div class="page-header">
    <h1 class="page-title">Setting Surplus Defisit</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">Tambah Setting Surplus Defisit</h3><br/>
	    <form action="{{ route('set-surplus-defisit-detail.store') }}" method="post" id="setting">{{ @csrf_field() }} 
        
        	<input type="hidden" name="id_set_surplus_defisit" value="{{ $data->id }}">
        
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
					<label class="col-md-3">Komponen Surplus Defisit</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->nama : ''}}" readonly>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Cost Centre</label>
						<div class="col-md-7">
							<select name="id_unit" id="id_unit" class="form-control select" required>
        					<option value="">Pilih</option>
        					@foreach ($unit as $yunit)
        					<option value="{{ $yunit->id }}">{{ $yunit->kode }}</option>
        					@endforeach
        				</select>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Unit</label>
						<div class="col-md-7">
						<input class="form-control" id="unit" readonly>
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
$('#id_unit').select2({
 	width : '100%'
});

$(document).ready(function(){

	$('#id_unit').change(function(){
    	var id_unit = $(this).val();
    	var url = '{{ route("isiUnitSetSurplusDefisit", ":id_unit") }}';
    	url = url.replace(':id_unit', id_unit);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        	if(response != null){
            	$('#unit').val(response.nama);
        	}}
    	});
	});

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
