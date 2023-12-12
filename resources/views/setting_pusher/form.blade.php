<div class="modal-dialog modal-simple">

		{{ Form::model($settingPusher,array('route' => array((!$settingPusher->exists) ? 'setting-pusher.store':'setting-pusher.update',$settingPusher->pk()),
	        'class'=>'modal-content','id'=>'setting-pusher-form','method'=>(!$settingPusher->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($settingPusher->exists?'Edit':'Tambah').' Setting Pusher' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('pusher_app_id','text')->model($settingPusher)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('pusher_app_key','text')->model($settingPusher)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('pusher_app_secret','text')->model($settingPusher)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('pusher_app_cluster','text')->model($settingPusher)->show() !!}
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
	$('#setting-pusher-form').formValidation({
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
