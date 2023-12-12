@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Mutasi Hutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            Pemasok   : {{ $instansi->nama }}<br/>
            Kode      : {{ $instansi->kode }}<br/>

            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    <th>Saldo</th>
                </tr>
                @php ($total=0)
                    @php ($i=1)
                @foreach ($Mutasi as $mutasi)
                <tr>
                    <td>{{ $i}}</td>
                        <td>{{date('d-m-Y', strtotime($mutasi->tanggal))}}</td>
                        <td>{{$mutasi->keterangan}}</td>
                        <td>Rp. {{ number_format($mutasi->debet,2,",",".")}}</td>
                        <td>Rp. {{ number_format($mutasi->kredit,2,",",".")}}</td>
                        <td>Rp. {{ number_format($mutasi->saldo,2,",",".")}}</td>
                </tr>
                @php($i++)
                @endforeach
	        </table>
        </div>
    </div>
</div>
@endsection