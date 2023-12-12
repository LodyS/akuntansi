<div class="modal-dialog modal-simple">

		{{ Form::model($spesialisasi,array('route' => array((!$spesialisasi->exists) ? 'spesialisasi.store':'spesialisasi.update',$spesialisasi->pk()),
	        'class'=>'modal-content','id'=>'spesialisasi-form','method'=>(!$spesialisasi->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($spesialisasi->exists?'Edit':'Tambah').' Spesialisasi' }}</h4>
    </div>
    <div class="modal-body">

	<div class="form-group row">
		<label class="col-md-3">Kode</label>
			<div class="col-md-7">
			<input name="kode" id="kode" value="{{ isset($lastCode) ? $lastCode : 'S-1' }}" class="form-control" readonly>			
		</div>
	</div>
						
	{!! App\Console\Commands\Generator\Form::input('nama','text')->model($spesialisasi)->show() !!}
				
		<div class="col-md-12 float-right">
			<div class="text-right">
				<button class="btn btn-primary" id="simpan">Simpan</button>
			</div>
		</div>
	</div>
{{ Form::close() }}
</div>


<script type="text/javascript">
$(document).ready(function(){
	$('#spesialisasi-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode : { validators: {
				        notEmpty: {
				          message: 'Kolom kode tidak boleh kosong'
							}
						}
					},nama : { validators: {
				        notEmpty: {
				          message: 'Kolom nama tidak boleh kosong'
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
