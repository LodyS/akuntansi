<div class="modal-dialog modal-simple">

		{{ Form::model($jenisUsaha,array('route' => array((!$jenisUsaha->exists) ? 'jenis-usaha.store':'jenis-usaha.update',$jenisUsaha->pk()),
	        'class'=>'modal-content','id'=>'jenis-usaha-form','method'=>(!$jenisUsaha->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($jenisUsaha->exists?'Edit':'Tambah').' Jenis Usaha' }}</h4>
    </div>
    <div class="modal-body">
	{!! App\Console\Commands\Generator\Form::input('kode','text')->model($jenisUsaha)->show() !!}															        

	<div class="form-group row">
		<label class="col-md-3">Kelompok Bisnis</label>
			<div class="col-md-7">
				<select name="id_kelompok_bisnis" class="form-control">
					<option value="">Pilih Kelompok bisnis</option>
                    @foreach ($kelompokBisnis as $bisnis)
						@if (isset($jenis->id_kelompok_bisnis) && !empty ($jenis->id_kelompok_bisnis))
					    <option value="{{ $bisnis->id }}" {{ ($jenis->id_kelompok_bisnis==$bisnis->id )?'selected':''}}>{{ $bisnis->nama }}</option>
					  	@else
                        <option value="{{ $bisnis->id }}">{{ $bisnis->nama }}</option>
					@endif
                @endforeach
            </select>
		</div>
	</div>

	{!! App\Console\Commands\Generator\Form::input('nama','text')->model($jenisUsaha)->show() !!}
							
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
    var url = '{{ route("cekKodeBadanUsaha", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Kode Badan sudah ada','warning')
			//alert('Kode bank sudah ada')
        }}
    });
});

	$('#jenis-usaha-form').formValidation({
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
					},id_kelompok_bisnis : { validators: {
				        notEmpty: {
				          message: 'Kolom id_kelompok_bisnis tidak boleh kosong'
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
