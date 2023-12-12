@extends('layouts.app')

@section('content')

<style>
table
{
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td
{
  text-align: left;
  padding: 8px;
}

tr:nth-child(even)
{
    background-color: #f2f2f2
}
</style>

<div class="page-header">
    <h1 class="page-title">Laporan Laba Rugi</h1>
    @include('layouts.inc.breadcrumb')
</div>

<div class="page-content">
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
            <div class="form-group">
        </div>
    </div>
</header>

<div class="panel-body">
<form action="{{ url('laporan-laba-rugi/laporan')}}" method="POST">{{ @csrf_field() }}

<div class="form-group row">
    <label class="col-md-3">Tanggal Mulai</label>
        <div class="col-md-7">
        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ date('Y-m-d')}}" class="form-control" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3">Tanggal Selesai</label>
        <div class="col-md-7">
        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ date('Y-m-d')}}" class="form-control" required>
    </div>
</div>

    <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button>

    <h3 align="center">Laporan Laba Rugi Rumah Sakit Akuntansi tanggal
    {{isset($tanggal_mulai) ?date('d-m-Y', strtotime($tanggal_mulai)) : '' }} S/D {{ isset($tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_selesai)) : '' }}</h3>

<div style="overflow-x:auto;">
    <table class="table table-hover" id="laporan-laba-rugi">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Perkiraan</th>
                <th>Nominal</th>
                <th>Nominal</th>
                <th>Nominal</th>
            </tr>
            @if(isset($data))
            @php ($satu=0)
            @php ($dua =0)
            @php ($tiga=0)
            @php ($i=1)
            @foreach ($data as $labarugi)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $labarugi->kode }}</td>
                <td>{{ $labarugi->perkiraan }}</td>
                <td>Rp. {{ number_format($labarugi->nominal_satu,2) }}</td>
                <td>Rp. {{ number_format($labarugi->nominal_dua,2) }}</td>
                <td>Rp. {{ number_format($labarugi->nominal_tiga,2) }}</td>
                @php ($satu += $labarugi->nominal_satu)
                @php ($dua += $labarugi->nominal_dua)
                @php ($tiga += $labarugi->nominal_tiga)
            </tr>
            @php($i++)
            @endforeach

            <tr>
                <td>Jumlah : </td>
                <td></td>
                <td></td>
                <td>Rp. {{ number_format($satu,2) }}</td>
                <td>Rp. {{ number_format($dua,2) }}</td>
                <td>Rp. {{ number_format($tiga,2) }}</td>
            </tr>
            @else
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            @endif
            </table>
                <button type="button" align="right" class="btn btn-outline-success" id="excel">Excel</button>
                <button class="btn btn-outline-danger print-link no-print" onclick="jQuery('#laporan-laba-rugi').print()">Cetak </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"
integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous"
referrerpolicy="no-referrer"></script>
<script type="text/javascript">

$("#excel").click(function () {
        $("#laporan-laba-rugi").table2excel({
            filename: "laporan-laba-rugi.xls"
        });
    });
</script>
@endpush
