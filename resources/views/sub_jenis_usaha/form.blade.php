<div class="modal-dialog modal-simple">

		{{ Form::model($subJenisUsaha,array('route' => array((!$subJenisUsaha->exists) ? 'sub-jenis-usaha.store':'sub-jenis-usaha.update',$subJenisUsaha->pk()),
	        'class'=>'modal-content','id'=>'sub-jenis-usaha-form','method'=>(!$subJenisUsaha->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($subJenisUsaha->exists?'Edit':'Tambah').' Unit Usaha' }}</h4>
    </div>
    <div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($subJenisUsaha)->show() !!}
				
			<div class="form-group row">
				<label class="col-md-3">Badan Usaha</label>
					<div class="col-md-7">
					<select name="id_jenis_usaha" class="form-control">
					<option value="">Pilih Jenis Usaha</option>
                      @foreach ($unit as $u)
					    @if (isset($jenis->id_jenis_usaha) && !empty ($jenis->id_jenis_usaha))
                            <option value="{{ $u->id}}" {{ ($jenis->id_jenis_usaha==$u->id )?'selected':''}}>{{ $u->nama }}</option>
							@else
							<option value="{{ $u->id}}">{{ $u->nama }}</option>
							@endif
                       @endforeach
                     </select>
					</div>
				</div>
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($subJenisUsaha)->show() !!}
														  
		<div class="col-md-12 float-right">
			<div class="text-right">
				<button class="btn btn-primary" id="simpan">Simpan</button>
				</div>
			</div>
		</div>
	{{ Form::close() }}
</div>


<script type="text/javascript">

$('#kode').change(function () {
    var kode = $(this).val();
    var url = '{{ route("cekKodeSubJenisUsaha", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Kode Unit Usaha sudah ada','warning')
        }}
    });
});

$(document).ready(function(){
	$('#sub-jenis-usaha-form').formValidation({
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
					},id_jenis_usaha : { validators: {
				        notEmpty: {
				          message: 'Kolom id_jenis_usaha tidak boleh kosong'
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
