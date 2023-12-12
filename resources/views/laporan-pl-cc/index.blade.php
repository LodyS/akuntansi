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
                    <h3 class="page-title" style="text-align:center">RUMAH SAKIT</h3>
                    <h3 class="page-title" style="text-align:center">LAPORAN PROFIT & LOSS CC</h3>
                    <h3 class="page-title" style="text-align:center">Periode : {{ date('d-m-Y', strtotime($tanggal_mulai)) }} s/d {{ date('d-m-Y', strtotime($tanggal_selesai)) }} </h3>
                {{-- </div> --}}
                <br>
                <hr>

                <div class="panel-body">
            <form action="{{ url('laporan-pl-cc/laporan') }}" method="POST">
                @csrf
                <br>
                <div class="form-group row">
                    <label class="col-md-3">Mulai Tanggal</label>
                    <div class="col-md-7">
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Sampai Tanggal</label>
                    <div class="col-md-7">
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Code Cost Centre</label>
                    <div class="col-md-7">
                       <select class="form-control" name="id_unit">
                        <option value="">Pilih Unit</option>
                        @foreach($unit as $u)
                        <option value="{{$u->id}}">{{ $u->code_cost_centre}} - {{ $u->nama }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" align="right" class="btn btn-primary" id="cari">Tampilkan</button>

            </form>
        </div>

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
                            @foreach ($laporan as $lap)
                                    <tr class="font-weight-bold">
                                        <td class="text-left" style="width: 30px !important">{{ $lap->first()->urutan_romawi }}</td>
                                        <td class="text-uppercase">{{ $lap->first()->surplus_defisit }}</td>
                                        <td class="text-right">{{ number_format($lap->sum('nominal'),2,",",".") }}</td>
                                    </tr>
                                    @foreach ($lap as $key => $lapDetail)
                                        @if($lapDetail->surplus_defisit_detail)
                                            <tr>
                                                <td class="text-right" style="width: 40px">{{ isset($lapDetail->urutan_romawi) ? $key + 1 : '' }}</td>
                                                <td class="text-uppercase">{{ $lapDetail->surplus_defisit_detail }}</td>
                                                <td class="text-right">{{ number_format($lapDetail->nominal,2,",", ".") }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
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
