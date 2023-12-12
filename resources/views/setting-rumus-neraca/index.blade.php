@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Rumus Neraca</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <table class="table table-hover" id="laporan-neraca-saldo">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Jenis Neraca</th>
                    <th>Induk</th>
                    <th>Level</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($data as $key=> $rekap)
                <tr>
                    <td>{{ $key+ $data->firstItem() }}</td>
                    <td>{{ $rekap->kode }}</td>
                    <td>{{ $rekap->nama }}</td>
                    <td>{{ $rekap->induk }}</td>
                    <td>{{ $rekap->level }}</td>
                    <td>{{ $rekap->jenis }}</td>
                    <td><a href="detail/{{$rekap->id}}" class="btn btn-xs btn-outline-primary">Detail</a>
                </tr>
                @endforeach
            </table>
            {{ $data->links() }}
        </div>
    </div>
</div>

@endsection
