@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekapitulasi Pendapatan Jasa</h1>
    <h5>Tanggal : {{ isset($tanggal) ? date('d-m-Y', strtotime($tanggal)) : 'Tidak ada data' }}</h5>
    <h5>Tipe Pembayaran : {{ isset($tipe_pembayaran) ? $tipe_pembayaran : 'Tidak ada data' }}</h5>
    <h5>Tipe Pasien : {{ isset($tipe_pasien) ? $tipe_pasien->tipe_pasien : 'Tidak ada data' }}</h5>
    <h5>Jenis Pasien : {{ isset($jenis_pasien) ? $jenis_pasien : 'Tidak ada pasien' }}</h5>
</div>

<form action="{{ url('jurnal-pendapatan-jasa/jurnal-umum') }}" method="post">{{ @csrf_field() }}
<input type="hidden" name="tipe_pasien" value="{{ $tipe_pasien->id }}">
<input type="hidden" name="tanggal" value="{{ $tanggal }}">
<input type="hidden" name="tipe_pembayaran" value="{{ $tipe_pembayaran }}">
<input type="hidden" name="jenis_pasien" value="{{ $jenis_pasien }}">

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Pasien</th>
					<th>Total</th>
                    <th cols="2">Aksi</th>
                </tr>
                    @foreach ($rekapitulasi as $key =>$rekap)
                        <tr>
                            <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                            <input type="hidden" name="id_pendapatan_jasa" value="{{ $rekap->id_pendapatan_jasa }}">
                            <td>{{$rekap->pasien}}</td>
                            <td>Rp.{{ number_format($rekap->total_tagihan) }}</td>
                            <td><a href="detail/{{$rekap->id_pendapatan_jasa}}" class="btn btn-success">Detail</a>
                            <a href="jurnal-umum/{{$rekap->id_pendapatan_jasa}}/{{$tipe_pasien->id}}/{{ $tanggal}}/{{ $tipe_pembayaran}}/{{$jenis_pasien}}" 
                            class="btn btn-success">Buat Jurnal</a></td>
                        </tr>
                @endforeach
            </table>
            {{ $rekapitulasi->appends(request()->toArray())->links() }}
	        </form>
        </div>
    </div>
</div>
@endsection
