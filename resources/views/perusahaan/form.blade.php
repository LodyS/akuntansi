<div class="modal-dialog modal-simple">

		{{ Form::model($perusahaan,array('route' => array((!$perusahaan->exists) ? 'perusahaan.store':'perusahaan.update',$perusahaan->pk()),
	        'class'=>'modal-content','id'=>'perusahaan-form','method'=>(!$perusahaan->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      
	<h4 class="modal-title" id="formModalLabel">{{ ($perusahaan->exists?'Edit':'Tambah').' Perusahaan' }}</h4>
    </div>
    <div class="modal-body">

	<div class="form-group row">
		<label class="col-md-3">Kelompok Bisnis</label>
			<div class="col-md-7">
			<select name="id_kelompok_bisnis" id="id_kelompok_bisnis" class="form-control select">
				<option value="">Pilih Kelompok Bisnis</option>
                @foreach ($kelompokBisnis as $bisnis)
				<option value="{{ $bisnis->id}}" {{ ($bisnis->id==$perusahaan->id_kelompok_bisnis)?'selected':''}}>{{ $bisnis->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Jenis Usaha</label>
			<div class="col-md-7">
			<select name="id_jenis_usaha" id="id_jenis_usaha" class="form-control select">
				<option value="">Pilih Jenis Usaha</option>
                @foreach ($jenisUsaha as $ju)
                    <option value="{{ $ju->id}}" {{ ($ju->id==$perusahaan->id_jenis_usaha )?'selected':''}}>{{ $ju->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Sub Jenis Usaha</label>
			<div class="col-md-7">
			<select name="id_sub_jenis_usaha" class="form-control select" id="id_sub_jenis_usaha">
				<option value="">Pilih Sub Jenis Usaha</option>
                @foreach ($subJenisUsaha as $sju)
                <option value="{{ $sju->id}}" {{ ($sju->id==$perusahaan->id_sub_jenis_usaha )?'selected':''}}>{{ $sju->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Unit</label>
			<div class="col-md-7">
			<select name="id_unit" class="form-control select" id="id_unit">
				<option value="">Pilih Unit</option>
                @foreach ($unit as $u)
                	<option value="{{ $u->id}}" {{ ($u->id==$perusahaan->id_unit )?'selected':''}}>{{ $u->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Unit Usaha</label>
			<div class="col-md-7">
			<select name="id_sub_unit_usaha" class="form-control" id="id_sub_unit_usaha">
				<option value="">Pilih Unit Usaha</option>
                @foreach ($unitUsaha as $us)
                <option value="{{ $us->id}}" {{ ($us->id==$perusahaan->id_sub_unit_usaha )?'selected':''}}>{{ $us->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">NPWP</label>
			<div class="col-md-7">
			<input type="text" name="npwp" class="form-control">
		</div>
	</div>

		{!! App\Console\Commands\Generator\Form::input('nama_badan_usaha','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('nama_unit_usaha','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('kode_unit_usaha','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('alamat_perusahaan','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('kota','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('negara_perusahaan','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('kode_pos','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('telepon_perusahaan','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('fax_perusahaan','text')->model($perusahaan)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('email_perusahaan','text')->model($perusahaan)->show() !!}
			
	<div class="col-md-12 float-right">
		<div class="text-right">
			<button class="btn btn-primary" id="simpan">Simpan</button>
		</div>
	</div>
</div>
{{ Form::close() }}
</div>
</div> yyyyy


<script type="text/javascript">
$('#texta, #selectiona, #textb, #selectionb').bind('input change', function() {
  $('#sentence').val($('#texta').val() + ' ' +
    $('#selectiona').val() + ' ' +
    $('#textb').val() + ' ' +
    $('#selectionb').val());
});

$('#id_kelompok_bisnis').select2({
  width : '100%'
});

$('#id_jenis_usaha').select2({
  width : '100%'
});

$('#id_sub_jenis_usaha').select2({
  width : '100%'
});

$('#id_unit').select2({
  width : '100%'
});

$('#id_sub_unit_usaha').select2({
  width : '100%'
});


$(document).ready(function(){
	$('#perusahaan-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama_badanusaha : { validators: {
				        notEmpty: {
				          message: 'Kolom nama badan usaha tidak boleh kosong'
							}
						}
					}, alamat_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom alamat perusahaan tidak boleh kosong'
							}
						}
					},kota : { validators: {
				        notEmpty: {
				          message: 'Kolom kota tidak boleh kosong'
							}
						}
					},negara_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom negara perusahaan tidak boleh kosong'
							}
						}
					},kode_pos : { validators: {
				        notEmpty: {
				          message: 'Kolom kode pos tidak boleh kosong'
							}
						}
					},telepon_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom telepon perusahaan tidak boleh kosong'
							}
						}
					},fax_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom fax perusahaan tidak boleh kosong'
							}
						}
					},email_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom email perusahaan tidak boleh kosong'
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
