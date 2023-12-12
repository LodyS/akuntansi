<div class="modal-dialog modal-simple">

	{{ Form::model($kelompokAktiva,array('route' => array((!$kelompokAktiva->exists) ? 'kelompok-aktiva.store':'kelompok-aktiva.update',$kelompokAktiva->pk()),
	    'class'=>'modal-content','id'=>'kelompok-aktiva-form','method'=>(!$kelompokAktiva->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	<span aria-hidden="true">×</span>
       </button>

      <h4 class="modal-title" id="formModalLabel">{{ ($kelompokAktiva->exists?'Edit':'Tambah').' Kelompok Aktiva' }}</h4>
    </div>

    <div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($kelompokAktiva)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($kelompokAktiva)->show() !!}
		<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
		<input type="hidden" name="user_update" value="{{ Auth::user()->id }}">

	<div class="form-group row">
		<label class="col-md-3">Penyusutan</label>
			<div class="col-md-7">
			<input type="radio" required name="flag_penyusutan" id="Ya" value="Y" onClick="javascript:showForm()" {{ ($kelompokAktiva->flag_penyusutan=="Y")?'checked':''}}><label>Ya</label>
			&nbsp;&nbsp;
            <input type="radio" name="flag_penyusutan" id="Tidak" value="N" onClick="javascript:showForm()" {{ ($kelompokAktiva->flag_penyusutan=="N")?'checked':''}}><label>Tidak</label>
		</div>
	</div>

	<div id="tampil" style="display:none" class="none">
		<div class="form-group row">
			<label class="col-md-3">Harga Perolehan</label>
				<div class="col-md-7">
				<select name="harga_perolehan" class="form-control select">
				<option value="">Pilih Harga Perolehan</option>
				@foreach ($perkiraan as $s)
				<option value="{{ $s->id }}" {{ ($kelompokAktiva->harga_perolehan==$s->id )?'selected':''}}>{{ $s->nama }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Biaya Penyusutan</label>
			<div class="col-md-7">
				<select name="biaya_penyusutan" class="form-control select">
				<option value="">Pilih Biaya Penyusutan</option>
				@foreach ($perkiraan as $s)
				<option value="{{ $s->id }}" {{ ($kelompokAktiva->biaya_penyusutan==$s->id )?'selected':''}}>{{ $s->nama }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Akumulasi Penyusutan</label>
			<div class="col-md-7">
				<select name="akumulasi_penyusutan" class="form-control select">
				<option value="">Pilih Akumulasi Penyusutan</option>
				@foreach ($perkiraan as $s)
				<option value="{{ $s->id }}" {{ ($kelompokAktiva->akumulasi_penyusutan==$s->id )?'selected':''}}>{{ $s->nama }}</option>
				@endforeach
				</select>
			</div>
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

$(".select").select2({
    dropdownParent: $("#kelompok-aktiva-form"),
	width: '100%'
});

$('#kode').change(function () {
    var kode = $(this).val();
    var url = '{{ route("cekKodeKelompokAktiva", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {

        if (response.status == 'Ada') {
            swal('Warning','Kode Kelompok Aktiva sudah ada','warning')
			//alert('Kode bank sudah ada')
        }}
    });
});

$('#nama').change(function () {
    var nama = $(this).val();
    var url = '{{ route("cekNamaKelompokAktiva", ":nama") }}';
    url = url.replace(':nama', nama);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {

        if (response.status == 'Ada') {
            swal('Warning','Nama Kelompok Aktiva sudah ada','warning')
			//alert('Kode bank sudah ada')
        }}
    });
});

function showForm (){
	if (document.getElementById('Ya').checked){
		document.getElementById('tampil').style.display = 'block';
	}
	else {
		document.getElementById('tampil').style.display = 'none';
	}
}

$(document).ready(function(){
	$('#kelompok-aktiva-form').formValidation({
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
					},flag_penyusutan : { validators: {
				        notEmpty: {
				          message: 'Kolom flag_penyusutan tidak boleh kosong'
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
