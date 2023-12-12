@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Buku Besar</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <form action="{{ url('laporan-buku-besar/laporan')}}" method="POST" id="laporan">{{ @csrf_field() }}

	<div class="form-group row">
		<label class="col-md-3">Perkiraan</label>
			<div class="col-md-7">
			<select name="id_perkiraan" id="id_perkiraan" class="form-control select" required>
                <option value="">Pilih Perkiraan</option>
                @foreach($perkiraan as $Perkiraan)
                <option value="{{ $Perkiraan->id}}">{{ $Perkiraan->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Unit</label>
			<div class="col-md-7">
			<select name="id_unit" id="id_unit" class="form-control select">
                <option value="">Pilih Unit</option>
                @foreach($unit as $unid)
                <option value="{{ $unid->id}}">{{ $unid->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Mulai</label>
			<div class="col-md-7">
			<input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ date('Y-m-d')}}" class="form-control">
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Selesai</label>
			<div class="col-md-7">
			<input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ date('Y-m-d')}}" class="form-control" required>
		</div>
	</div>

        <button type="submit" align="right" class="btn btn-primary" id="cari"><i class="icon glyphicon glyphicon-search"></i>Cari</button>
        <br/><br/>

    <h4 align="center">LAPORAN BUKU BESAR<br/>
{{optional($setting)->nama }}</br>
    Tanggal :
    {{ isset($tanggal_mulai) ? date('d-m-Y', strtotime($tanggal_mulai)) : '' }} S/D {{ isset($tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_selesai)) : '' }}</h4>

    <table class="table table-hover" id="laporan-buku-besar">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Cost Centre</th>
                <th>Unit</th>
                <th>Keterangan</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo Debet</th>
                <th>Saldo Kredit</th>
            </tr>
            @isset($rekapitulasi)
            @php ($i=1)
        @foreach ($rekapitulasi as $rekap)
            <tr>
                <td>{{ $i}}</td>
                <td>{{ date('d-m-Y', strtotime($rekap->tanggal_posting)) }}</td>
                <td>{{ $rekap->code_cost_centre }}</td>
                <td>{{ $rekap->unit }}</td>
                <td>{{ $rekap->keterangan}}</td>
                <td>Rp. {{ number_format($rekap->debet,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($rekap->kredit,2, ",",  ".") }}</td>
                <td>Rp. {{ number_format($rekap->saldo_debet,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($rekap->saldo_kredit,2, ",", ".") }}</td>

            </tr>
            @php($i++)
        @endforeach
        <br/>
        <br/>
            <button type="button" align="right" class="btn btn-success" id="excel"><i class="icon glyphicon glyphicon-list-alt" aria-hidden="true"></i>Excel</button>
            <button class="btn btn-danger print-link no-print" onclick="jQuery('#laporan-buku-besar').print()"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Cetak </button>
            @endif
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"
integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous"
referrerpolicy="no-referrer"></script>
<script type="text/javascript">

$(".select").select2({ width: '100%' });

$("#excel").click(function () {
        $("#laporan-buku-besar").table2excel({
            filename: "laporan-buku-besar.xls"
        });
    });
</script>
@endpush
