@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Mutasi Penerimaan Kas</h1>
    	@include('layouts.inc.breadcrumb')
		<div class="page-header-actions">
    </div>
</div>

<div class="page-content">
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
            <div>
        </div>
    </div>
</header>

    <div class="panel-body">
       	@include('flash-message')
           <form action="{{ url('jurnal-mutasi-penerimaan-kas/pencarian')  }}" method="post" >{{ @csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-3">Tanggal :</label>
                      	<div class="col-md-7">
                      	<input type="date" name="tanggal" value="{{ date('Y-m-d')}}" class="form-control">
                  	</div>
                </div>

                <button type="submit" align="right" class="btn btn-primary">Cari</button>
            </form>

        			<table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                		<tr>
                    		<th>No</th>
                    		<th>Tanggal</th>
                    		<th>Kode</th>
                            <th>Keterangan</th>
                    		<th>Nominal</th>
                		</tr>
                        <form action="{{ url('jurnal-mutasi-penerimaan-kas/jurnal-umum') }}" method="post" id="create">{{ @csrf_field() }}
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        @if(isset($data))
                        @foreach ($data as $key =>$rekap)
                        <tr>
                            <td>{{ $key + $data->firstItem() }}</td>
                            <td>{{date('d-m-Y', strtotime($rekap->tanggal))}}</td>
                            <td>{{ $rekap->kode }}</td>
                            <td>{{ $rekap->keterangan }}</td>
                            <td>Rp. {{ number_format($rekap->nominal) }}</td>
                        </tr>
                        {{ $data->appends(request()->toArray())->links() }}
                    @endforeach

                    @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Rp. 0</td>
                        </tr>
                    @endif
        		</table>
            @if(isset($data))<button type="submit" align="right" class="btn btn-danger">Buat Jurnal</button>@endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

</script>
@endpush
