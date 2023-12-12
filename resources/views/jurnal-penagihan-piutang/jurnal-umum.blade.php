@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Jurnal Umum Penagihan Piutang</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		
		
	<form action="{{ url('simpanJurnalPenagihanPiutang')}}" method="post">{{ @csrf_field() }} 

    @if (isset($id_pendapatan_jasa))
        @foreach ($id_pendapatan_jasa as $pendapatan_jasa)
            <input type="hidden" name="id_pendapatan_jasa[]" value="{{ $pendapatan_jasa}}" class="form-control">
        @endforeach
    @endif
              
    <div class="form-group row">
		<label class="col-md-3">Tipe Jurnal</label>
			<div class="col-md-7">
            <input type="hidden" name="tipe_jurnal" value="{{ isset($tipe_jurnal) ? $tipe_jurnal->id : '' }}" class="form-control">
            <input type="text" class="form-control" value="{{ isset($tipe_jurnal) ? $tipe_jurnal->tipe_jurnal : '' }}" readonly> 
		</div>
	</div>
					
    <div class="form-group row">
		<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">
            <input type="text" name="kode_jurnal" value="{{ isset($kode_jurnal) ? $kode_jurnal->kode : 'SJ-1' }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal_posting" value="{{ date('Y-m-d') }}" class="form-control" id="tanggal">
		</div>
	</div>
					 
	<div class="form-group row">
		<label class="col-md-3">Keterangan</label>
			<div class="col-md-7">
				<textarea class="form-control" name="keterangan" rows="4">
            </textarea>
		</div>
	</div>
				
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="10%">No Perkiraan</th>
                    <th>Nama Perkiraan</th>
                    <th>Debet</th>
					<th>Kredit</th>		
                </tr>
            </thead>
        <tbody>
        @php ($total_debet=0)
        @php ($total_kredit=0)
            @foreach ($debet as $jurnal)
                <tr>
					<td width="10%"><input type="hidden" name="id_perkiraan[]" value="{{ $jurnal->id_perkiraan }}">{{ $jurnal->id_perkiraan }}</td>
                    <td>{{ $jurnal->perkiraan }}</td>
                    <td><input type="hidden" name="debet[]" value="{{ $jurnal->debit }}">Rp.  {{ number_format($jurnal->debit) }}</td>
					<td><input type="hidden" name="kredit[]" value="{{ $jurnal->kredit }}">Rp. {{ number_format($jurnal->kredit) }}</td>
                    @php ($total_debet += $jurnal->debit)
                @php ($total_kredit += $jurnal->kredit)
                @endforeach
                </tr>
                
                <tr>
                <td><b>Total</b></td>
                <td></td>
                <td>Rp. {{ number_format($total_debet)}}</td>
                <td>Rp. {{ number_format($total_kredit)}}</td>
            </tr>

            <tr>
                <td><b>Balance : </td>
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