@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Sistem Informasi Hutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            
				<table class="table table-hover">

                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Saldo Hutang</th>
                        <th>Rekening Kontrol</th>
                        <th>Mutasi</th>
                        <th>Detail</th>
                        <th>Saldo Awal</th>
                    </tr>
                    @php ($i=1)
                    @foreach ($InstansiRelasi as $pemasok)
                    <tr>
                       <td>{{ $i}}</td>
                       <td>{{ $pemasok->pemasok }}</td>
                       <td>Rp. {{ number_format($pemasok->saldo_hutang,2,",",".") }}</td>
                       <td>{{ $pemasok->rekening_kontrol }}</td>
                      </td>
                       <td><a class="btn btn-success btn-xs" href="sistem-informasi-hutang/mutasi-hutang/{{ $pemasok->id }}" 
                       style="color:white; font-family:Arial">Mutasi</a></td>
                       <td><a class="btn btn-success btn-xs" href="sistem-informasi-hutang/detail-hutang/{{ $pemasok->id }}" 
                       style="color:white; font-family:Arial">Detail</a></td>
                       <td><a class="btn btn-success btn-xs" href="sistem-informasi-hutang/tambah-saldo/{{ $pemasok->id }}" 
                       style="color:white; font-family:Arial">Tambah</a></td>
                    </tr>
                    @php($i++)
                    @endforeach
                </table>
            {{ $InstansiRelasi->links() }}
        </div>
    </div>
</div>
@endsection