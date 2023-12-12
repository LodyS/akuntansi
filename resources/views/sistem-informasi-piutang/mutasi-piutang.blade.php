@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Mutasi Piutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <div class="form-group row">
		        <label class="col-md-3">Pelanggan</label>
			        <div class="col-md-7">
                    <input type="text" value="{{ isset($pelanggan->nama) ? $pelanggan->nama : '' }}" class="form-control btn-round" readonly>
                </div>
	        </div>

            <div class="form-group row">
		        <label class="col-md-3">Kode</label>
			        <div class="col-md-7">
                    <input type="text" value="{{ isset($pelanggan->kode) ? $pelanggan->kode : '' }}" class="form-control btn-round" readonly>
                </div>
	        </div>

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
                @foreach ($mutasi as $piutang)
                <tr>
                    <td>{{ $i}}</td>
                        <td>{{date('d-m-Y', strtotime($piutang->tanggal))}}</td>
                        <td>{{$piutang->keterangan}}</td>
                        <td>Rp. {{ number_format($piutang->debet,2,",",".")}}</td>
                        <td>Rp. {{ number_format($piutang->kredit,2,",",".")}}</td>
                        <td>Rp. {{ number_format($piutang->saldo,2,",",".")}}</td>
                </tr>
                @php($i++)
                @endforeach
	        </table>
        </div>
    </div>
</div>
@endsection
