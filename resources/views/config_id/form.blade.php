<div class="modal-dialog modal-simple">

	{{ Form::model($configId,array('route' => array((!$configId->exists) ? 'config-id.store':'config-id.update',$configId->pk()),
	'class'=>'modal-content','id'=>'config-id-form','method'=>(!$configId->exists) ? 'POST' : 'PUT')) }}
<input type="text" style="display: none" value="config" name="mode"> 
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="formModalLabel">{{ ($configId->exists?'Edit':'Tambah').' Config IDS' }}</h4>
	</div>
	<div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('config_name','text')->model($configId)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('table_source','text')->model($configId)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('config_value','text')->model($configId)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('description','text')->model($configId)->show() !!}
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
		$('#config-id-form').formValidation({
			framework: "bootstrap4",
			button: {
				selector: "#simpan",
				disabled: "disabled"
			},
			icon: null,
			fields: {
				config_name : { validators: {
				        notEmpty: {
				          message: 'Kolom Config Name tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Config Name minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					table_source : { validators: {
				        notEmpty: {
				          message: 'Kolom Table Source tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Table Source minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					config_value : { validators: {
				        notEmpty: {
				          message: 'Kolom Config Value tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Config Value minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					description : { validators: {
				        notEmpty: {
				          message: 'Kolom Description tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Description minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},
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
