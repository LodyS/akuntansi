@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekapitulasi Penagihan Piutang</h1>
</div>


<form action="{{ url('jurnal-penagihan-piutang/jurnal-umum') }}" method="post">{{ @csrf_field() }} 
<input type="hidden" name="tipe_pasien" value="{{ $tipe_pasien }}">
<input type="hidden" name="tanggal" value="{{ $tanggal }}">
<input type="hidden" name="jenis_pasien" value="{{ $jenis }}">

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <table class="table table-hover">   
                <tr>      
                    <th>No</th>
                    <th>No Registrasi</th>
                    <th>Tanggal</th>
					<th>Dokter</th>
					<th>Tindakan</th>
					<th>Lab</th>
					<th>Usg/Ekg</th>
					<th>Obat</th>
					<th>Total</th>
                </tr>

            @foreach ($rekapitulasi as $key =>$rekap)
                <tr>
                    <input type="hidden" name="id_pendapatan_jasa[]" value="{{ $rekap->id_pendapatan_jasa }}">
                    <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                    <td>{{ $rekap->no_kunjungan }}</td>
                    <td>{{ date('d-m-Y', strtotime($rekap->tanggal)) }}</td>
					<td>Rp. {{ number_format($rekap->dokter) }}</td>
					<td>Rp. {{ number_format($rekap->tindakan) }}</td>
					<td>Rp. {{ number_format($rekap->lab) }}</td>
					<td>Rp. {{ number_format($rekap->usg_ekg) }}</td>
					<td>Rp. {{ number_format($rekap->obat) }}</td>	
					<td>Rp. {{ number_format($rekap->total) }}   </td>	
                </tr>
            @endforeach
            </table>
            {{ $rekapitulasi->appends(request()->toArray())->links() }}
            <button type="submit" align="right" class="btn btn-primary">Buat Jurnal</button>
			</form>
        </div>
    </div>
</div>
@endsection