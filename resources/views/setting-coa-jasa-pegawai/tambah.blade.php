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
     
        <form action="{{ url('/simpan-setting-coa-jasa-pegawai') }}" method="post" id="create">{{ @csrf_field() }} 

        <div class="panel-body">
            <div class="form-group row">
			    <label class="col-sm-1">Unit</label>
				    <div class="col-md-4">
				    <select name="id_unit" id="id_unit" class="form-control" required>
                    <option value="">Pilih Unit</option>
                    @foreach ($unit as $bang)
                    <option value="{{ $bang->id }}">{{ $bang->nama }}</option>
                    @endforeach
                    </select>
			    </div>
		    </div>

        @include('flash-message')
            <table class="table table-hover table-striped w-full" id="setting">
                <tr>
                    <th>No</th>
                    <th>Komponen</th>
                    <th>Rekening</th>
                </tr>
                    @php ($i=1)
                    @foreach($jasaPegawai as $key => $jasa)
                    <tr> 
                        <input type="hidden" name="id_jasa_pegawai[]" value="{{ $jasa->id }}">
                        <td>{{ $i }}</td>
                        <td> {{$jasa->nama}} </td>
                        <td>
                            <select name="id_perkiraan[]" class="form-control select" required>
                                <option value="">Pilih Perkiraan</option>
                                @foreach ($perkiraan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @php($i++)
                    @endforeach
                </table>
            <button type="submit" align="right" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(".select").select2({
	width: '100%'
});

$("#id_unit").select2({
	width: '100%'
});

</script>
@endpush