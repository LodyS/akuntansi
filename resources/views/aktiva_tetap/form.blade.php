<div class="modal-dialog modal-simple">

		{{ Form::model($aktivaTetap,array('route' => array((!$aktivaTetap->exists) ? 'aktiva-tetap.store':'aktiva-tetap.update',$aktivaTetap->pk()),
	        'class'=>'modal-content','id'=>'aktiva-tetap-form','method'=>(!$aktivaTetap->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($aktivaTetap->exists?'Edit':'Tambah').' Aktiva Tetap' }}</h4>
    </div>
    <div class="modal-body">
	<b>General</b>
	<hr/>
	<input type="hidden" name="id_user" value="{{ Auth::user()->id }}">


	<div class="form-group row">
		<label class="col-md-3">Kelompok Aktiva</label>
			<div class="col-md-7">
			<select name="id_kelompok_aktiva" class="form-control select" id="id_kelompok_aktiva">
			<option value="">Pilih Kelompok Aktiva</option>
			@foreach ($KelompokAktiva as $kelompok)
			<option value="{{ $kelompok->id }}" {{ ($aktivaTetap->id_kelompok_aktiva==$kelompok->id )?'selected':''}}>{{ $kelompok->nama }}</option>
			@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Kode</label>
			<div class="col-md-7">
			<input name="kode" id="kode" value="{{ isset($aktivaTetap) ? $aktivaTetap->kode : '' }}" class="form-control" readonly>
		</div>
	</div>

	{!! App\Console\Commands\Generator\Form::input('nama','text')->model($aktivaTetap)->show() !!}
	{!! App\Console\Commands\Generator\Form::input('no_seri','text')->model($aktivaTetap)->show() !!}
	{!! App\Console\Commands\Generator\Form::input('lokasi','text')->model($aktivaTetap)->show() !!}

	<div class="form-group row">
		<label class="col-md-3">Departemen</label>
			<div class="col-md-7">
			<select name="id_unit" class="form-control select">
			<option value="">Pilih Departemen</option>
			@foreach ($Unit as $unit)
			<option value="{{ $unit->id }}" {{ ($aktivaTetap->id_unit==$unit->id )?'selected':''}}>{{ $unit->nama }}</option>
			@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Tanggal Pembelian</label>
			<div class="col-md-7">
			<input type="date" name="tanggal_pembelian" class="form-control" id="tanggal_pembelian"
			value="{{ isset($aktivaTetap) ? $aktivaTetap->tanggal_pembelian : date('Y-m-d') }}" required>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Mulai dipakai</label>
			<div class="col-md-7">
			<input type="date" name="tanggal_pemakaian" class="form-control" id="mulai_pakai"
			value="{{ isset($aktivaTetap) ? $aktivaTetap->tanggal_pemakaian : date('Y-m-d') }}" required>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Masa Depresiasi</label>
			<div class="col-md-7">
			@if(isset($aktivaTetap))
			<input type="text" name="depreciated" class="form-control" id="sudah_didepresiasi" value="{{ $aktivaTetap->depreciated }}">
			@else
			<input type="text" name="depreciated" class="form-control" id="sudah_didepresiasi">
			@endif
		</div>
	</div>

<b>Penyusutan</b><hr/>

	<div class="form-group row">
		<label class="col-md-3">Pilih Metode Penyusutan</label>
			<div class="col-md-7">
			<select name="id_metode_penyusutan" class="form-control select">
			<option value="">Metode Penyusutan</option>
			@foreach ($MetodePenyusutan as $metode)
			<option value="{{ $metode->id }}" {{ ($aktivaTetap->id_metode_penyusutan == $metode->id )?'selected':''}}>{{ $metode->nama }}</option>
			@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Harga Perolehan</label>
			<div class="col-md-7">
			<input type="text" name="harga_perolehan" class="form-control nominal" id="harga_perolehan"
			value="{{ isset($aktivaTetap) ? number_format($aktivaTetap->harga_perolehan,2,",",".") : '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Nilai Residu</label>
			<div class="col-md-7">
			<input type="text" name="nilai_residu" class="form-control nominal" id="nilai_residu"
			value="{{ isset($aktivaTetap) ? number_format($aktivaTetap->nilai_residu,2,",",".") : '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Masa Pakai</label>
			<div class="col-md-7">
			<input type="text" name="umur_ekonomis" class="form-control" id="masa_pakai"
			value="{{ isset($aktivaTetap) ? $aktivaTetap->umur_ekonomis : '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Tarif</label>
			<div class="col-md-7">
			<input type="text" name="tarif" class="form-control nominal" id="tarif"
			value="{{ isset($aktivaTetap) ? number_format($aktivaTetap->tarif,2,",",".") : '' }}" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Penyesuaian</label>
			<div class="col-md-7">
			<input type="text" name="penyesuaian" id="penyesuaian" class="form-control nominal"
			value="{{ isset($aktivaTetap) ? number_format($aktivaTetap->penyesuaian,2,",",".") : '' }}">
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
    theme: "bootstrap-5",
	width: '100%'
});

function formatRupiah(number) {
  return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('.nominal').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).on('keyup change', "#masa_pakai", "#harga_perolehan, #nilai_residu",  function() {
	var val1 = $("#harga_perolehan").val()
    var val2 = $("#nilai_residu").val()
	var val3 = $("#masa_pakai").val()

	var harga_perolehan = Number(val1.replace(/[^0-9]+/g,""));
	var nilai_residu = Number(val2.replace(/[^0-9]+/g,""));

    var result = (harga_perolehan - nilai_residu) / val3
    $("#tarif").val(formatRupiah(result))
});

$('#id_kelompok_aktiva').change(function(){
    var id = $(this).val();
    var url = '{{ route("isiKodeKelompokAktiva", ":id") }}';
    url = url.replace(':id', id);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        if(response != null){
            $('#kode').val(response.kode);
		}}
    });
});

$(document).ready(function(){
	$('#aktiva-tetap-form').formValidation({
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
					},id_kelompok_aktiva : { validators: {
				        notEmpty: {
				          message: 'Kolom id_kelompok_aktiva tidak boleh kosong'
							}
						}
					},id_unit : { validators: {
				        notEmpty: {
				          message: 'Kolom id_unit tidak boleh kosong'
							}
						}
					}, id_metode_penyusutan : { validators: {
				        notEmpty: {
				          message: 'Kolom id_metode_penyusutan tidak boleh kosong'
							}
						}
					},lokasi : { validators: {
				        notEmpty: {
				          message: 'Kolom lokasi tidak boleh kosong'
							}
						}
					},no_seri : { validators: {
				        notEmpty: {
				          message: 'Kolom no_seri tidak boleh kosong'
							}
						}
					},mulai_pakai : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal_pemakaian tidak boleh kosong'
							}
						}
					},tanggal_selesai_pakai : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal_selesai_pakai tidak boleh kosong'
							}
						}
					},tanggal_pembelian : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal_pembelian tidak boleh kosong'
							}
						}
					},nilai_residu : { validators: {
				        notEmpty: {
				          message: 'Kolom nilai_residu tidak boleh kosong'
							}
						}
					},umur_ekonomis : { validators: {
				        notEmpty: {
				          message: 'Kolom umur_ekonomis tidak boleh kosong'
							}
						}
					},depreciated : { validators: {
				        notEmpty: {
				          message: 'Kolom depreciated tidak boleh kosong'
							}
						}
					},harga_perolehan : { validators: {
				        notEmpty: {
				          message: 'Kolom harga_perolehan tidak boleh kosong'
							}
						}
					},penyesuaian : { validators: {
				        notEmpty: {
				          message: 'Kolom penyesuaian tidak boleh kosong'
							}
						}
					},tarif : { validators: {
				        notEmpty: {
				          message: 'Kolom tarif tidak boleh kosong'
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
