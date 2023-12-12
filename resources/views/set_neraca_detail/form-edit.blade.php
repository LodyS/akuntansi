@extends('layouts.app')

@section('content')
   
<div class="page-header">
    <h1 class="page-title">Setting Neraca</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">Update Setting Neraca</h3><br/>
        <form action="{{ url('/update-set-neraca-detail') }}" method="post" id="edit">{{ @csrf_field() }} 
        
        <input type="hidden" name="id" value="{{ $setNeracaDetail->id }}">
        
        <div class="form-group row">
		    <label class="col-md-3">Kode</label>
			    <div class="col-md-7">
			    <input type="text" class="form-control" value="{{ isset($setNeracaDetail->kode) ? $setNeracaDetail->kode : '' }}" readonly>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Jenis Neraca</label>
			    <div class="col-md-7">
			    <input type="text" class="form-control" value="{{ isset($setNeracaDetail->jenis_neraca) ? $setNeracaDetail->jenis_neraca : '' }}" readonly>
		    </div>
	    </div>

        <div class="form-group row">
			<label class="col-md-3">Induk</label>
			    <div class="col-md-7">
			    <input type="text" class="form-control" value="{{ isset($setNeracaDetail->induk) ? $setNeraca->induk : '' }}" readonly>
		    </div>
	    </div>

            <div class="form-group row">
            	<label class="col-md-3">Rekening</label>
                	<div class="col-md-7">
                		<select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                        <option value="">Pilih Induk</option>
                        @foreach ($perkiraan as $rekening)
                        <option value="{{ $rekening->id }}" {{ ($setNeracaDetail->id_perkiraan== $rekening->id)?'selected':''}}>{{ $rekening->nama }}</option>
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

$(document).ready(function(){
	$('#edit').formValidation({
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
