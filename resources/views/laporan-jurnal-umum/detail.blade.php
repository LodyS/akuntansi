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
    <h1 class="page-title">Detail Laporan Jurnal Umum</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <div class="form-group row">
            <label class="col-md-3">Tanggal</label>
                <div class="col-md-7">
                <input type="text" value="{{ date('d-m-Y', strtotime( isset($jurnal) ? $jurnal->tanggal_posting : '')) }}" class="form-control btn-round" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Tipe Jurnal</label>
                <div class="col-md-7">
                <input type="text" value="{{ optional($jurnal)->tipe_jurnal }}" class="form-control btn-round" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Keterangan</label>
                <div class="col-md-7">
                <input type="text" value="{{ optional($jurnal)->keterangan }}" class="form-control btn-round" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Kode Jurnal</label>
                <div class="col-md-7">
                <input type="text" value="{{ optional($jurnal)->kode_jurnal }}" class="form-control btn-round" readonly>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="table table-hover" id="laporan-jurnal-umum">
                <thead>
                    <tr>
                        <th>Kode Dokter</th>
                        <th>Nama Dokter</th>
                        <th>Invoice</th>
                        <th>Kode Rekening</th>
                        <th>Code Cost Centre</th>
                        <th>Keterangan</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                    </tr>

                    @foreach ($data as $rekap)
                    <tr>
                        <td>{{ $rekap->kode }}</td>
                        <td>{{ $rekap->nakes }}</td>
                        <td>{{ $rekap->invoice }}</td>
                        <td>{{ $rekap->kode_rekening }}</td>
                        <td>{{ $rekap->code_cost_centre }}</td>
                        <td>{{ $rekap->rekening }} - {{ $rekap->unit }}</td>
                        <td>Rp. {{ number_format($rekap->debet,2) }}</td>
                        <td>Rp. {{ number_format($rekap->kredit,2) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



