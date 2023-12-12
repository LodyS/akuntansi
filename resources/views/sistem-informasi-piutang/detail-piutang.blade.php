@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Piutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <div class="form-group row">
		        <label class="col-md-3">Pelanggan</label>
			        <div class="col-md-7">
                    <input type="text" value="{{ optional($pelanggan)->nama }}" class="form-control btn-round" readonly>
                </div>
	        </div>

            <div class="form-group row">
		        <label class="col-md-3">Kode</label>
			        <div class="col-md-7">
                    <input type="text" value="{{ optional($pelanggan)->kode }}" class="form-control btn-round" readonly>
                </div>
	        </div>

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
                @foreach ($DetailPiutang as $piutang)
                <tr>
                    <td>{{ $i}}</td>
                    <td>@if ($piutang->keterangan==1)
                            Saldo Awal
                        @elseif ($piutang->keterangan==2)
                            Pendapatan Jasa
                        @else
                           {{ $piutang->keterangan }}
                        @endif
                    </td>
                        <td>{{date('d-m-Y', strtotime($piutang->tanggal))}}</td>
                        <td>{{ $piutang->umur_piutang}} Hari</td>
                        <td>{{ isset($piutang->jatuh_tempo) ? date('d-m-Y', strtotime($piutang->jatuh_tempo)) : '' }}</td>
                        <td>Rp. {{ number_format($piutang->piutang,2,",",".") }}</td>
                    @php ($total += $piutang->piutang)
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
