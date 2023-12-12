@extends('layouts.app')

@section('content')

@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <button id="btnCetak" class="btn btn-primary no-print">Cetak</button>
            <button id="btnExcel" class="btn btn-primary no-print">Export Excel</button>
            <hr>
            <div class="container page-print">
                {{-- <div class="page-header"> --}}
                    <h3 class="page-title" style="text-align:center">P\L</h3>
                    <h3 class="page-title" style="text-align:center">{{optional($setting)->nama }}</h3>
                    <h3 class="page-title" style="text-align:center">Periode : {{ date('d-m-Y', strtotime($tanggal_mulai)) }} s/d {{ date('d-m-Y', strtotime($tanggal_selesai)) }} </h3>
                {{-- </div> --}}
                <br>
                <hr>
                <div class="row">
                    <div class="col">
                        <table id="tabel_laporan" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>KETERANGAN</th>
                                    <th class="text-right">NOMINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($laporan))
                                @foreach ($laporan as $lap)
                                    <tr class="font-weight-bold">
                                        <td class="text-left" style="width: 30px !important">{{ $lap->first()->urutan_romawi }}</td>
                                        <td class="text-uppercase">{{ $lap->first()->surplus_defisit }}</td>
                                        <td class="text-right">Rp. {{ number_format($lap->sum('nominal'),2, ",", ".") }}</td>
                                    </tr>
                                    @foreach ($lap as $key => $lapDetail)
                                        @if($lapDetail->surplus_defisit_detail)
                                            <tr>
                                                <td class="text-right" style="width: 40px">{{ isset($lapDetail->urutan_romawi) ? $key + 1 : '' }}</td>
                                                <td class="text-uppercase">{{ $lapDetail->surplus_defisit_detail }}</td>
                                                {{-- <td class="text-right">{{ str_replace('-','',nominalTitik($lapDetail->nominal,false)) }}</td> --}}
                                                <td class="text-right">Rp. {{ number_format($lapDetail->nominal,2, ",", ".") }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

    <script>
        $('#btnCetak').click(function (e) {
            e.preventDefault();
            $('.page-print').print({noPrintSelector: ".no-print"})
        });

        $('#btnExcel').click(function (e) {
            e.preventDefault();
            $("#tabel_laporan").table2excel({
                exclude: ".no-print",
                filename: "laporan-surplus-defisit.xls"
            });
        });
    </script>
@endsection
