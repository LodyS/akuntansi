<div class="modal-dialog modal-simple">

		{{ Form::model($kelas,array('route' => array((!$kelas->exists) ? 'kelas.store':'kelas.update',$kelas->pk()),
	        'class'=>'modal-content','id'=>'kelas-form','method'=>(!$kelas->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($kelas->exists?'Edit':'Tambah').' Kelas' }}</h4>
    </div>
    <div class="modal-body">
					{!! App\Console\Commands\Generator\Form::input('nama','text')->model($kelas)->show() !!}
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
	$('#kelas-form').formValidation({
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
