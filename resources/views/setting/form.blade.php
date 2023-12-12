<div class="modal-dialog modal-simple">

		{{ Form::model($setting,array('route' => array((!$setting->exists) ? 'setting.store':'setting.update',$setting->pk()),
	        'class'=>'modal-content','id'=>'setting-form','method'=>(!$setting->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($setting->exists?'Edit':'Tambah').' Setting' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('nama_aplikasi','text')->model($setting)->show() !!}
																        {!! App\Console\Commands\Generator\Form::textarea( 'alamat' )->model($setting)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('website','text')->model($setting)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('fax','text')->model($setting)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('telepon','text')->model($setting)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('email','text')->model($setting)->show() !!}
																        {!! App\Console\Commands\Generator\Form::textarea( 'logo' )->model($setting)->show() !!}
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
	$('#setting-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama_aplikasi : { validators: {
				        notEmpty: {
				          message: 'Kolom nama_aplikasi tidak boleh kosong'
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
