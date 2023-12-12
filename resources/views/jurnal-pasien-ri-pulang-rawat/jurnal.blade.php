@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Jurnal Pasien Pulang Rawat</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		
		
	<form action="{{ url('/simpan-jurnal-ri')}}" method="post">{{ @csrf_field() }} 
                
    <div class="form-group row">
		<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">
            @if (isset($tipe_jurnal))
            <input type="hidden" name="id_tipe_jurnal" value="{{ $tipe_jurnal->id }}" class="form-control" readonly>
            <input type="text" value="{{ $tipe_jurnal->tipe_jurnal }}" class="form-control" readonly>
            @endif
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" id="tanggal">
		</div>
	</div>
				
    <div class="form-group row">
		<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">
            <input type="text" name="kode_jurnal" value="{{ isset($kode_jurnal) ? $kode_jurnal->kode : 'GJ-1' }}" class="form-control" readonly>         
		</div>
	</div>
					 
	<div class="form-group row">
		<label class="col-md-3">Keterangan</label>
			<div class="col-md-7">
			<textarea class="form-control" name="keterangan" rows="4"></textarea>
		</div>
	</div>
				
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Perkiraan</th>
                            <th>Nama Perkiraan</th>
                            <th>Debet</th>
							<th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php ($i=1)
                    @php ($total_debet=0)
                    @php ($total_kredit=0)
                    @foreach ($debet as $jurnal)
                        <tr>
                            <td>{{ $i }}</td>
							<td><input type="hidden" name="id_perkiraan[]" value="{{ $jurnal->id_perkiraan }}">{{ $jurnal->id_perkiraan }}</td>
                            <td>{{ $jurnal->rekening }}</td>
                            <td><input type="hidden" name="debet[]" value="{{ $jurnal->debit }}">Rp.  {{ number_format($jurnal->debit) }}</td>
							<td><input type="hidden" name="kredit[]" value="{{ $jurnal->kredit }}">Rp.  {{ number_format($jurnal->kredit) }}</td>
                            @php ($total_debet += $jurnal->debit)
                            @php ($total_kredit += $jurnal->kredit)
                            @php($i++)
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
                                    
				@if (isset($id_pendapatan_jasa))   
				@foreach ($id_pendapatan_jasa as $jasa)
				    <input type="hidden" name="id_pendapatan_jasa[]" value="{{ $jasa }}">
				@endforeach
                @endif
							   
                    </tbody>
                </table>
                <button type="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>	
        </div>
    </div>
</div>
@endsection