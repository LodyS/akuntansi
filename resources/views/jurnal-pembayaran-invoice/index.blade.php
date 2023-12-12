@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Rekapitulasi Pembayaran Invoice</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ url('jurnal-pembayaran-invoice/rekapitulasi')}}" method="post">{{ @csrf_field() }}

                <div class="form-group row">
		            <label class="col-md-3">Tanggal</label>
			            <div class="col-md-7">
			            <input type="date" name="tanggal" class="form-control btn-round" value="{{date('Y-m-d')}}" required>
		            </div>
	            </div>

                <div>
                    <button type="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button><br/><br/>
                </div>
            </form>

                    <table class="table table-hover">
                        <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Number</th>
                        <th>Pelanggan</th>
                        <th>Total Piutang</th>
                        <th>PPH 23</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>

                    @foreach ($rekapitulasi as $key => $rekap)
                        <tr>
                            <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                            <td>{{ date('d-m-Y', strtotime($rekap->tanggal)) }}</td>
                            <td>{{ $rekap->number }}</td>
                            <td>{{ $rekap->pelanggan }}</td>
                            <td>Rp. {{ number_format($rekap->total) }}</td>
                            <td>Rp. {{ number_format($rekap->pph_23) }}</td>
                            <td>Rp. {{ number_format($rekap->total_bayar) }}</td>
                            <td><a href="jurnal-umum/{{ $rekap->id }}" class="btn btn-success">Buat Jurnal</a>
                        </tr>
                    @endforeach
                </table>
            {{ $rekapitulasi->appends(request()->toArray())->links() }}
        </div>
    </div>
</div>
@endsection
