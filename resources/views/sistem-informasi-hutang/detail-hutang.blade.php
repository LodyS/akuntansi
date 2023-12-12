@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Detail Hutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            Pelanggan : {{ $instansiRelasi->nama }}<br/>
            Kode      : {{ $instansiRelasi->kode }}<br/>

            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Keterangan</th>
                    <th>Tanggal Transaksi</th>
                    <th>Umur Piutang</th>
                    <th>Jatuh Tempo</th>
                    <th>Piutang</th>
                </tr>
                @php ($total=0)
                    @php ($i=1)
                @foreach ($detailHutang as $hutang)
                <tr>
                    <td>{{ $i}}</td>
                    <td>@if ($hutang->keterangan==1) 
                            Saldo Awal
                        @elseif ($hutang->keterangan==2) 
                            Pembelian
                        @else
                           {{ $hutang->keterangan }} 
                        @endif
                    </td>
                        <td>{{date('d-M-Y', strtotime($hutang->tanggal))}}</td>
                        <td>{{ $hutang->umur_piutang}} Hari</td>
                        <td>{{date('d-M-Y', strtotime($hutang->jatuh_tempo))}}</td>
                        <td>Rp. {{ number_format($hutang->hutang,2,",",".") }}</td>
                    @php ($total += $hutang->hutang)
                </tr>
                @php($i++)
                @endforeach
	        </table>
                <div align="right">
                    Total : Rp. {{ number_format($total,2,",",".") }}
                </div>
        </div>
    </div>
</div>
@endsection