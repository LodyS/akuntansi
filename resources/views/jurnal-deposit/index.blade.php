@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Deposit</h1>
</div>

  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <form action="{{ url('jurnal-deposit/rekapitulasi')}}" method="post">{{ @csrf_field() }}

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" class="form-control" value="{{date('Y-m-d')}}" required>
		</div>
	</div>

        <button type="submit" align="right" class="btn btn-primary">Cari</button><br/><br/>

    <table class="table table-hover">

        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Deposit</th>
            <th>Aksi</th>
        </tr>

        @foreach ($rekapitulasi as $key => $rekap)
            <tr>
                <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                <td>{{ $rekap->nama_pasien }}</td>
                <td>{{ date('d-m-Y', strtotime($rekap->waktu)) }}</td>
                <td>Rp. {{ number_format($rekap->kredit) }}</td>
                <td><a href="jurnal-umum/{{ $rekap->id }}" class="btn btn-success">Buat Jurnal</a>
            </tr>
        @endforeach
    </table>
    {{ $rekapitulasi->appends(request()->toArray())->links() }}

        </div>
    </div>
</div>
@endsection
