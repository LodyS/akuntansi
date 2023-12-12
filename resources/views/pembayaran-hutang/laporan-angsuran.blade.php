@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Laporan Pembayaran Hutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

<h3>Supplier : {{ $supplier->supplier }}</h3>
    <table class="table table-hover">

        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Hutang</th>
            <th>Angsuran</th>
            <th>Saldo</th>
        </tr>

        @php ($i=1)
        @foreach ($hutang as $laporan)
            <tr>
                <td>{{ $i }}</td>
                <td>{{date('d-m-Y', strtotime($laporan->waktu))}}</td>
                <td>Rp. {{ number_format($laporan->hutang)}}</td>
                <td>Rp. {{ number_format($laporan->angsuran)}}</td>
                <td>Rp. {{ number_format($laporan->saldo) }}</td>
            </tr>
            @php($i++)
        @endforeach
    </table>
   
        </div>
    </div>
</div>
<div class=" modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>
@endsection