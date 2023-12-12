<div class="modal-dialog modal-simple">

		{{ Form::model($perkiraan,array('route' => array((!$perkiraan->exists) ? 'perkiraan.store':'perkiraan.update',$perkiraan->pk()),
	        'class'=>'modal-content','id'=>'perkiraan-form','method'=>(!$perkiraan->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      	</button>
      	<h4 class="modal-title" id="formModalLabel">{{ ($perkiraan->exists?'Edit':'Tambah').' Perkiraan' }}</h4>
    </div>
    
	<div class="modal-body">
	
	<div class="form-group row">
		<label class="col-md-3">Tipe Perkiraan</label>
			<div class="col-md-7">
			<select name="type" id="type" class="form-control">
				<option value="">Pilih Type</option>
                <option value="1" {{($perkiraan->type == '1')?'selected':''}}>Header</option>
				<option value="2" {{($perkiraan->type == '2')?'selected':''}}>Detail</option>
            </select>
		</div>
	</div>	

	<input type="hidden" name="id_periode" value="{{ isset($periodeKeuangan) ? $periodeKeuangan->id : ''}}">
	<input type="hidden" name="id_cabang_user" value="{{ isset($cabanguser) ? $cabanguser->id : '' }}">

	<div class="form-group row">
		<label class="col-md-3">Induk</label>
			<div class="col-md-7">
			<select name="id_induk" id="id_induk"  class="form-control select">
				<option value="">Pilih Induk</option>
				@foreach ($kira as $induk)
                <option value="{{ $induk->id}}" {{ ($induk->id== $perkiraan->id_induk)?'selected':''}}>{{ $induk->nama }}</option>
				@endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Kode</label>
			<div class="col-md-7">
			<input type="text" name="kode" value="{{ isset($perkiraan) ? $perkiraan->kode : '' }}" class="form-control" id="kode" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Level</label>
			<div class="col-md-7">
			<input type="text" name="level" value="{{ isset($perkiraan) ? $perkiraan->level : '' }}" class="form-control" id="level" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Nama Perkiraan</label>
			<div class="col-md-7">
			<input type="text" name="nama" id="nama" value="{{ isset($perkiraan) ? $perkiraan->nama : '' }}" class="form-control">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Group</label>
			<div class="col-md-7">
			<select name="fungsi" id="fungsi" class="form-control">
			<option value="">Pilih Group</option>
			@foreach ($fungsi as $group)
            <option value="{{ $group->id}}" {{ ($group->id == $perkiraan->fungsi)?'selected':''}}>{{ $group->nama_fungsi }}</option>
			@endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Kategori</label>
			<div class="col-md-7">
			<select name="id_kategori" id="kategori" class="form-control">
			<option value="">Pilih Kategori</option>
			@foreach ($kategori as $kat)
            <option value="{{ $kat->id}}" {{ ($kat->id== $perkiraan->id_kategori)?'selected':''}}>{{ $kat->nama }}</option>
			@endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Saldo Awal</label>
			<div class="col-md-7">
			@if ($perkiraan->id_kategori == 1)
			<input type="text" name="saldo_awal" value="{{ number_format($perkiraan->debet,2,",",".") }}" id="saldo_awal" class="form-control">
			@elseif ($perkiraan->id_kategori == 2)
			<input type="text" name="saldo_awal" value="{{ number_format($perkiraan->kredit,2,",",".") }}" id="saldo_awal" class="form-control">
			@else
			<input type="text" name="saldo_awal" id="saldo_awal" class="form-control">
			@endif
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
    dropdownParent: $("#perkiraan-form"),
	width: '100%'
});

$('#saldo_awal').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#id_induk').change(function(){
    var id_induk = $(this).val();
    var url = '{{ route("isiKolom", ":id_induk") }}';
    url = url.replace(':id_induk', id_induk);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        if(response != null){
            $('#kode').val(response.kode);
			$('#level').val(response.level);
        }}
    });
});

	$('#perkiraan-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
		
					id_induk : { validators: {
				        notEmpty: {
				          message: 'Kolom Induk tidak boleh kosong'
							}
						}
					}, 

					kode : { validators: {
				        notEmpty: {
				          message: 'Kolom Kode tidak boleh kosong'
							}
						}
					}, 

					level : { validators: {
				        notEmpty: {
				          message: 'Kolom Level tidak boleh kosong'
							}
						}
					}, 

					nama : { validators: {
				        notEmpty: {
				          message: 'Kolom Nama tidak boleh kosong'
							}
						}
					}, 

					fungsi : { validators: {
				        notEmpty: {
				          message: 'Kolom Fungsi tidak boleh kosong'
							}
						}
					}, 

					id_kategori : { validators: {
				        notEmpty: {
				          message: 'Kolom Kategori tidak boleh kosong'
							}
						}
					}, 

					saldo_awal : { validators: {
				        notEmpty: {
				          message: 'Kolom Saldo Awal tidak boleh kosong'
							}
						}
					}, 

					type : { validators: {
				        notEmpty: {
				          message: 'Kolom type tidak boleh kosong'
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
	
//});
</script>
