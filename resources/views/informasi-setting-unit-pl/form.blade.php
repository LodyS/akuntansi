@extends('layouts.app')

@section('content')

<style>
.form-control{
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}
</style>

<div class="page-header">
    <h1 class="page-title">Tambah Surplus Defisit Unit</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center">Tambah Surplus Defisit Unit</h3><br/>
	            <form action="{{ url('/simpan-informasi-setting-unit-pl')  }}" method="post">{{ @csrf_field() }}

                    <div class="form-group row">
		                <label class="col-md-3">Unit</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control" value="{{ $data->nama }}" readonly>
                            <input type="hidden" name="id_unit" class="form-control round" value="{{ $data->id }}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Surplus Defisit</label>
				            <div class="col-md-7">
				            <select name="id_surplus_defisit_detail" id="id_surplus_defisit_detail" class="form-control select">
            	                <option value="">Pilih</option>
				                @foreach ($surplusDefisitDetail as $id=>$surplus)
				                <option value="{{ $id }}">{{ $surplus }}</option>
				                @endforeach
          		            </select>
			            </div>
		            </div>

                <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-floppy-saved"></i></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('#id_surplus_defisit_detail').select2({
        width: '100%',
        theme: 'bootstrap4'
    });

</script>
@endpush
