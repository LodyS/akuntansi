<div class="modal-dialog modal-simple">

		{{ Form::model($settingPerusahaan,array('route' => array((!$settingPerusahaan->exists) ? 'setting-perusahaan.store':'setting-perusahaan.update',$settingPerusahaan->pk()),
	        'class'=>'modal-content','id'=>'setting-perusahaan-form','method'=>(!$settingPerusahaan->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($settingPerusahaan->exists?'Edit':'Tambah').' Setting Perusahaan' }}</h4>
    </div>
    <div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('alamat','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('email','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('website','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('telepon','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('fax','text')->model($settingPerusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('url','text')->model($settingPerusahaan)->show() !!}
		
	<div class="form-group row">
		<label class="col-md-3">Tanggal Awal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal_berdiri" id="tanggal_berdiri" value="{{ isset($settingPerusahaan) ? $settingPerusahaan->tanggal_berdiri : '' }}" 
			class="form-control">
		</div>
	</div>
		
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
	$('#setting-perusahaan-form').formValidation({
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
