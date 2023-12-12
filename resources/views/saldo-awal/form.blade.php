@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Form Saldo Awal</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">{{ ($aksi == "create") ? 'Tambah' : 'Edit' }} Saldo Awal</h3><br/>
            <form action="{{ ($aksi == 'create') ? url('/simpan-saldo-awal') : url('/update-saldo-awal') }}" method="post">{{ @csrf_field() }}

                <input type="hidden" name="id" value="{{ isset($data) ? $data->id : '' }}">
                <input type="hidden" name="id_user" value="{{ $id_user }}">
                <input type="hidden" name="keterangan" value="Saldo Awal">

                    <div class="form-group row">
		                <label class="col-md-3">Tanggal</label>
			                <div class="col-md-7">
			                <input type="date" class="form-control" name="tanggal" value="{{ isset($data) ? $data->tanggal : date('Y-m-d') }}" >
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Rekening</label>
			                <div class="col-md-7">
			                <select name="id_perkiraan" id="id_perkiraan"  class="form-control select">
				            <option value="">Pilih Rekening</option>
				            @foreach ($perkiraan as $kira)
                            <option value="{{ $kira->id}}" {{ ($kira->id== $data->id_perkiraan)?'selected':''}}>{{ $kira->nama }}</option>
				            @endforeach
                            </select>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Code Cost Centre</label>
			                <div class="col-md-7">
			                    <select name="id_unit" id="id_unit"  class="form-control select">
				                <option value="">Pilih Code Cost Centre</option>
				                @foreach ($unit as $u)
                                <option value="{{ $u->id}}" {{ ($u->id== $data->id_unit)?'selected':''}}>{{ $u->code_cost_centre }} && {{ $u->nama }}</option>
				                @endforeach
                            </select>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Debet</label>
			                <div class="col-md-7">
			                <input type="text" name="debet" value="{{ isset($data) ? number_format($data->debet,2,",",".") : '' }}" class="form-control nominal">
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Kredit</label>
			                <div class="col-md-7">
			                <input type="text" name="kredit" value="{{ isset($data) ? number_format($data->kredit,2,",",".") : '' }}" class="form-control nominal">
		                </div>
	                </div>

                <button type="submit" align="right" class="btn btn-primary" id="simpan">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$('#id_perkiraan').select2({
 	width : '100%'
});

$('#id_unit').select2({
 	width : '100%'
});

$('.nominal').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).ready(function(){
	$('#setting').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {

		id_perkiraan : {
			validators: {
				notEmpty: {
				    message: 'Kolom Perkiraan tidak boleh kosong'
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
@endpush
