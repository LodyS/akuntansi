<div class="modal-dialog modal-simple">

		{{ Form::model($kelompokBisnis,array('route' => array((!$kelompokBisnis->exists) ? 'kelompok-bisnis.store':'kelompok-bisnis.update',$kelompokBisnis->pk()),
	        'class'=>'modal-content','id'=>'kelompok-bisnis-form','method'=>(!$kelompokBisnis->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($kelompokBisnis->exists?'Edit':'Tambah').' Kelompok Bisnis' }}</h4>
    </div>
    <div class="modal-body">
				{!! App\Console\Commands\Generator\Form::input('kode','text')->model($kelompokBisnis)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('nama','text')->model($kelompokBisnis)->show() !!}
				
														
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

	$('#kode').change(function () {
    var kode = $(this).val();
    var url = '{{ route("cekKodeKelompokBisnis", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Kode Kelompok Bisnis sudah ada','warning')
        }}
    });
});

	$('#kelompok-bisnis-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode : { validators: {
				        notEmpty: {
				          message: 'Kolom kode tidak boleh kosong'
							}
						}
					},nama : { validators: {
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
