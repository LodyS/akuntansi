<div class="modal-dialog modal-simple">

		{{ Form::model($kasBank,array('route' => array((!$kasBank->exists) ? 'kas-bank.store':'kas-bank.update',$kasBank->pk()),
	        'class'=>'modal-content','id'=>'kas-bank-form','method'=>(!$kasBank->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($kasBank->exists?'Edit':'Tambah').' Kas Bank' }}</h4>
    </div>
    <div class="modal-body">
			<input type="hidden" name="id_user" value="{{ Auth::user()->id }}">

			<div class="form-group row">
				<label class="col-md-3">Kode Bank</label>
					<div class="col-md-7">
					<input type="text" name="kode_bank" id="kode_bank" class="form-control"
					value="{{ isset($kasBank) ? $kasBank->kode_bank : ''}}" required>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3">Nama Bank</label>
					<div class="col-md-7">
					<input type="text" name="nama" id="nama" class="form-control" value="{{ isset($kasBank) ? $kasBank->nama : ''}}">
				</div>
			</div>

			{!! App\Console\Commands\Generator\Form::input('keterangan','text')->model($kasBank)->show() !!}
			{!! App\Console\Commands\Generator\Form::input('alamat','text')->model($kasBank)->show() !!}

			<div class="form-group row">
				<label class="col-md-3">E-mail</label>
					<div class="col-md-7">
					<input type="email" name="email" id="email" class="form-control" value="{{ isset($kasBank) ? $kasBank->email : ''}}">
				</div>
			</div>

			{!! App\Console\Commands\Generator\Form::input('telepon','text')->model($kasBank)->show() !!}
			{!! App\Console\Commands\Generator\Form::input('fax','text')->model($kasBank)->show() !!}

			<div class="form-group row">
				<label class="col-md-3">Badan Usaha</label>
					<div class="col-md-7">
						<select name="id_jenis_usaha" id="id_jenis_usaha" class="form-control select">
						<option value="">Pilih Badan Usaha</option>
                        @foreach ($jenisUsaha as $j)
                        <option value="{{ $j->id}}" {{ ($j->id==$kasBank->id_jenis_usaha )?'selected':''}}>{{ $j->nama }}</option>
                        @endforeach
                    </select>
				</div>
			</div>

			@if($aksi === 'create')
			<div class="form-group row">
				<label class="col-md-3">Perkiraan</label>
					<div class="col-md-7">
						<select name="id_perkiraan" id="id_perkiraan" class="form-control select">
						<option value="">Pilih Perkiraan</option>
                        @foreach ($perkiraan as $kira)
                        <option value="{{ $kira->id}}">{{ $kira->nama }}</option>
                        @endforeach
                    </select>
				</div>
			</div>
			@endif

			{!! App\Console\Commands\Generator\Form::input('rekening','text')->model($kasBank)->show() !!}
			{!! App\Console\Commands\Generator\Form::input('kode_pos','text')->model($kasBank)->show() !!}
			{!! App\Console\Commands\Generator\Form::input('negara','text')->model($kasBank)->show() !!}

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
    dropdownParent: $("#kas-bank-form"),
	width: '100%'
});

$('#kode_bank').change(function () {
    var kode_bank = $(this).val();
    var url = '{{ route("cekKode", ":kode_bank") }}';
    url = url.replace(':kode_bank', kode_bank);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {

        if (response.status == 'Ada') {
            swal('Warning','Kode bank sudah ada','warning')
			//alert('Kode bank sudah ada')
        }}
    });
});

$(document).ready(function(){
	/*$('#kas-bank-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode_bank : { validators: {
				        notEmpty: {
				          message: 'Kolom kode_bank tidak boleh kosong'
							}
						}
					},nama : { validators: {
				        notEmpty: {
				          message: 'Kolom nama tidak boleh kosong'
							}
						}
					},keterangan : { validators: {
				        notEmpty: {
				          message: 'Kolom keterangan tidak boleh kosong'
							}
						}
					},telepon : { validators: {
				        notEmpty: {
				          message: 'Kolom telepon tidak boleh kosong'
							}
						}
					}, id_jenis_usaha : { validators: {
				        notEmpty: {
				          message: 'Kolom id_jenis_usaha tidak boleh kosong'
							}
						}
					},rekening : { validators: {
				        notEmpty: {
				          message: 'Kolom rekening tidak boleh kosong'
							}
						}
					},kode_pos : { validators: {
				        notEmpty: {
				          message: 'Kolom kode_pos tidak boleh kosong'
							}
						}
					},negara : { validators: {
				        notEmpty: {
				          message: 'Kolom negara tidak boleh kosong'
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
}); */

$('#id_jenis_usaha').select2({
  width : '100%'
});

});
</script>
