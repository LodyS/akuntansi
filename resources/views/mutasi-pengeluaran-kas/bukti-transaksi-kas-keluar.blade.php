@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Mutasi Pengeluaran Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="p-10">
            @if($mutasiKas->flag_bayar == 'Y')
                <button id="btnCetak" class="btn btn-primary no-print">Cetak</button>
            @endif
            <hr>
        </div>

        <div class="panel-body">
		    @include('flash-message')
                <h3 align="center">Bukti Pengeluaran Kas</h3><br/>

                    <div class="form-group row">
		                <label class="col-md-3">No Bukti</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control btn-round" value="{{ optional($mutasiKas)->kode }}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
                            <input type="date" class="form-control btn-round" value="{{ optional($mutasiKas)->tanggal }}" readonly>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Diterima Dari</label>
                            <div class="col-md-7">
                            <input type="text" value="{{ optional($mutasiKas)->penerima }}" class="form-control btn-round" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
			            <label class="col-md-3">Keterangan</label>
				            <div class="col-md-7">
				            <input type="text" value="{{ optional($mutasiKas)->keterangan }}" class="form-control btn-round" readonly>
			            </div>
		            </div>

                    <div class="page-print">
                        <table class="table table-hover">
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Cost Center</th>
                                <th>Rekening</th>
                                <th>Keterangan</th>
                                <th>Nominal</th>
                            </tr>

                            @forelse ($mutasiKasDetail as $key=> $data)
                            <tr>
                                <td>{{ ++$key}}</td>
                                <td>{{ $data->unit }}</td>
                                <td>{{ $data->code_cost_centre }}</td>
                                <td>{{ $data->rekening }}</td>
                                <td>{{ $data->keterangan }}</td>
                                <td>Rp. {{ number_format($data->nominal,2, ",", ".") }}</td>
                            </tr>
                            @empty

                            @endforelse

                            <tr>
                                <td><b>Total</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Rp. {{ number_format($total,2, ",", ".") }}</b></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#btnCetak').click(function (e) {
                e.preventDefault();
                $('.page-print').print({
                    noPrintSelector: ".no-print",
                    prepend : generateHeader()
                })
            });
        });

        function generateHeader () {
            return `
                <h3 align="center">Bukti Pengeluaran Kas</h3><br/>
                <table>
                    <tr>
                        <td style="width: 150px">No Bukti</td>
                        <td>: {{ optional($mutasiKas)->kode }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ optional($mutasiKas)->tanggal }}</td>
                    </tr>
                    <tr>
                        <td>Diterima Oleh</td>
                        <td>: {{ optional($mutasiKas)->penerima }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ optional($mutasiKas)->keterangan }}</td>
                    </tr>
                </table>
                <br>`;
        }
    </script>
@endpush
