<div class="modal-dialog modal-simple">

		{{ Form::model($jenisInstansiRelasi,array('route' => array((!$jenisInstansiRelasi->exists) ? 'jenis-instansi-relasi.store':'jenis-instansi-relasi.update',$jenisInstansiRelasi->pk()),
	        'class'=>'modal-content','id'=>'jenis-instansi-relasi-form','method'=>(!$jenisInstansiRelasi->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($jenisInstansiRelasi->exists?'Edit':'Tambah').' Jenis Supplier' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('nama','text')->model($jenisInstansiRelasi)->show() !!}
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
	$('#jenis-instansi-relasi-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama : { validators: {
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
