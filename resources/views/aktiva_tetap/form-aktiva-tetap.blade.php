@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Aktiva Tetap</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		<h4>{{ ($aktivaTetap->exists?'Edit':'Tambah').' Aktiva Tetap' }}</h4>
	      <form action="{{ (isset($cek)) ? url('/update-setting-perusahaan') : url('/simpan-setting-perusahaan')  }}" method="post">{{ @csrf_field() }} 
        <input type="hidden" name="id" value="{{ isset($cek) ? $cek->id : '' }}">
        
        <div class="form-group row">
		<label class="col-md-3">Kelompok Aktiva</label>
			<div class="col-md-7">
			<select name="id_kelompok_aktiva" class="form-control" id="id_kelompok_aktiva">
			<option value="">Pilih Kelompok Aktiva</option>
			@foreach ($KelompokAktiva as $kelompok)
			<option value="{{ $kelompok->id }}" {{ ($aktivaTetap->id_kelompok_aktiva==$kelompok->id )?'selected':''}}>{{ $kelompok->nama }}</option>
			@endforeach
			</select>	
		</div>
	</div>

	<!--<div class="form-group row">
		<label class="col-md-3">Kode</label>
			<div class="col-md-7">
			@if ($lastCode == null && $kode == null)
			<input name="kode" id="kode" value="M-1" class="form-control" readonly>
			@elseif (isset($lastCode) && $kode == null)
			<input name="kode" id="kode" value="{{ $lastCode->lastCode }}" class="form-control" readonly>
			@elseif (isset($aktivaTetap))
			<input type="text" name="kode" id="kode" class="form-control" value="{{ $aktivaTetap->kode }}" readonly>
			@endif
		</div>
	</div>-->

	<div class="form-group row">
		<label class="col-md-3">Kode</label>
			<div class="col-md-7">
			<input name="kode" id="kode" value="{{ isset($aktivaTetap) ? $aktivaTetap->kode : '' }}" class="form-control" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Nama</label>
			<div class="col-md-7">
			<input name="nama" id="nama" value="{{ isset($aktivaTetap) ? $aktivaTetap->nama : '' }}" class="form-control" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">No Seri</label>
			<div class="col-md-7">
			<input name="no_seri" id="no_seri" value="{{ isset($aktivaTetap) ? $aktivaTetap->no_seri : '' }}" class="form-control" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Lokasi</label>
			<div class="col-md-7">
			<input name="lokasi" id="lokasi" value="{{ isset($aktivaTetap) ? $aktivaTetap->lokasi : '' }}" class="form-control" readonly>
		</div>
	</div>
													      
	<div class="form-group row">
		<label class="col-md-3">Departemen</label>
			<div class="col-md-7">
			<select name="id_unit" class="form-control">
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
			<select name="id_metode_penyusutan" class="form-control">
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
			<input type="text" name="harga_perolehan" class="form-control" id="harga_perolehan" 
			value="{{ isset($aktivaTetap) ? $aktivaTetap->harga_perolehan : '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Nilai Residu</label>
			<div class="col-md-7">
			<input type="text" name="nilai_residu" class="form-control" id="nilai_residu" value="{{ isset($aktivaTetap) ? $aktivaTetap->nilai_residu : '' }}">	
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Masa Pakai</label>
			<div class="col-md-7">
			<input type="text" name="umur_ekonomis" class="form-control" id="masa_pakai" value="{{ isset($aktivaTetap) ? $aktivaTetap->umur_ekonomis : '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Tarif</label>
			<div class="col-md-7">
			<input type="text" name="tarif" class="form-control" id="tarif" value="{{ isset($aktivaTetap) ? $aktivaTetap->tarif : '' }}" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Penyesuaian</label>
			<div class="col-md-7">
			<input type="text" name="penyesuaian" id="penyesuaian" class="form-control" value="{{ isset($aktivaTetap) ? $aktivaTetap->penyesuaian : '' }}">	
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
    dropdownParent: $("#aktiva-tetap-form"),
	width: '100%'
});

$('#harga_perolehan').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

$('#nilai_residu').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

$('#tarif').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

$('#penyesuaian').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

$(document).on('change', "#masa_pakai", "#harga_perolehan, #nilai_residu",  function() {
	var val1 = $("#harga_perolehan").val()
    var val2 = $("#nilai_residu").val()
	var val3 = $("#masa_pakai").val()

	var harga_perolehan = Number(val1.replace(/[^0-9.-]+/g,""));
	var nilai_residu = Number(val2.replace(/[^0-9.-]+/g,""));

    var result = (harga_perolehan - nilai_residu) / val3
    $("#tarif").val(result)
});

$('#id_kelompok_aktiva').change(function(){
    var id_kelompok_aktiva = $(this).val();
    var url = '{{ route("isiKode", ":id_kelompok_aktiva") }}';
    url = url.replace(':id_kelompok_aktiva', id_kelompok_aktiva);

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
	  /*fields: {
	id_user : { validators: {
				        notEmpty: {
				          message: 'Kolom id_user tidak boleh kosong'
							}
						}
					},kode : { validators: {
				        notEmpty: {
				          message: 'Kolom kode tidak boleh kosong'
							}
						}
					},nama : { validators: {
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
					},penyusutan : { validators: {
				        notEmpty: {
				          message: 'Kolom penyusutan tidak boleh kosong'
							}
						}
					},id_metode_penyusutan : { validators: {
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
					},tanggal_pemakaian : { validators: {
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
}, */
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
@endsection