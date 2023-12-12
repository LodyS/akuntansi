@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Upload File Jurnal</h1>
    <div class="page-header-actions">
        <form action="{{ url('/dowload-format-jurnal')  }}" method="get">{{ @csrf_field() }}
            <button class="btn btn-danger btn-round" type="submit"><i class="icon glyphicon glyphicon-download" aria-hidden="true"></i>Dowload Format Excel</button>
        </form>
    </div>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center">Upload File Jurnal dengan tipe file excel/csv</h3><br/>
	            <form action="{{ url('/simpan-upload-jurnal')  }}" method="post" enctype="multipart/form-data" id="upload">{{ @csrf_field() }}
                    <input type="hidden" name="tanggal_posting" value="{{ date('Y-m-d') }}">
                    <div class="form-group row">
		                <label class="col-md-3">File</label>
			                <div class="col-md-7">
			                <input type="file" name="file" id="file" class="form-control-file" required>
		                </div>
	                </div>
                <button type="submit" id="simpan" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-import" aria-hidden="true"></i>Import</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$('#upload').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	file : { validators: {
				        notEmpty: {
				          message: 'File tidak boleh kosong'
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
</script>
@endpush
