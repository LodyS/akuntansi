@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Perubahan Ekuitas</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <form action="{{ url('perubahan-ekuitas/laporan') }}" method="POST">{{ @csrf_field() }}

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

        <button class="btn btn-primary">Cari</button>
<h3 align="center"> {{ optional($setting)->nama }}<br/>LAPORAN PERUBAHAN MODAL<br/>Bulan {{bulan($bulan)}} Tahun {{$tahun}}</h3>
    <table class="table table-hover" id="perubahan-ekuitas">
        <thead>
            <tr>
                <th>Kode COA</th>
                <th>COA</th>
                <th>Saldo Awal</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo Akhir</th>
            </tr>

            @php ($total_saldo=0)
            @forelse($data as $d)
            <tr>
                <td>{{ $d->kode}}</td>
                <td>{{ $d->coa}}</td>
                <td>Rp. {{ number_format($d->SALDO_AWAL) }}</td>
                <td>Rp. {{ number_format($d->debet) }}</td>
                <td>Rp. {{ number_format($d->kredit) }}</td>
                <td>Rp. {{ number_format($saldo_akhir = ($d->SALDO_AWAL- $d->debet) + $d->kredit) }}</td>
            </tr>
            @php ($total_saldo += $saldo_akhir)
            @empty
            @endforelse
            <tr>
                <td><b>TOTAL</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Rp. {{ ($total_saldo==null) ? 0 : number_format($total_saldo) }}</b></td>
            </tr>

        </table>
        <button type="button" align="right" class="btn btn-outline-success" id="excel">Excel</button>
            <button class="btn btn-outline-danger print-link no-print" onclick="jQuery('#laporan-perubahan-ekuitas').print()">Cetak </button>

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
        $("#perubahan-ekuitas").table2excel({
            filename: "laporan-perubahan-ekuitas.xls"
        });
    });
</script>
@endpush
