@extends('layouts.app')

@section('content')

<style>
.judul { font-weight: bold; }

.judul_1 { font-weight: bold; }

</style>

<div class="page-header">
    <h1 class="page-title">Laporan Neraca Bulan : {{ isset($bulan_indonesia) ? $bulan_indonesia : '' }} tahun : {{ isset($tahun) ? $tahun : '' }}</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <form action="{{ url('laporan-neraca/laporan') }}" method="POST">{{ @csrf_field() }}

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

<div class="text-right">
    <button class="btn btn-primary">Cari</button>
</div>

<div class="container">
  <div class="row">

    <div class="col-md-6">
        <table class="table table-hover ">
            <tr>
                <th><span class="judul">Aktiva</span></th>
                <th></th>
                <th></th>
            </tr>

            @if(isset($aktiva_lancar))
            @foreach ($aktiva_lancar as $aktiva)
            <tr>
                <td>{{ $aktiva->perkiraan}}</td>
                <td>Rp. {{ number_format($aktiva->nominal_satu,2) }}</td>
                <td>Rp. {{ number_format($aktiva->nominal_dua,2) }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td></td>
                <td>Rp. 0</td>
                <td>Rp. 0</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="col-md-6">
        <table class="table table-hover">
            <th><span class="judul_1">Passiva</span></th>
                <th></th>
                <th></th>
            </tr>
            @if(isset($passiva))
            @foreach($passiva as $pass)
            <tr>

                <td>{{ $pass->perkiraan}}</td>
                <td>Rp. {{ number_format($pass->nominal_satu,2) }}</td>
                <td>Rp. {{ number_format($pass->nominal_dua,2) }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td></td>
                <td>Rp. 0</td>
                <td>Rp. 0</td>
            </tr>
            @endif
    </table>
</div>

</div>
</div>

        </div>
    </div>
</div>
@endsection
