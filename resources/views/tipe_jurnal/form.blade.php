<div class="modal-dialog modal-simple">

		{{ Form::model($tipeJurnal,array('route' => array((!$tipeJurnal->exists) ? 'tipe-jurnal.store':'tipe-jurnal.update',$tipeJurnal->pk()),
	        'class'=>'modal-content','id'=>'tipe-jurnal-form','method'=>(!$tipeJurnal->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($tipeJurnal->exists?'Edit':'Tambah').' Tipe Jurnal' }}</h4>
    </div>
    <div class="modal-body">
				{!! App\Console\Commands\Generator\Form::input('kode_jurnal','text')->model($tipeJurnal)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('tipe_jurnal','text')->model($tipeJurnal)->show() !!}
					<input type="hidden" name="jenis_jurnal" value="Custom">
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
	$('#tipe-jurnal-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode_jurnal : { validators: {
				        notEmpty: {
				          message: 'Kolom Kode jurnal tidak boleh kosong'
							}
						}
					},tipe_jurnal : { validators: {
				        notEmpty: {
				          message: 'Kolom Tipe Jurnal tidak boleh kosong'
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
