@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Perubahan Ekuitas</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <form action="{{ url('laporan-perubahan-ekuitas/laporan') }}" method="POST">{{ @csrf_field() }}

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

    <table class="table table-hover" id="laporan-perubahan-ekuitas">
        <thead>
            <tr>
                <th>Uraian</th>
                <th>Modal Saham</th>
                <th>Modal Non Saham</th>
                <th>Saldo Laba</th>
                <th>Jumlah Ekuitas Bersih</th>
            </tr>

            <tr>
                <td>Saldo {{ isset($bulan_terpilih) ? date_indo($bulan_terpilih->firstOfMonth()->toDateString()) : '0' }}</td>
                <td>Rp. {{ isset($modal_saham) ? number_format($modal_saham->modal_saham,2) : '0' }}</td>
                <td>Rp. {{ isset($modal_non_saham) ? number_format($modal_non_saham->modal_non_saham,2) : '0' }}</td>
                <td>Rp. {{ isset($saldo_laba) ? number_format($saldo_laba->saldo_laba,2) : '0' }}</td>
                <td>Rp. {{ isset($jumlah_ekuitas) ?number_format($jumlah_ekuitas,2) : '0' }}</td>
            </tr>

            <tr>
                <td>Penambahan modal Persarikatan</td>
                <td>Rp. {{ isset($modal_non_saham_b) ? number_format($modal_saham_b->modal_saham,2) : '0' }}</td>
                <td>Rp. {{ isset($modal_non_saham_b) ? number_format($modal_non_saham_b->modal_non_saham,2) : '0' }}</td>
                <td>Rp. {{ isset($saldo_laba_b) ? number_format($saldo_laba_b->saldo_laba,2) : '0' }}</td>
                <td>Rp. {{ isset($jumlah_ekuitas_b) ? number_format($jumlah_ekuitas_b,2) : '0' }}</td>
            </tr>

            <tr>
                <td>Laba Penjualan Aktiva</td>
                <td>Rp. 0</td>
                <td>Rp. 0</td>
                <td>Rp. {{ isset($saldo_laba_c) ? number_format($saldo_laba_c->saldo_laba,2) : '0' }}</td>
                <td>Rp. {{ isset($saldo_laba_c) ? number_format($saldo_laba_c->saldo_laba,2) : '0' }}</td>
            </tr>

            <tr>
                <td>Laba rugi bersih tahun berjalan</td>
                <td>Rp. 0</td>
                <td>Rp. 0</td>
                @if(isset($laba_rugi))
                @foreach ($laba_rugi as $lr)
                <td>Rp. {{ number_format($lr->laba_rugi,2) }}</td>
                <td>Rp. {{ number_format($lr->laba_rugi,2) }}</td>
                @endforeach
                @else
                <td>Rp. 0</td>
                <td>Rp. 0</td>
                @endif
            </tr>

            <tr>
                <td>Saldo {{ isset($bulan_terpilih) ? date_indo($bulan_terpilih->lastOfMonth()->toDateString()) : '' }}</td>
                <td>Rp. 0</td>
                <td>Rp. 0</td>
                <td>Rp. 0</td>
                {{-- <td>Rp. {{ number_format($saldo_akhir->total,2) }}</td> --}}
                <td>Rp. {{ isset($jumlah_ekuitas) ? number_format( $jumlah_ekuitas + $jumlah_ekuitas_b + $saldo_laba_c->saldo_laba + $laba_rugi[0]->laba_rugi,2 ) : '0' }}</td>
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
        $("#laporan-perubahan-ekuitas").table2excel({
            filename: "laporan-perubahan-ekuitas.xls"
        });
    });
</script>
@endpush
