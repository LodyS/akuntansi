@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Jurnal Pembayaran Hutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-jurnal-pembayaran-hutang')}}" method="post" id="jurnal">{{ @csrf_field() }} 

        @foreach ($id_pembelian as $id)
        <input type="hidden" name="id_pembelian[]" value="{{ $id->id_pembelian }}">
        @endforeach

		@if (isset($perkiraan))
		<input type="hidden" name="perkiraan_id" value="{{ $perkiraan->id_perkiraan}}">
		@else
		<input type="hidden" name="perkiraan_id" value="1">
    @endif

    <div class="form-group row">
		  <label class="col-md-3">Tipe Jurnal</label>
			<div class="col-md-7">
        <input type="text" value="{{ isset($tipe_jurnal) ? $tipe_jurnal->tipe_jurnal : '' }}" class="form-control" readonly>
			  <input type="hidden" name="id_tipe_jurnal" value="{{ isset ($tipe_jurnal) ? $tipe_jurnal->id : '' }}" class="form-control">
		  </div>
	  </div>

    <div class="form-group row">
	  	<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">	
      <input type="text" name="kode_jurnal" value="{{ isset($kode_jurnal) ? $kode_jurnal->kode_jurnal : 'CDJ-1' }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control"  required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Keterangan</label>
			<div class="col-md-7">
			<textarea class="form-control" name="keterangan" rows="4"></textarea>    
		</div>
	</div>

  @foreach ($id_pembelian as $id)
    <input type="hidden" name="id_pembelian[]" value="{{ $id->id }}">
  @endforeach

    <table class="table table-hover">
      <tr>
        <th>No</th>
        <th>No Perkiraan</th>
        <th>Rekening</th>
        <th>Debet</th>
        <th>Kredit</th>
      </tr>

      @php ($i=1)
	  @php ($total_debet=0)
    @php ($total_kredit=0)
        @foreach ($debet as $data)

      <tr>
        <td>{{ $i }}</td>
        <input type="hidden" name="id_perkiraan[]" id="id_perkiraan" value="{{$data->id_perkiraan}}">
        <td>{{$data->kode}}</td>
        <td>{{$data->rekening}}</td>
        <td><input type="hidden" name="debet[]" value="{{ $data->debet}}">Rp. {{ number_format($data->debet) }}</td>
        <td><input type="hidden" name="kredit[]" value="{{ $data->kredit}}">Rp. {{ number_format($data->kredit) }}</td>
      </tr>
	  	@php ($total_debet += $data->debet)
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
        <button type="submit" align="right" id="simpan" class="btn btn-primary">Simpan</button>

	<td></td>   
	<td></td>  
	<td></td>  
	<td></td>  
	<td></td>   
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(document).ready(function(){
	$('#jurnal').formValidation({
		framework: "bootstrap4",
		button: {
		selector: "#simpan",
	    disabled: "disabled"
	  },

	icon: null,
	fields: {
	id_perkiraan : { 
			validators: {
			notEmpty: {
			message: 'Kolom Id Perkiraan tidak boleh kosong'
			}
		}
	},
}, 
err: {
	clazz: 'invalid-feedback'
},
control: {
	// The CSS class for valid control
	valid: 'is-valid',

	// The CSS class for invalid control
	invalid: 'is-invalid'
},
row: {
	invalid: 'has-danger'
}
});
	

});
</script>
@endpush