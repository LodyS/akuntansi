@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Setting COA Invoice</h1>
        <div class="page-header-actions">
    </div>
</div>


<div class="page-content">
    <div class="panel">


<form action="/setting-coa-invoice/{{ $setting_coa->id }}" method="POST">{{ @csrf_field() }}
<input name="_method" type="hidden" value="PUT">

<div class="panel-body">

    <div class="form-group row">
		<label class="col-md-3">Nama</label>
			<div class="col-md-7">
            <input type="text" value="{{ $setting_coa->nama }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">COA</label>
			<div class="col-md-7">
                <select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                <option value="">Pilih Perkiraan</option>
                @foreach ($perkiraan as $Perkiraan)
                <option value="{{ $Perkiraan->id }}" {{ ($setting_coa->id_perkiraan==$Perkiraan->id )?'selected':''}}>{{ $Perkiraan->nama }}</option>
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

    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
$(document).ready(function(){

$("#id_perkiraan").select2({
    width : '100%'
    });
});
</script>
@endpush
