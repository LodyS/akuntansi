@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Umum</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	    <form action="{{ url('simpan-jurnal-penerimaan-piutang')}}" method="post">{{ @csrf_field() }}

	    @if (isset($id_pembayaran))
        @foreach ($id_pembayaran as $pembayaran)
            <input type="hidden" name="id_pembayaran[]" value="{{ $pembayaran}}" class="form-control">
        @endforeach
        @endif

    	    <div class="form-group row">
			    <label class="col-md-3">Tipe Jurnal</label>
				    <div class="col-md-7">
				    @if (isset($tipe_jurnal))
                	<input type="text" value="{{ $tipe_jurnal->tipe_jurnal }}" class="form-control" readonly>
					<input type="hidden" name="id_tipe_jurnal" value="{{ $tipe_jurnal->id }}" class="form-control">
				    @endif
			    </div>
		    </div>

    	    <div class="form-group row">
			    <label class="col-md-3">Kode Jurnal</label>
				    <div class="col-md-7">
            	    <input type="text" name="kode_jurnal" value="{{ isset($jurnal) ? $jurnal->kode_jurnal : 'CRJ-1' }}" class="form-control" readonly>
			    </div>
		    </div>

 	        <div class="form-group row">
			    <label class="col-md-3">Tanggal</label>
				    <div class="col-md-7">
				    <input type="date" name="tanggal" value="{{$tanggal }}" class="form-control" readonly required>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Keterangan</label>
				    <div class="col-md-7">
				    <textarea class="form-control" name="keterangan" rows="4"></textarea>
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
				    <td>Rp. {{number_format($jurnal->debit) }}</td>
					<td>Rp. {{number_format($jurnal->kredit) }}</td>
					<input type="hidden" name="id_perkiraan[]" value="{{ $jurnal->id_perkiraan }}" class="form-control">
					<input type="hidden" name="debet[]" value="{{ $jurnal->debit }}" class="form-control">
					<input type="hidden" name="kredit[]" value="{{ $jurnal->kredit }}" class="form-control">
					<input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
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
			</table>

            <button type="submit" align="right" class="btn btn-primary">Simpan</button>

            </form>
        </div>
    </div>
</div>
@endsection
