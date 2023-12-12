@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Jurnal Umum</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">

<form action="{{ url('/simpan-jurnal-pendapatan-jasa') }}" method="post" id="jurnal-pendapatan-jasa">{{ @csrf_field() }}

    @if (isset($id_pendapatan_jasa))
        <input type="hidden" name="id_pendapatan_jasa[]" value="{{ $id_pendapatan_jasa}}" class="form-control">
    @endif

    <div class="form-group row">
		<label class="col-md-3">Tipe Jurnal</label>
			<div class="col-md-7">
            @if (isset($tipe_jurnal))
            <input type="hidden" name="tipe_jurnal" value="{{ $tipe_jurnal->id }}" class="form-control">
            <input type="text" class="form-control" value="{{ $tipe_jurnal->tipe_jurnal }}" readonly>
            @endif
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Kode Jurnal</label>
			<div class="col-md-7">        
            <input type="text" name="kode_jurnal" id="kode_jurnal" value="{{ isset($kode_jurnal) ? $kode_jurnal->kode : 'SJ-1'}}" class="form-control" readonly>           
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">No Dokumen</label>
			<div class="col-md-7">
            <input type="text" name="no_dokumen" id="no_dokumen" value="1" class="form-control">
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" id="tanggal">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Keterangan</label>
			<div class="col-md-7">
			<textarea class="form-control" name="keterangan" rows="4" value="Jurnal Pendapatan Jasa"></textarea>
		</div>
	</div>

            <button type="submit" id="simpan" class="btn btn-primary">Simpan</button>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Perkiraan</th>
                        <th>Nama Perkiraan</th>
                        <th>Debet</th>
						<th>Kredit</th>
                    </tr>
                </thead>

                <tbody>
                @php ($total_debet=0)
                @php ($total_kredit=0)
                @foreach ($jurnal as $key => $jurnals)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><input type="hidden" name="id_perkiraan[]" value="{{ $jurnals->id_perkiraan }}">{{ $jurnals->id_perkiraan}}</td>
                        <td>{{ $jurnals->perkiraan }}</td>
                        <td><input type="hidden" name="debet[]" value="{{ $jurnals->debet ?: 0 }}">Rp.{{ number_format($jurnals->debet) }}</td>
						<td><input type="hidden" name="kredit[]" value="{{ $jurnals->kredit ?: 0 }}">Rp.{{ number_format($jurnals->kredit) }}</td>
                    </tr>
                @php ($total_debet += $jurnals->debet)
                @php ($total_kredit += $jurnals->kredit)
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
            
                </tbody>
            </table>

        </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(document).ready(function(){

	$('#jurnal-pendapatan-jasa').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	},
	    icon: null,
	    fields: {

            no_dokumen : {
                validators: {
				    notEmpty: {
				    message: 'Kolom No Dokumen tidak boleh kosong'
						}
					}
				},
            kode_jurnal : {
                validators: {
				    notEmpty: {
				    message: 'Kolom Kode jurnal tidak boleh kosong'
						}
					}
				},
            tanggal :  {
                validators : {
                    notEmpty : {
                    message : 'Kolom Tanggal tidak boleh kosong'
                    }
                }
            }
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
