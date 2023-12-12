@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Penyusutan Aktiva Tetap</h1>
</div>

@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('simpan-jurnal-penyusutan-aktiva-tetap')}}" method="post">{{ @csrf_field() }}

    @if (isset($periode_keuangan))
    <input type="hidden" name="id_periode" value="{{ $periode_keuangan->id }}">
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
            <input type="text" name="kode_jurnal" value="{{ $kode_jurnal->kode_jurnal }}" class="form-control" readonly>
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
			<textarea class="form-control" name="keterangan" rows="4" value="Jurnal Penyusutan Aktiva Tetap"></textarea>
		</div>
	</div>

    @if($aktiva_tetap == null)
        @foreach ($penyusutan as $susut)
        <input type="hidden" name="id_penyusutan[]" value="{{$susut->id}}"> <!-- Ini untuk get id penyusutan jika aktiva tetap dipilih semua-->
        @endforeach
    @else
        <input type="hidden" name="penyusutan_id" value="{{ $aktiva_tetap }}">
    @endif <!-- Ini untuk get ID penyusutan dari aktiva tetap yang dipilih jika kolom form aktiva tetap di isi-->

    <table class="table table-hover">
        <tr>
            <th>No</th>
            <th>No Perkiraan</th>
            <th>Rekening</th>
            <th>Debet</th>
            <th>Kredit</th>
        </tr>

        @foreach ($debet as $key=> $data)

        <tr>
            <td>{{ ++$key }}</td>
            <td><input type="hidden" name="id_perkiraan[]" value="{{$data->id_perkiraan}}" class="form-control" readonly>{{$data->id_perkiraan}}</td>
            <td>{{$data->perkiraan}}</td>
            <td><input type="hidden" name="debet[]" value="{{ $data->debet}}">Rp. {{ number_format($data->debet,2, ",", ".") }}</td>
            <td><input type="hidden" name="kredit[]" value="{{ $data->kredit}}">Rp. {{ number_format($data->kredit,2, ",", ".") }}</td>
      </tr>


        @endforeach

        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td>Rp. {{ number_format($total_debet,2, ",", ".")}}</td>
            <td>Rp. {{ number_format($total_kredit,2, ",", ".")}}</td>
        </tr>

        <tr>
            <td><b>Balance : </td>
            <td></td>
            <td></td>
            <td></td>
            @php ($balance = $total_debet - $total_kredit)
            <td><input type="hidden" name="balance" value="{{ $balance }}">Rp. {{ number_format($balance,2, ",", ".") }}</td>
        </tr>
    </table>

            <button type="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
