@extends('layouts.app')

@section('content')
   
<div class="page-header">
    <h1 class="page-title">Setting Aplikasi</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
        <h3 align="center">{{ ($cek == null) ? 'Tambah Setting Aplikasi' : 'Edit Setting Aplikasi' }}</h3><br/>
	    <form action="{{ (isset($cek)) ? url('/update-setting-aplikasi') : url('/simpan-setting-aplikasi')  }}" method="post"
		enctype="multipart/form-data">{{ @csrf_field() }} 
        <input type="hidden" name="id" value="{{ isset($cek) ? $cek->id : '' }}">
        
        <div class="form-group row">
		    <label class="col-md-3">Nama Aplikasi</label>
			    <div class="col-md-7">
			    <input type="text" name="nama" class="form-control" value="{{ isset($cek) ? $cek->nama : ''}}" required>
		      </div>
	      </div>

        <div class="form-group row">
		    <label class="col-md-3">Deskripsi</label>
			    <div class="col-md-7">
			    <input type="text" name="deskripsi" class="form-control" value="{{ isset($cek) ? $cek->deskripsi : ''}}" required>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Logo</label>
			    <div class="col-md-7">
			    <input type="file" name="logo" class="form-control-file" value="{{ isset($cek) ? $cek->logo : '' }}">
				@if(isset($cek))
				<input type="hidden" name="cek_logo" class="form-control" value="{{ $cek->logo  }}" readonly>
				<img src="{{ url('logo/'. $cek->logo) }}" class="img-thumbnail" width="150"><br/>
				@endif
				File harus berbentuk JPG atau PNG
		    </div>
	    </div>

        <div class="form-group row">
			<label class="col-md-3">Base URL</label>
			    <div class="col-md-7">
			    <input type="text" name="base_url" class="form-control" value="{{ isset($cek) ? $cek->base_url : '' }}" required>
		    </div>
	    </div>

		<div class="form-group row">
			<label class="col-md-3">Status</label>
			    <div class="col-md-7">
			    <input type="checkbox" name="flag_morbis" value="Y" {{ (isset($cek) && $cek->flag_morbis == 'Y') ? 'checked' : '' }}>&nbsp;Aktif
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Version</label>
			    <div class="col-md-7">
			    <input type="text" name="version" class="form-control" value="{{ isset($cek) ? $cek->version : '' }}" required>
		    </div>
	    </div>

              <button type="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

</script>
@endpush
