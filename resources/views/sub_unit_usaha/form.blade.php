<div class="modal-dialog modal-simple">

{{ Form::model($subUnitUsaha,array('route' => array((!$subUnitUsaha->exists) ? 'sub-unit-usaha.store':'sub-unit-usaha.update',$subUnitUsaha->pk()),
'class'=>'modal-content','id'=>'sub-unit-usaha-form','method'=>(!$subUnitUsaha->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    	<span aria-hidden="true">×</span>
    </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($subUnitUsaha->exists?'Edit':'Tambah').' Sub Unit Usaha' }}</h4>
</div>
    
	<div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($subUnitUsaha)->show() !!}
				
			<div class="form-group row">
				<label class="col-md-3">Nama Sub Unit Usaha</label>
					<div class="col-md-7">
					<select name="id_sub_jenis_usaha" class="form-control">
						<option value="">Pilih Sub Unit Usaha</option>
                      	@foreach ($unit as $u)
					    <option value="{{ $u->id}}" {{ ($subUnitUsaha->id_sub_jenis_usaha==$u->id )?'selected':''}}>{{ $u->nama }}</option>
                       	@endforeach
                    </select>
				</div>
			</div>
		
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($subUnitUsaha)->show() !!}
																 
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
    var url = '{{ route("cekKodeSubUnitUsaha", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Kode Sub Unit Usaha sudah ada','warning')
        }}
    });
});

	$('#sub-unit-usaha-form').formValidation({
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
					},id_sub_jenis_usaha : { validators: {
				        notEmpty: {
				          message: 'Kolom id_sub_jenis_usaha tidak boleh kosong'
							}
						}
					},nama : { validators: {
				        notEmpty: {
				          message: 'Kolom nama tidak boleh kosong'
							}
						}
					},flag_aktif : { validators: {
				        notEmpty: {
				          message: 'Kolom flag_aktif tidak boleh kosong'
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
