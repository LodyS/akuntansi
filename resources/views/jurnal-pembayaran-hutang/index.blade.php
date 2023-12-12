@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Pembayaran Hutang, Tanggal :  {{date('d-m-Y', strtotime($waktu))}}</h1>
</div>
@include('layouts.inc.breadcrumb')
    <div class="page-content">
        <div class="panel">
            <div class="panel-body">

            <form action="{{ url('jurnal-pembayaran-hutang/rekapitulasi-pembayaran-hutang') }}" method="post" id="index">{{ @csrf_field() }}
	            <div class="form-group row">
		            <label class="col-md-3">Tanggal</label>
			            <div class="col-md-7">
			            <input type="date" name="tanggal" id="tanggal" class="form-control btn-round" value="{{date('Y-m-d')}}">
		            </div>
	            </div>

                <button type="submit" align="right" id="cari" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>
            </form><br/>

	        <form action="{{ url('jurnal-pembayaran-hutang/jurnal')}}" method="post">{{ @csrf_field() }}
                <input type="hidden" name="tanggal" value="{{ isset($waktu) ? $waktu : date('Y-m-d') }}">

                    <table class="table table-hover">
                        <tr>
                            <th>No</th>
                            <th>Bukti Pembayaran</th>
                            <th>Pemasok</th>
                            <th>Jenis Pembelian</th>
                            <th>Pembayaran</th>
                            <th>Bank</th>
                            <th>Perkiraan</th>
                        </tr>
                        @foreach ($rekapitulasi as $key => $rekap)
                        <tr>
                            <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                            <td>{{ $rekap->bukti_pembayaran }}</td>
                            <td>{{ $rekap->pemasok }}</td>
                            <td>{{ $rekap->jenis_pembelian }}</td>
                            <td>Rp. {{ number_format($rekap->pembayaran) }}</td>
                            <td>{{ $rekap->bank }}</td>
                            <td>{{ $rekap->perkiraan }}</td>
                        </tr>
                        @endforeach
                    </table>
                    {{ $rekapitulasi->appends(request()->toArray())->links() }}
                @if($hitung->id >0)
                <button type="submit" align="right" class="btn btn-danger btn-round"><i class="icon glyphicon glyphicon-list" aria-hidden="true"></i>Buat Jurnal</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
