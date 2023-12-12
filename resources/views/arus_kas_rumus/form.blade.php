@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Rumus Arus Kas</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">Tambah Setting Rumus Arus Kas</h3><br/>
	    <form action="{{ route('arus-kas-rumus.store') }}" method="post">{{ @csrf_field() }}

        	<input type="hidden" name="id_rumus_arus_kas" value="{{ optional($data)->id }}">
            <input type="hidden" name="id" value="{{ $id }}">

				<div class="form-group row">
					<label class="col-md-3">Kode</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ optional($data)->kode }}" readonly>
					</div>
				</div>

                <div class="form-group row">
					<label class="col-md-3">Jenis Arus Kas</label>
						<div class="col-md-7">
						<input class="form-control" value="{{ optional($data)->nama }}" readonly>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Transaksi</label>
						<div class="col-md-7">
							<select name="id_transaksi_jurnal" id="id_rumus" class="form-control select" required>
        					<option value="">Pilih</option>
        					@foreach ($transaksi as $arus)
        					<option value="{{ $arus->id }}">{{ $arus->nama }}</option>
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
$('#id_rumus').select2({
 	width : '100%'
});

</script>
@endpush
