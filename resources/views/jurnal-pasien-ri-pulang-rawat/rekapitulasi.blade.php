@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekapitulasi Pasien Pulang Rawat Tanggal : {{date('d-m-Y', strtotime($tanggal))}}</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		
<div class="form-group row">
    </div>	
        <table class="table table-hover">
            <tr>
                <th>No Pendaftaran</th>
				<th>Tanggal</th>
                <th>Pasien</th>
				<th>Total Tagihan</th>
            </tr>
                   
            <form action="{{ url('jurnal-pasien-ri-pulang-rawat/jurnal') }}" method="post">{{ @csrf_field() }} 
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="tipe_pasien" value="{{ $tipe_pasien }}">

                @foreach ($rekapitulasi as $rekap)
                    <tr>
                        <input type="hidden" name="id_pendapatan_jasa[]" value="{{ $rekap->id_pendapatan_jasa }}">
                        <td> {{ $rekap->no_kunjungan}}</td>
						<td> {{date('d-m-Y', strtotime($rekap->tanggal_kunjungan))}}</td>
                        <td> {{ $rekap->pasien}}</td>
						<td>Rp. {{ number_format($rekap->tagihan) }}</td>
                    @endforeach
                    </tr>
                </table>
                {{ $rekapitulasi->appends(request()->toArray())->links() }}
                <button type="submit" class="btn btn-xs btn-success">Buat Jurnal</a>
			</form>
        </div>
    </div>
</div>
@endsection