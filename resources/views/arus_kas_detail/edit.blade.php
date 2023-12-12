@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Arus Kas</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">Edit Setting Arus Kas</h3><br/>
        <form action="{{ url('/update-arus-kas-detail')  }}" method="post">{{ @csrf_field() }}

        	<input type="hidden" name="id" value="{{ isset($data) ? $data->id_arus_kas_detail : '' }}">
            <input type="hidden" name="id_arus_kas" value="{{ isset($data) ? $data->id_arus_kas : '' }}">

				<div class="form-group row">
					<label class="col-md-3">Kode</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->kode : ''}}" readonly>
					</div>
				</div>

                <div class="form-group row">
					<label class="col-md-3">Arus Kas</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->nama : ''}}" readonly>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Induk</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ isset($data) ? $data->induk : ''}}" readonly>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Rekening</label>
						<div class="col-md-7">
							<select name="id_perkiraan" id="id_perkiraan" class="form-control select" required>
        					<option value="">Pilih</option>
        					@foreach ($perkiraan as $kira)
        					<option value="{{ $kira->id }}" {{ ($kira->id== $data->id_perkiraan)?'selected':''}}>{{ $kira->kode_rekening }} - {{ $kira->nama }}</option>
        					@endforeach
        				</select>
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

</script>
@endpush
