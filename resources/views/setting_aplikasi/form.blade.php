<div class="modal-dialog modal-simple">

		{{ Form::model($settingAplikasi,array('route' => array((!$settingAplikasi->exists) ? 'setting-aplikasi.store':'setting-aplikasi.update',$settingAplikasi->pk()),
	        'class'=>'modal-content','id'=>'setting-aplikasi-form','method'=>(!$settingAplikasi->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($settingAplikasi->exists?'Edit':'Tambah').' Setting Aplikasi' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('nama','text')->model($settingAplikasi)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('deskripsi','text')->model($settingAplikasi)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('logo','text')->model($settingAplikasi)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('base_url','text')->model($settingAplikasi)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('flag_morbis','text')->model($settingAplikasi)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('version','text')->model($settingAplikasi)->show() !!}
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
	$('#setting-aplikasi-form').formValidation({
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
