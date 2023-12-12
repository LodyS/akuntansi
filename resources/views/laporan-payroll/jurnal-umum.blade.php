@extends('layouts.app')

@section('content')   
<div class="page-header">
    <h1 class="page-title">Jurnal Umum Gaji Pegawai Payroll</h1>
</div>

@include('layouts.inc.breadcrumb')
	<div class="page-content">
    	<div class="panel">
        	<div class="panel-body">
			@include('flash-message')
				<form action="{{ url('simpan-jurnal-gaji-pegawai-payroll')}}" method="post">{{ @csrf_field() }} 
					<input type="hidden" name="id_unit" value="{{ $id_unit}}">
					<div class="form-group row">
						<label class="col-md-3">Tanggal</label>
							<div class="col-md-7">
							<input type="date" name="tanggal" value="{{ $tanggal_posting }}" class="form-control" required readonly>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3">Kode Jurnal</label>
							<div class="col-md-7">
            				<input type="text" name="kode_jurnal" value="{{ isset($kode_jurnal) ? $kode_jurnal->kode_jurnal : 'GJ-1' }}" class="form-control" readonly>
							<input type="hidden" name="id_tipe_jurnal" value="{{ isset($kode_jurnal) ? $kode_jurnal->id_tipe_jurnal : '' }}" class="form-control" readonly>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3">Keterangan</label>
							<div class="col-md-7">
							<input type="text" name="keterangan" value="Jasa Dokter : {{ $unit->nama }}" class="form-control" required readonly>
						</div>
					</div>

					<table class="table table-hover">
						<tr>
							<th>No Perkiraan</th>
				  			<th>Nama Perkiraan</th>
				  			<th>Debet</th>
				  			<th>Kredit</th>
						</tr>
						@php ($total_debet=0)
    					@php ($total_kredit=0)
						@foreach ($debet as $jurnal)
						<tr>
							<td>{{$jurnal->id_perkiraan}}</td>
							<td>{{$jurnal->rekening}}</td>
				    		<td>Rp. {{number_format($jurnal->debet) }}</td>
							<td>Rp. {{number_format($jurnal->kredit) }}</td>
							<input type="hidden" name="id_perkiraan[]" value="{{ $jurnal->id_perkiraan }}" class="form-control">
							<input type="hidden" name="debet[]" value="{{ $jurnal->debet }}" class="form-control">
							<input type="hidden" name="kredit[]" value="{{ $jurnal->kredit }}" class="form-control">
							<input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
							@php ($total_debet += $jurnal->debet)
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
					</table>
        		<button type="submit" align="right" class="btn btn-primary">Simpan</button>     
            </form>
        </div>
    </div>
</div>
@endsection