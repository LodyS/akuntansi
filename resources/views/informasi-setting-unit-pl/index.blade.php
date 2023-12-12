@extends('layouts.app')

@section('content')

<style>
table
{
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td
{
  text-align: left;
  padding: 8px;
}

tr:nth-child(even)
{
    background-color: #f2f2f2
}
</style>

<div class="page-header">
    <h1 class="page-title">Informasi Setting PL Unit</h1>
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

    <div class="panel-body">

        <form action="{{ url('informasi-setting-unit-pl/pencarian')}}" method="POST">{{ @csrf_field() }}

            <div class="form-group row">
			    <label class="col-md-3">Nama Unit</label>
				    <div class="col-md-7">
				        <select name="nama_unit" class="form-control select">
            	        <option value="">Pilih</option>
				        @foreach ($unit as $kode => $u)
				        <option value="{{ $kode }}">{{ $kode }}</option>
				        @endforeach
          		    </select>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Code Cost Centre</label>
				    <div class="col-md-7">
				        <select name="code_cost_centre" class="form-control select">
            	        <option value="">Pilih</option>
				        @foreach ($unit as $nama => $u)
				        <option value="{{ $u }}">{{ $u }}</option>
				        @endforeach
          		    </select>
			    </div>
		    </div>

            <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button><br/><br/>

                <div style="overflow-x:auto;">
                    <table class="table table-hover bb" id="worksheet">
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Code Cost Centre</th>
                            <th>P/L</th>
                            <th>Flag P/L</th>
                            <th>Aksi</th>
                        </tr>

                        @foreach ($data as $key=> $d)
                        <tr>
                            <td>{{ $key + $data->firstItem() }}</td>
                            <td>{{ $d->unit }}</td>
                            <td>{{ $d->code_cost_centre }}</td>
                            <td>{{ $d->p_l }}</td>
                            <td>{{ $d->flag_pl }}</td>
                            <td>@if($d->flag_pl == 'No')
                            <a href="form/{{ $d->id }}" class="btn btn-sm btn-icon btn-danger btn-round edit">
                            <i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i>&nbsp;Tambah</button>
                            @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                {{ $data->appends(request()->toArray())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('.select').select2({
    theme : 'bootstrap-5',
    width : '100%',
});
</script>
@endpush
