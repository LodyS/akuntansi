<div class="modal-dialog modal-simple">
	{{ Form::model($jenisPembelian,array('route' => array((!$jenisPembelian->exists) ? 'jenis-pembelian.store':'jenis-pembelian.update',$jenisPembelian->pk()),
	    'class'=>'modal-content','id'=>'jenis-pembelian-form','method'=>(!$jenisPembelian->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    	<span aria-hidden="true">×</span>
    	</button>
		<h4 class="modal-title" id="formModalLabel">{{ ($jenisPembelian->exists?'Edit':'Tambah').' Jenis Pembelian' }}</h4>
    </div>
<div class="modal-body">

{!! App\Console\Commands\Generator\Form::input('nama','text')->model($jenisPembelian)->show() !!}
																		
	<div class="form-group row">
		<label class="col-md-3">Perkiraan Diskon</label>
			<div class="col-md-7">
			<select name="id_perkiraan_diskon" class="form-control select" id="id_perkiraan_diskon">
				<option value="">Pilih Perkiraan</option>
				@foreach ($Perkiraan as $perkiraan)
					@if (isset($jenisPembelian->id_perkiraan_diskon) && !empty($jenisPembelian->id_perkiraan_diskon))
						<option value="{{ $perkiraan->id }}" {{ ($jenisPembelian->id_perkiraan_diskon==$perkiraan->id )?'selected':''}}>{{ $perkiraan->nama }}</option>
					@else
						<option value="{{ $perkiraan->id }}">{{ $perkiraan->nama }}</option>
					@endif
				@endforeach
			</select>	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Perkiraan Pajak</label>
			<div class="col-md-7">
			<select name="id_perkiraan_pajak" class="form-control select" id="id_perkiraan_pajak">
				<option value="">Pilih Perkiraan</option>
				@foreach ($Perkiraan as $perkiraan)
					@if (isset($jenisPembelian->id_perkiraan_pajak) && !empty($jenisPembelian->id_perkiraan_pajak))
						<option value="{{ $perkiraan->id }}" {{ ($jenisPembelian->id_perkiraan_pajak==$perkiraan->id )?'selected':''}}>{{ $perkiraan->nama }}</option>
					@else
						<option value="{{ $perkiraan->id }}">{{ $perkiraan->nama }}</option>
					@endif
				@endforeach
			</select>	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Perkiraan Materai</label>
			<div class="col-md-7">
			<select name="id_perkiraan_materai" class="form-control select" id="id_perkiraan_materai">
				<option value="">Pilih Perkiraan</option>
				@foreach ($Perkiraan as $perkiraan)
					@if (isset($jenisPembelian->id_perkiraan_materai) && !empty($jenisPembelian->id_perkiraan_materai))
						<option value="{{ $perkiraan->id }}" {{ ($jenisPembelian->id_perkiraan_materai==$perkiraan->id )?'selected':''}}>{{ $perkiraan->nama }}</option>
					@else
						<option value="{{ $perkiraan->id }}">{{ $perkiraan->nama }}</option>
					@endif
				@endforeach
			</select>	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Perkiraan Pembelian</label>
			<div class="col-md-7">
			<select name="id_perkiraan_pembelian" class="form-control select" id="id_perkiraan_pembelian">
				<option value="">Pilih Perkiraan</option>
				@foreach ($Perkiraan as $perkiraan)
					@if (isset($jenisPembelian->id_perkiraan_pembelian) && !empty($jenisPembelian->id_perkiraan_pembelian))
						<option value="{{ $perkiraan->id }}" {{ ($jenisPembelian->id_perkiraan_pembelian==$perkiraan->id )?'selected':''}}>{{ $perkiraan->nama }}</option>
					@else
						<option value="{{ $perkiraan->id }}">{{ $perkiraan->nama }}</option>
					@endif
				@endforeach
			</select>	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Perkiraan Hutang</label>
			<div class="col-md-7">
			<select name="id_perkiraan_hutang" class="form-control select" id="id_perkiraan_hutang">
				<option value="">Pilih Perkiraan</option>
				@foreach ($Perkiraan as $perkiraan)
					@if (isset($jenisPembelian->id_perkiraan_hutang) && !empty($jenisPembelian->id_perkiraan_hutang))
						<option value="{{ $perkiraan->id }}" {{ ($jenisPembelian->id_perkiraan_hutang==$perkiraan->id )?'selected':''}}>{{ $perkiraan->nama }}</option>
					@else
						<option value="{{ $perkiraan->id }}">{{ $perkiraan->nama }}</option>
					@endif
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
    dropdownParent: $("#jenis-pembelian-form"),
	width: '100%'
});

$(document).ready(function(){
	$('#jenis-pembelian-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama : { validators: {
				        notEmpty: {
				          message: 'Kolom nama tidak boleh kosong'
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
