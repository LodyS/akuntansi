@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Pembelian Logistik dan Farmasi</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-jurnal-pembelian-logistik-farmasi')}}" method="post">{{ @csrf_field() }}

  <input type="hidden" name="id_pembelian" value="{{$id_pembelian}}" class="form-control">

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
                <input type="text" name="kode_jurnal" value="{{ isset($jurnal) ? $jurnal->kode_jurnal : 'CDJ-1' }}" class="form-control" readonly>
		  </div>
	  </div>

    <div class="form-group row">
		  <label class="col-md-3">Tanggal</label>
			  <div class="col-md-7">
				<input type="date" name="tanggal" value="{{$tanggal }}" class="form-control"  required>
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
        @foreach ($pembelian as $data)
      <tr>
        <td>{{ $i }}</td>
        <td><input type="hidden" name="id_perkiraan[]" value="{{$data->id_perkiraan}}" class="form-control" readonly>{{$data->kode}}</td>
        <td>{{$data->perkiraan}}</td>
        <td><input type="hidden" name="debet[]" value="{{ $data->debit}}">Rp. {{ number_format($data->debit) }}</td>
        <td><input type="hidden" name="kredit[]" value="{{ $data->kredit}}">Rp. {{ number_format($data->kredit) }}</td>
      </tr>
      @php ($total_debet += $data->debit)
            @php ($total_kredit += $data->kredit)
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
