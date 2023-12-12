@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Neraca Saldo Setelah Penutupan Bulan : {{ isset($bulan_indonesia) ? $bulan_indonesia : ''  }} tahun : {{ isset($tahun) ? $tahun : '' }}</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <form action="{{ url('laporan-neraca-saldo-setelah-penutupan/laporan') }}" method="POST">{{ @csrf_field() }}

	<div class="form-group row">
		<label class="col-md-3">Bulan</label>
			<div class="col-md-7">
			<select name="bulan" class="form-control select" required>
                <option value="">Pilih</option>
                <option value="1">Januari</option>
                <option value="2">Febuari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tahun</label>
			<div class="col-md-7">
			<select name="tahun" id="tahun" class="form-control select" required>
                <option value="">Pilih</option>
                <option value="">Pilih Semua Tahun</option>
                @for($i=2020; $i<2050; $i++)
                <option value="{{ $i }}">{{ $i}} </option>
                @endfor
            </select>
		</div>
	</div>

		<button class="btn btn-primary btn-round">Cari</button>

        <table class="table table-hover ">
            <tr>
                <th>Kode</th>
                <th>Perkiraan</th>
                <th>Debet</th>
                <th>Kredit</th>
            </tr>

            @if(isset($neraca))
            @php ($total_debet=0)
            @php ($total_kredit=0)
            @foreach ($neraca as $ner)
            <tr>
                <td>{{ $ner->kode }}</td>
                <td>{{ $ner->perkiraan }}</td>
                <td>Rp. {{ number_format($ner->debet,2) }}</td>
                <td>Rp. {{ number_format($ner->kredit,2) }}</td>
                @php ($total_debet += $ner->debet)
                @php ($total_kredit += $ner->kredit)
            </tr>
            @endforeach
            <tr>
                <td><b>Total</b></td>
                <td></td>
                <td><b>Rp. {{ number_format($total_debet,2) }}</b></td>
                <td><b>Rp. {{ number_format($total_kredit,2) }}</b></td>
            </tr>
            @else
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endif
        </table>
    </div>
    </div>
</div>
@endsection
