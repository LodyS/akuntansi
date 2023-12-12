@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Jurnal Deposit</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-jurnal-pembayaran-invoice')}}" method="post">{{ @csrf_field() }} 
   
    @if (isset($id_periode))
        <input type="hidden" name="id_periode" value="{{ $id_periode->id }}">
    @endif

    <input type="hidden" name="id_pembayaran_invoice" value="{{ $id_pembayaran_invoice }}">

    <div class="form-group row">
		  <label class="col-md-3">Tipe Jurnal</label>
			<div class="col-md-7">
            <input type="text" value="{{ isset($tipe_jurnal) ? $tipe_jurnal->kode_jurnal : 'CRJ-1' }}" class="form-control" name="kode_jurnal" readonly>
			<input type="hidden" name="id_tipe_jurnal" value="{{ isset($tipe_jurnal) ? $tipe_jurnal->id_tipe_jurnal : '' }}" class="form-control">
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">No Dokumen</label>
			<div class="col-md-7">
			<input type="text" name="no_dokumen" class="form-control" required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Keterangan</label>
			<div class="col-md-7">
			<textarea class="form-control" name="keterangan" rows="4" required></textarea>    
		</div>
	</div>

<table class="table table-hover">
    <tr>
        <th>No</th>
        <th>No Perkiraan</th>
        <th>Nama Perkiraan</th>
        <th>Debet</th>
        <th>Kredit</th>
    </tr>
      
    @php ($i=1)
    @php ($total_debet=0)
    @php ($total_kredit=0)
    @foreach ($jurnal as $jurnals)
           
    <tr>
        <td>{{ $i }}</td>
        <td><input type="hidden" name="id_perkiraan[]" value="{{$jurnals->id}}" class="form-control" readonly>{{$jurnals->kode}}</td>
        <td>{{$jurnals->nama}}</td>
        <td><input type="hidden" name="debet[]" value="{{ $jurnals->debet}}">Rp. {{number_format($jurnals->debet)}}</td>
        <td><input type="hidden" name="kredit[]" value="{{ $jurnals->kredit}}">Rp. {{number_format($jurnals->kredit)}}</td>
    </tr>
        @php ($total_debet += $jurnals->debet)
        @php ($total_kredit += $jurnals->kredit)
        @php($i++)
    @endforeach
    
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
    </table>

        <button type="submit" align="right" class="btn btn-primary">Simpan</button>
                
            </form>
        </div>
    </div>
</div>
@endsection