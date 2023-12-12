@extends('layouts.app')

@section('content')

<style>
    @media print {
       body {
           margin-top: -80px;
       }
    }
    tfoot td {
        font-weight: 500;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Mutasi Pengeluaran Kas</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <div class="page-print">
                <table class="table table-hover" id="laporan-jurnal-umum">
                    <thead>
                        <tr>
                            <th>Tanggal Posting</th>
                            <th>Keterangan</th>
                            <th>Kode Jurnal</th>
                            <th>Kode Rekening</th>
                            <th>COA</th>
                            <th>Unit</th>
                            <th>Cost Center</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $idx => $jurnal)
                            <tr>
                                @if($idx == 0)
                                    <td>{{ date_indo($jurnal->tanggal_posting) }}</td>
                                    <td>{{ $jurnal->keterangan }}</td>
                                    <td>{{ $jurnal->kode_jurnal }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                                <td>{{ $jurnal->kode_rekening }}</td>
                                <td>{{ $jurnal->coa }}</td>
                                <td>{{ $jurnal->unit }}</td>
                                <td>{{ $jurnal->code_cost_centre }}</td>
                                <td class="text-nowrap text-right">{{ number_format($jurnal->debet,2, ",", ".") }}</td>
                                <td class="text-nowrap text-right">{{ nominalTitik($jurnal->kredit,2, ",", ".") }}</td>
                            </tr>
                        @empty
                            <tr>Data tidak ditemukan.</tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="7">Total</td>
                            <td class="text-nowrap text-right">{{ number_format($data->sum('debet'),2, ",", ".") }}</td>
                            <td class="text-nowrap text-right">{{ number_format($data->sum('kredit'),2, ",", ".") }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- <button type="button" align="right" class="btn btn-dark btn-round" id="excel">
            <i class="icon glyphicon glyphicon-file" aria-hidden="true"></i>Excel</button> --}}
            <button class="btn btn-primary btn-round print-link no-print" id="print" >
            <i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Cetak</button>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

    $(document).ready(function () {
        $('#print').click(function (e) {
            e.preventDefault();
            $('.page-print').print({
                // globalStyles: false,
                // mediaPrint: true,
                noPrintSelector: ".no-print",
                prepend : generateHeader()
            })
        });

    // $("#excel").click(function () {
    //     $("#laporan-jurnal-umum").table2excel({
    //         filename: "laporan-jurnal-umum.xls"
    //     });
    // });
    });

    function generateHeader() {
        return `<h3>Mutasi Pengeluaran Kas</h3> <hr>`;
    }
</script>
@endpush

