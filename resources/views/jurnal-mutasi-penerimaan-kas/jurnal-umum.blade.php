@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Jurnal Mutasi Penerimaan Kas</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <form action="{{ url('/simpan-jurnal-mutasi-penerimaan-kas')}}" method="post">{{ @csrf_field() }}
        <div class="form-group row">
		            <label class="col-md-3">Kode Jurnal</label>
			            <div class="col-md-7">
                        <input type="text" name="kode_jurnal" value="{{ isset($kode) ? $kode->kode : 'GJ-1' }}" class="form-control" readonly>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Tanggal</label>
			            <div class="col-md-7">
			            <input type="date" name="tanggal" value="{{ isset($tanggal) ? $tanggal : date('Y-m-d')}}" class="form-control" readonly>
		            </div>
	            </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode Rekening</th>
                    <th>Cost Centre</th>
                    <th>Rekening</th>
                    <th>Debet</th>
					<th>Kredit</th>
                </tr>
            </thead>
        <tbody>
        @php ($total_debet=0)
        @php ($total_kredit=0)
            @foreach ($debet as $jurnal)
                <tr>
                    <input type="hidden" name="id_mutasi_kas" value="{{$jurnal->id_mutasi_kas }}">
					<td><input type="hidden" name="id_perkiraan[]" value="{{ $jurnal->id_perkiraan }}">{{ $jurnal->kode_rekening }}</td>
                    <td><input type="hidden" name="id_unit[]" value="{{ $jurnal->id_unit }}">{{ $jurnal->cost_centre }}</td>
                    <td>{{ $jurnal->nama }} - {{ $jurnal->unit }}</td>
                    <td><input type="hidden" name="debet[]" value="{{ $jurnal->debet }}">Rp.  {{ number_format($jurnal->debet) }}</td>
					<td><input type="hidden" name="kredit[]" value="{{ $jurnal->kredit }}">Rp. {{ number_format($jurnal->kredit) }}</td>
                    @php ($total_debet += $jurnal->debet)
                @php ($total_kredit += $jurnal->kredit)
                @endforeach
                </tr>

                <tr>
                <td><b>Total</b></td>
                <td></td>
                <td></td>
                <td>Rp. {{ number_format($total_debet)}}</td>
                <td>Rp. {{ number_format($total_kredit)}}</td>
            </tr>

            <tr>
                <td><b>Balance : </td>
                <td></td>
                <td></td>
                <td></td>
                @php ($balance = $total_debet - $total_kredit)
                <td><input type="hidden" name="balance" value="{{ $balance }}">Rp. {{ number_format($balance) }}</td>
            </tr>

        </tbody>
    </table>

                <button type="submit" align="right" class="btn btn-primary">Simpan</button>
             </form>
        </div>
    </div>
</div>
@endsection
