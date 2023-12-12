<div class="modal-dialog modal-simple">

		{{ Form::model($pelanggan,array('route' => array((!$pelanggan->exists) ? 'pelanggan.store':'pelanggan.update',$pelanggan->pk()),
	        'class'=>'modal-content','id'=>'pelanggan-form','method'=>(!$pelanggan->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($pelanggan->exists?'Edit':'Tambah').' Pelanggan' }}</h4>
    </div>
    <div class="modal-body">
				{!! App\Console\Commands\Generator\Form::input('kode','text')->model($pelanggan)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('nama','text')->model($pelanggan)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('keterangan','text')->model($pelanggan)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('telp','text')->model($pelanggan)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('email','text')->model($pelanggan)->show() !!}
				{!! App\Console\Commands\Generator\Form::input('alamat','text')->model($pelanggan)->show() !!}
		
		<div class="form-group row">
			<label class="col-md-3">Batas Kredit</label>
				<div class="col-md-7">
				<input type="text" name="batas_kredit" id="batas_kredit" 
				value="{{ isset($pelanggan) ? number_format($pelanggan->batas_kredit,2,",",".") : '' }}" class="form-control">
			</div>
		</div>	
		
		<input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
		<input type="hidden" name="saldo_piutang" value="0">

		<div class="form-group row">
			<label class="col-md-3">Provinsi</label>
				<div class="col-md-7">
				<select name="id_provinsi" id="provinsi" class="form-control select">
					<option value="">Pilih Provinsi</option>
                    @foreach ($provinsi as $p)
					<option value="{{ $p->id}}" {{ ($pelanggan->id_provinsi==$p->id)?'selected':''}}>{{ $p->provinsi }}</option>
                    @endforeach
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Kabupaten</label>
				<div class="col-md-7">
				<select id="kabupaten" name="id_kabupaten" class="form-control select">
				@if (isset($pelanggan))
				<option value="">Pilih Kabupaten</option>
            	<option value="{{ $pelanggan->id_kabupaten }}">{{ $pelanggan->kabupaten}}</option>
				@else
				<option value="">Pilih Kabupaten</option>
				@endif
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Kecamatan</label>
				<div class="col-md-7">
				<select name="id_kecamatan" id="kecamatan" class="form-control select">
				<option value="">Pilih Kecamatan</option>
				@if (isset($pelanggan))
            	<option value="{{ $pelanggan->id_kecamatan }}">{{ $pelanggan->kecamatan}}</option>
				@else
				<option value="">Pilih Kecamatan</option>
				@endif
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Kelurahan</label>
				<div class="col-md-7">
				<select name="id_kelurahan" id="kelurahan" class="form-control select">
				<option value="">Pilih Kelurahan</option>
				@if (isset($pelanggan))
            	<option value="{{ $pelanggan->id_kelurahan }}">{{ $pelanggan->kelurahan}}</option>
				@else
				<option value="">Pilih Kelurahan</option>
				@endif
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Termin Pembayaran</label>
				<div class="col-md-7">
				<select name="id_termin" class="form-control select">
					<option value="">Pilih Termin Pembayaran</option>
                    @foreach ($termin as $t)
                    <option value="{{ $t->id}}" {{ ($pelanggan->id_termin==$t->id)?'selected':''}}>{{ $t->kode}}</option>
                    @endforeach
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Rekening Kontrol</label>
				<div class="col-md-7">
				<select name="id_perkiraan" class="form-control select">
					<option value="">Pilih Perkiraan</option>
                    @foreach ($rekening as $r)
                    <option value="{{ $r->id}}" {{ ($pelanggan->id_perkiraan==$r->id)?'selected':''}}>{{ $r->nama}}</option>
                    @endforeach
                </select>
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
    dropdownParent: $("#pelanggan-form"),
	width: '100%'
});

$('#batas_kredit').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).ready(function(){

//Kabupaten Ubah
$('#provinsi').change(function(){

   // id provinsi
   var id = $(this).val();
   // Empty the dropdown
   $('#kabupaten').find('option').not(':first').remove();

// AJAX request
$.ajax({
	url: 'pelanggan/get-kabupaten/'+id,
	type: 'get',
	dataType: 'json',
	success: function(response){
	var len = 0;
	   		
		if(response['data'] != null)
		{
		 	len = response['data'].length;
	   	}

	   	if(len > 0){
		 // Read data and create <option >
			for(var i=0; i<len; i++){
		   		var id = response['data'][i].id;
		   		var kabupaten = response['data'][i].kabupaten;
		   		var option = "<option value='"+id+"'>"+kabupaten+"</option>";
		   		$("#kabupaten").append(option);
			}}}
  		});
	});
});

$(document).ready(function(){

//Ubah kecamatan
$('#kabupaten').change(function(){
   // id kabupaten 
var id = $(this).val();
   // Empty the dropdown
$('#kecamatan').find('option').not(':first').remove();

   // AJAX request
$.ajax({
	url: 'pelanggan/get-kecamatan/'+id,
	type: 'get',
	dataType: 'json',
	success: function(response){
	var len = 0;

	if(response['data'] != null)
	{
		len = response['data'].length;
	}

	   	if(len > 0){
		 // Read data and create <option >
		 	for(var i=0; i<len; i++){
		   		var id = response['data'][i].id;
		   		var kecamatan = response['data'][i].kecamatan;
		   		var option = "<option value='"+id+"'>"+kecamatan+"</option>";

		   		$("#kecamatan").append(option);
			}}}
  		});
	});
});

$(document).ready(function(){

//Ubah kecamatan
$('#kecamatan').change(function(){

   // Department id
   var id = $(this).val();

   // Empty the dropdown
   $('#kelurahan').find('option').not(':first').remove();

   // AJAX request
   $.ajax({
	 url: 'pelanggan/get-kelurahan/'+id,
	 type: 'get',
	 dataType: 'json',
	 success: function(response){

	   var len = 0;
	   if(response['data'] != null){
		 len = response['data'].length;
	   }

	   if(len > 0){
		 for(var i=0; i<len; i++){
		   var id        = response['data'][i].id;
		   var kelurahan = response['data'][i].kelurahan;
		   var option = "<option value='"+id+"'>"+kelurahan+"</option>";

		   $("#kelurahan").append(option);
		 }
	   }

	 }
  });
});

});

$(document).ready(function(){
	$('#pelanggan-form').formValidation({
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
