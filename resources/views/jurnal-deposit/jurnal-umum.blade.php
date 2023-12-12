@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Deposit</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-jurnal-deposit')}}" method="post">{{ @csrf_field() }}

    @if (isset($id_periode))
        <input type="hidden" name="id_periode" value="{{ $id_periode->id }}">
    @endif

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
		<label class="col-md-3">Kode Voucher</label>
			<div class="col-md-7">
			<input type="text" name="kode_voucher" value="{{ $kode_voucher }}" class="form-control" readonly required>
		</div>
	</div>

    <input type="hidden" name="id_tipe_jurnal" value="{{ $id_jurnal->id }}" class="form-control" required>
    <input type="hidden" name="id_deposit" value="{{ $id_deposit }}" class="form-control" required>

    <div class="form-group row">
		<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">
			<input type="text" name="kode_jurnal" value="{{ $tipe_jurnal }}" class="form-control" readonly>
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

    @foreach ($jurnal as $jurnals)

    <tr>
        <td>{{ $i }}</td>
        <td><input type="hidden" name="id_perkiraan[]" value="{{$jurnals->id_perkiraan}}" class="form-control" readonly>{{$jurnals->id_perkiraan}}</td>
        <td>{{$jurnals->perkiraan}}</td>
        <td><input type="hidden" name="debet[]" value="{{ $jurnals->debet}}">Rp. {{number_format($jurnals->debet)}}</td>
        <td><input type="hidden" name="kredit[]" value="{{ $jurnals->kredit}}">Rp. {{number_format($jurnals->kredit)}}</td>
    </tr>

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
