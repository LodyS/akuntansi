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
    <h1 class="page-title">Informasi Setting Coa Neraca</h1>
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

        <form action="{{ url('informasi-setting-coa-neraca/pencarian')}}" method="POST">{{ @csrf_field() }}

            <div class="form-group row">
			    <label class="col-md-3">Rekening</label>
				    <div class="col-md-7">
				        <select name="nama_perkiraan" id="id_perkiraan" class="form-control select">
            	        <option value="">Pilih</option>
				        @foreach ($perkiraan as $u)
				        <option value="{{ $u->nama }}">{{ $u->nama }}</option>
				        @endforeach
          		    </select>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Kode Rekening</label>
				    <div class="col-md-7">
				    <select name="kode_rekening" id="kode_rekening" class="form-control select">
            	        <option value="">Pilih</option>
				        @foreach ($perkiraan as $u)
				        <option value="{{ $u->kode_rekening }}">{{ $u->kode_rekening }}</option>
				        @endforeach
          		    </select>
			    </div>
		    </div>

        <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button><br/><br/>

        <div style="overflow-x:auto;">
            <table class="table table-hover bb" id="worksheet">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rekening</th>
                        <th>Kode</th>
                        <th>P/L</th>
                        <th>Flag P/L</th>
                        <th>Aksi</th>
                    </tr>

                    @foreach ($data as $key=> $d)
                    <tr>
                        <td>{{ $key + $data->firstItem() }}</td>
                        <td>{{ $d->rekening }}</td>
                        <td>{{ $d->kode_rekening }}</td>
                        <td>{{ $d->neraca }}</td>
                        <td>{{ $d->flag_pl }}</td>
                        <td>@if($d->flag_pl == 'No')
                            <a href="form/{{ $d->id }}" class="btn btn-sm btn-icon btn-danger btn-round edit"  data-original-title="Tambah" data-toggle="tooltip">
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
    width:'100%'
});

</script>
@endpush
