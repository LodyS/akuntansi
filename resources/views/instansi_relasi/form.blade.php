<div class="modal-dialog modal-simple">

{{ Form::model($instansiRelasi,array('route' => array((!$instansiRelasi->exists) ? 'instansi-relasi.store':'instansi-relasi.update',$instansiRelasi->pk()),
'class'=>'modal-content','id'=>'instansi-relasi-form','method'=>(!$instansiRelasi->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="formModalLabel">{{ ($instansiRelasi->exists?'Edit':'Tambah').' Instansi Relasi' }}</h4>
</div>

<div class="modal-body">
	<input type="hidden" name="saldo_hutang" value="0">
	<input type="hidden" name="tanggal_hutang" value="2000-01-01">
	<input type="hidden" name="jatuh_tempo" value="2000-01-01">
		{!! App\Console\Commands\Generator\Form::input('kode','text')->model($instansiRelasi)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($instansiRelasi)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('alamat','text')->model($instansiRelasi)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('telp','text')->model($instansiRelasi)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('email','text')->model($instansiRelasi)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('rekening','text')->model($instansiRelasi)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('atas_nama','text')->model($instansiRelasi)->show() !!}
		
	<div class="form-group row">
		<label class="col-md-3">Batas Kredit</label>
			<div class="col-md-7">
			<input type="text" name="batas_kredit" id="batas_kredit" 
			value="{{ isset($instansiRelasi) ? number_format($instansiRelasi->batas_kredit,2,",",".") : '' }}" class="form-control">
		</div>
	</div>
								
	<div class="form-group row">
		<label class="col-md-3">Jenis Instansi Relasi</label>
			<div class="col-md-7">
			<select name="id_jenis_instansi_relasi"  class="form-control select">
				<option value="">Pilih pemasok</option>
            	@foreach ($jenis as $jenis_instansi)
            	<option value="{{ $jenis_instansi->id}}" {{ ($instansiRelasi->id_jenis_instansi_relasi== $jenis_instansi->id)?'selected':''}}>
				{{ $jenis_instansi->nama }}</option>
            	@endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Provinsi</label>
			<div class="col-md-7">
				<select name="id_provinsi" id="provinsi" class="form-control select">
				<option value="">Pilih Provinsi</option>
                @foreach ($provinsi as $p)
				<option value="{{ $p->id}}" {{ ($instansiRelasi->id_provinsi==$p->id)?'selected':''}}>{{ $p->provinsi }}</option>
                @endforeach
            </select>	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Kabupaten</label>
			<div class="col-md-7">
			<select id="kabupaten" name="id_kabupaten" class="form-control select">
			<option value="">Pilih Kabupaten</option>
			@if (isset($instansiRelasi))
            <option value="{{ $instansiRelasi->id_kabupaten }}">{{ $instansiRelasi->kabupaten }}</option>
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
			@if (isset($instansiRelasi))
			<option value="">Pilih Kecamatan</option>
            <option value="{{ $instansiRelasi->id_kecamatan }}">{{ $instansiRelasi->kecamatan }}</option>
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
			@if (isset($instansiRelasi))
			<option value="">Pilih Kelurahan</option>
            <option value="{{ $instansiRelasi->id_kelurahan }}">{{ $instansiRelasi->kelurahan }}</option>
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
                <option value="{{ $t->id}}" {{ ($instansiRelasi->id_termin==$t->id)?'selected':''}}>{{ $t->kode}}</option>
                @endforeach
            </select>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-md-3">Rekening Kontrol</label>
			<div class="col-md-7">
			<select name="id_perkiraan" class="form-control select" required>
				<option value="">Pilih Perkiraan</option>
            	@foreach ($rekening as $r)
            	<option value="{{ $r->id}}" {{ ($instansiRelasi->id_perkiraan==$r->id)?'selected':''}}>{{ $r->nama}}</option>
            	@endforeach
            </select>	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Pajak</label>
			<div class="col-md-7">
			<select name="id_tarif_pajak" class="form-control select">
				<option value="">Pilih Pajak</option>
                @foreach ($pajak as $p)
                <option value="{{ $p->id}}" {{ ($instansiRelasi->id_tarif_pajak== $p->id)?'selected':''}}>{{ $p->nama_pajak}}</option>
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

$('#batas_kredit').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(".select").select2({
    dropdownParent: $("#instansi-relasi-form"),
	width: '100%'
});

$(document).ready(function(){

//Kabupaten Ubah
$('#provinsi').change(function(){

   // pronvisi id
   var id = $(this).val();

   // Empty the dropdown
   $('#kabupaten').find('option').not(':first').remove();

   // AJAX request 
   	$.ajax({
	 	url: 'instansi-relasi/get-kabupaten/'+id,
	 	type: 'get',
	 	dataType: 'json',
	 	success: function(response){

	   	var len = 0;
	   	if(response['data'] != null){
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

   // Department id
   var id = $(this).val();

   // Empty the dropdown
   $('#kecamatan').find('option').not(':first').remove();

   // AJAX request 
$.ajax({
	url: 'instansi-relasi/get-kecamatan/'+id,
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
	 		url: 'instansi-relasi/get-kelurahan/'+id,
	 		type: 'get',
	 		dataType: 'json',
	 		success: function(response){

	   	var len = 0;
	   		
			if(response['data'] != null)
			{
		 		len = response['data'].length;
	   		}

	   		if(len > 0)
			{
		 		for(var i=0; i<len; i++){
		   		var id = response['data'][i].id;
		   		var kelurahan = response['data'][i].kelurahan;
		   		var option = "<option value='"+id+"'>"+kelurahan+"</option>"; 

		   		$("#kelurahan").append(option); 
			}}}
  		});
	});
});

$(document).ready(function(){
	$('#instansi-relasi-form').formValidation({
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
					},alamat : { validators: {
				        notEmpty: {
				          message: 'Kolom alamat tidak boleh kosong'
							}
						}
					},telp : { validators: {
				        notEmpty: {
				          message: 'Kolom telp tidak boleh kosong'
							}
						}
					},email : { validators: {
				        notEmpty: {
				          message: 'Kolom email tidak boleh kosong'
							}
						}
					},id_jenis_instansi : { validators: {
				        notEmpty: {
				          message: 'Kolom id_jenis_instansi tidak boleh kosong'
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