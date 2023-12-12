<div class="modal-dialog modal-simple">

		{{ Form::model($akunAnggaran,array('route' => array((!$akunAnggaran->exists) ? 'akun-anggaran.store':'akun-anggaran.update',$akunAnggaran->pk()),
	        'class'=>'modal-content','id'=>'akun-anggaran-form','method'=>(!$akunAnggaran->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($akunAnggaran->exists?'Edit':'Tambah').' Akun Anggaran' }}</h4>
    </div>
    <div class="modal-body">

	<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
	<input type="hidden" name="user_update" value="{{ Auth::user()->id }}">
	<input type="hidden" name="user_delete" value="{{ Auth::user()->id }}">

	<div class="form-group row">
		<label class="col-md-3">Tipe</label>
			<div class="col-md-7">
			<select name="tipe" class="form-control select">
				<option value="">Pilih Tipe Akun Anggaran</option>
                <option value="1" {{($akunAnggaran->tipe == '1')?'selected':''}}>Header</option>
				<option value="2" {{($akunAnggaran->tipe == '2')?'selected':''}}>Detail</option>
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Induk</label>
			<div class="col-md-7">
			<select name="id_induk" id="id_induk" class="form-control select">
				<option value="">Pilih Induk</option>
				@foreach ($induk as $data)
                <option value="{{ $data->id}}" {{($akunAnggaran->id_induk == $data->id)?'selected':''}}>{{ $data->nama}}</option>
				@endforeach
            </select>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-md-3">COA</label>
			<div class="col-md-7">
			<select name="id_perkiraan" id="id_perkiraan" class="form-control select">
			<option value="">Pilih Perkiraan</option>
			@foreach ($perkiraan as $data)
            <option value="{{ $data->id}}" {{($akunAnggaran->id_perkiraan == $data->id)?'selected':''}}>{{ $data->nama}}</option>
			@endforeach
            </select>
		</div>
	</div>

		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($akunAnggaran)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($akunAnggaran)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('keterangan','text')->model($akunAnggaran)->show() !!}
		<input type="hidden" name="status" id="status" value="Tidak Ada" class="form-control" readonly>
															    												      
			<div class="col-md-12 float-right">
				<div class="text-right">
					<button class="btn btn-primary" id="simpan">Simpan</button>
				</div>
			</div>
		</div>
	{{ Form::close() }}
</div>

<script type="text/javascript">

$(".select").select2({
	width: '100%'
});

$(document).ready(function(){

$('#kode').change(function () {
    var kode = $(this).val();
    var url = '{{ route("cekAkunAnggaran", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Kode Akun Anggaran sudah ada','warning')
			$('#status').val(response.status);
        }}
    });
});

	$('#akun-anggaran-form').formValidation({
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
