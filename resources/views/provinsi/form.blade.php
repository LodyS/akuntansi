<div class="modal-dialog modal-simple">

	{{ Form::model($provinsi,array('route' => array((!$provinsi->exists) ? 'provinsi.store':'provinsi.update',$provinsi->pk()),
	'class'=>'modal-content','id'=>'provinsi-form','method'=>(!$provinsi->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="formModalLabel">{{ ($provinsi->exists?'Edit':'Tambah').' Provinsi' }}</h4>
	</div>
	<div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($provinsi)->show(['label'=>'Kode Provinsi']) !!}
		{!! App\Console\Commands\Generator\Form::input('provinsi','text')->model($provinsi)->show(['label'=>'Provinsi']) !!}
		{{-- {!! App\Console\Commands\Generator\Form::checkbox('flag_aktif','Flag Aktif',array('label'=>'','value'=>'Y'),$provinsi->exists?$provinsi->flag_aktif:'N') !!} --}}
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
		$('#provinsi-form').formValidation({
			framework: "bootstrap4",
			button: {
				selector: "#simpan",
				disabled: "disabled"
			},
			icon: null,
			fields: {
				kode : { validators: {
					notEmpty: {
						message: 'Kolom Kode Provinsi tidak boleh kosong'
					}
				}
			},
			provinsi : { validators: {
				notEmpty: {
					message: 'Kolom Provinsi tidak boleh kosong'
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
