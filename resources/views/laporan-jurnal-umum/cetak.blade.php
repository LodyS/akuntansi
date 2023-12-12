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
    <h1 class="page-title">Laporan Jurnal Umum</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">


        <h3 align="center">LAPORAN JURNAL UMUM<br/>
        {{ optional($setting)->nama }}<br/>
        tanggal {{ isset($tanggal_mulai) ? date('d-m-Y', strtotime($tanggal_mulai)) : '' }} s/d {{ isset($tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_selesai)) : ''}}</h4>
        <br/>
        <div style="overflow-x:auto;">
            <table class="table table-hover" id="laporan-jurnal-umum">
                <thead>
                    <tr>
                        <th>Tanggal Posting</th>
                        <th>Keterangan</th>
                        <th>Kode Jurnal</th>
                        <th>Kode Rekening</th>
                        <th>Keterangan</th>
                        <th>Code Cost Centre</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th></th>

                    </tr>
                    @if(isset($rekapitulasi))

                    @php ($jurnal_kode = null)

                    @foreach ($rekapitulasi as $rekap)
                        @if ($loop->index > 0 && $jurnal_kode != $rekap->kode_jurnal)
                            @include ('laporan-jurnal-umum/balance', compact('rekapitulasi', 'jurnal_kode'))
                        @endif

                    <tr>
                    @php ($jurnal_kode = $rekap->kode_jurnal)


                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? date('d-m-Y', strtotime($rekap->tanggal_posting)) : '' }}</b></td>
                        @elseif ($rekap->layer ==1 && $rekap->urutan==1 )
                            <td><b>{{ date('d-m-Y', strtotime($rekap->tanggal_posting))   }}</b></td>
                        @else
                        <td></td>
                        @endif


                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? $rekap->keterangan : '' }}</b></td>
                        @elseif ($rekap->layer ==1 &&  $rekap->urutan==1 )
                            <td><b>{{ $rekap->keterangan  }}</b></td>
                        @else
                        <td></td>
                        @endif

                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? $rekap->kode_jurnal : '' }}</b></td>
                        @elseif ($rekap->layer !== null && $rekap->layer ==1 && $rekap->urutan !== null && $rekap->urutan==1 )
                            <td><b>{{ $rekap->kode_jurnal  }}</b></td>
                        @else
                        <td></td>
                        @endif


                        <td>{{ $rekap->kode_rekening }}</td>
                        <td>{{ $rekap->nama }} - {{ $rekap->unit }}</td>
                        <td>{{ $rekap->code_cost_centre }}</td>
                        <td>Rp. {{ number_format($rekap->debet,2) }}</td>
                        <td>Rp. {{ number_format($rekap->kredit,2) }}</td>
                        <td></td>
                        <td></td>




                        </td>
                    </tr>
                    @if ($loop->last)
                        @include ('laporan-jurnal-umum/balance', compact('rekapitulasi', 'jurnal_kode'))
                    @endif

                    @endforeach

                    <tr>
                        <td><b>Total</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($debet,2) }}</b></td>
                        <td><b>Rp. {{ number_format($kredit,2) }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><b>Balance</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($balance,2) }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif

                </table>
                <button type="button" align="right" class="btn btn-dark btn-round" id="excel">
                <i class="icon glyphicon glyphicon-file" aria-hidden="true"></i>Excel</button>
                <button class="btn btn-danger btn-round print-link no-print" id="print" >
                <i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Cetak</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"
integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous"
referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>

<script type="text/javascript">
$(".select").select2({
    dropdownParent : $("#laporan"),
    theme: 'bootstrap4',
    width : '100%'
});

$("#excel").click(function () {
    $("#laporan-jurnal-umum").table2excel({
        filename: "laporan-jurnal-umum.xls"
    });
});

        $(document).ready(function () {
            $('#print').click(function (e) {
                e.preventDefault();
                $('.page-print').print({
                    noPrintSelector: ".no-print",
                    prepend : generateHeader()
                })
            });
        });

        function generateHeader () {

        }


</script>
@endpush

