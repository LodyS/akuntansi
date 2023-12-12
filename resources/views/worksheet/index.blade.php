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
    <h1 class="page-title">Worksheet</h1>
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

    <form action="{{ url('worksheet/laporan')}}" method="POST">{{ @csrf_field() }}

<div class="form-group row">
    <label class="col-md-3">Tanggal Mulai</label>
        <div class="col-md-7">
        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ date('Y-m-d')}}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3">Tanggal Selesai</label>
        <div class="col-md-7">
        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ date('Y-m-d')}}" class="form-control">
    </div>
</div>

    <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button>
        <h3 align="center">WORKSHEET <br/>{{ optional($setting)->nama }}<br/>tanggal
        {{isset($tanggal_mulai) ?date('d-m-Y', strtotime($tanggal_mulai)) : '' }} S/D {{ isset($tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_selesai)) : '' }}</h3>

<div style="overflow-x:auto;">
    <table class="table table-hover bb" id="worksheet">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Rekening</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Debet Peny</th>
                <th>Kredit Peny</th>
                <th>Debet Setelah Peny</th>
                <th>Kredit Setelah Peny</th>
                <th>Debet L/R</th>
                <th>Kredit L/R</th>
                <th>Debet Neraca</th>
                <th>Kredit Neraca</th>
            </tr>
            @if(isset($data))


            @foreach ($data as $key=>$worksheet)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $worksheet->kode_rekening }}</td>
                <td>{{ $worksheet->Rekening }}</td>
                <td>Rp. {{ number_format($worksheet->DEBET,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->KREDIT,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->DEBET_ADJ,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->KREDIT_ADJ,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->debit_after_adj,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->kredit_after_adj,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->debet_laba_rugi,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->kredit_laba_rugi,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->debet_neraca,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($worksheet->kredit_neraca,2, ",", ".") }}</td>

            </tr>
            @endforeach
                <td>Total</td>
                <td></td>
                <td></td>
                <td>Rp. {{ number_format($total_debet,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_kredit,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_debet_adj,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_kredit_adj,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_debet_after_adj,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_kredit_after_adj,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_debet_laba_rugi,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_kredit_laba_rugi,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_debet_neraca,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($total_kredit_neraca,2, ",", ".") }}</td>
                @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @endif
            </table>
                <button type="button" align="right" class="btn btn-outline-success" id="excel">Excel</button>
                <button class="btn btn-outline-danger print-link no-print" onclick="jQuery('#worksheet').print()">Cetak </button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">

$("#excel").click(function () {
        $("#worksheet").table2excel({
            filename: "worksheet.xls"
        });
    });
</script>
@endpush


