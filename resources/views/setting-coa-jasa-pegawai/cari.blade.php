@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Setting COA Jasa Pegawai</h1>
    @include('layouts.inc.breadcrumb')
</div>

<div class="page-content">   
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
                </div>
            </div>
        </header>
        @include('flash-message')
        <div class="panel-body">
        <form action="{{ url('setting-coa-jasa-pegawai/cari')}}" method="post">{{ @csrf_field() }}
            <div class="form-group row">
			    <label class="col-sm-1">Unit</label>
				    <div class="col-md-4">
				    <select name="id_unit" id="id_unit" class="form-control" required>
                    <option value="">Pilih Unit</option>
                    @foreach ($yunit as $bang)
                    <option value="{{ $bang->id }}">{{ $bang->nama }}</option>
                    @endforeach
                    </select>
			    </div>
		    </div>
            <button type="submit" id="submit" align="right" class="btn btn-outline-primary">Cari</button>  

        <a href="tambah" class="btn btn-info">Tambah</a>
            <table class="table table-hover dataTable table-striped w-full" id="setting">
                <tr>
                    <th>Unit</th>
                    <th>Detail</th>
                </tr>
               
                    <tr>
                        <td> {{$unit->nama}} </td>
                        <td><a href="detail/{{$unit->id}}" class="btn btn-xs btn-outline-success">Setting COA</a></td>
                    </tr>
                </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function() {
    
    $("#id_unit").select2({
        width : '100%'
    });
    
});
</script>
@endpush