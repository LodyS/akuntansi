@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Jurnal Penjualan Obat Tunai</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-jurnal-penjualan-obat-tunai')}}" method="post">{{ @csrf_field() }} 

    @if(isset($periodeKeuangan))
    <input type="hidden" value="{{ $periodeKeuangan->id}}" name="id_periode">
    @endif

    @if(isset($id_penjualan))
    <input type="hidden" value="{{ $id_penjualan}}" name="id_penjualan">
    @endif

    <div class="form-group row">
		<label class="col-md-3">Tipe Jurnal</label>
			<div class="col-md-7">
            <input type="text" value="{{ $tipe_jurnal->tipe_jurnal }}" class="form-control" readonly>
			<input type="hidden" name="id_tipe_jurnal" value="{{ $tipe_jurnal->id }}" class="form-control">
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">
            <input type="text" name="kode_jurnal" value="{{ isset($jurnal) ? $jurnal->kode_jurnal : 'SJ-1' }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">No Dokumen</label>
			<div class="col-md-7">
            <input type="text" name="kode_voucher" value="{{ $kode_voucher }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" class="form-control" value="{{ $tanggal_transaksi }}" required>
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
            @foreach ($debet_satu as $jurnal)
            <tr>
                <td>{{ $i }}</td>
                <td><input type="hidden" name="id_perkiraan[]" value="{{ isset($jurnal->id_perkiraan) ? $jurnal->id_perkiraan : '' }}" 
                class="form-control" readonly>{{$jurnal->kode}}</td>
                <td>{{$jurnal->perkiraan}}</td>
                <td><input type="hidden" name="debet[]" value="{{ $jurnal->debet}}">Rp. {{number_format($jurnal->debet,2)}}</td>
                <td><input type="hidden" name="kredit[]" value="{{ $jurnal->kredit}}">Rp. {{number_format($jurnal->kredit,2)}}</td>
            </tr>
            @php ($total_debet += $jurnal->debet)
            @php ($total_kredit += $jurnal->kredit)
            @php($i++)
        @endforeach

            <tr>
                <td><b>Total</b></td>
                <td></td>
                <td></td>
                <td>Rp. {{ number_format($total_debet,2)}}</td>
                <td>Rp. {{ number_format($total_kredit,2)}}</td>
            </tr>

            <tr>
                <td><b>Balance : </td>
                <td></td>
                <td></td>
                <td></td>
                @php ($balance = $total_debet - $total_kredit)
                <td><input type="hidden" name="balance" value="{{ $balance }}">Rp. {{ number_format($balance,2) }}</td>
            </tr>
        </table>

        <button type="submit" align="right" class="btn btn-primary">Simpan</button>
                
            </form>
        </div>
    </div>
</div>
@endsection