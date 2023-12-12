<div class="modal-dialog modal-simple">

		{{ Form::model($settingEmail,array('route' => array((!$settingEmail->exists) ? 'setting-email.store':'setting-email.update',$settingEmail->pk()),
	        'class'=>'modal-content','id'=>'setting-email-form','method'=>(!$settingEmail->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($settingEmail->exists?'Edit':'Tambah').' Setting Email' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('mail_driver','text')->model($settingEmail)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('mail_host','text')->model($settingEmail)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('mail_port','text')->model($settingEmail)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('mail_username','text')->model($settingEmail)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('mail_password','text')->model($settingEmail)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('mail_encryption','text')->model($settingEmail)->show() !!}
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
	$('#setting-email-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	
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
