@extends('layouts.app')

@section('content')

<style>
.highcharts-figure,
.highcharts-data-table table {
    min-width: 320px;
    max-width: 660px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
</style>

<div class="container-fluid" >
    <div class="row justify-content-center" >
        <div class="col-md-18" style="width:1600px">
            <div class="card">
                <div class="card-header">

                    <h4 align="center">DASHBOARD<br/>{{ optional($setting)->nama }}
                        <br/>{{ optional($setting)->alamat }} Telp. {{ optional($setting)->telepon}} Kode Pos {{ optional($setting)->kode_pos}}</h4></div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- awal laporan keuangan -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                <img src="banner/4.png" class="mw-100" width="90px" align="right">
                                    <h5 class="card-title">LABA</h5>
                                    <p class="card-text" style="{{ ($totalProfit >0) ? 'color:#4CAF50;' : 'color:red;' }}"><i class="{{ ($totalProfit >0) ? 'bi bi-graph-up-arrow' : 'bi bi-graph-down-arrow' }}"></i>&nbsp;&nbsp;<b>Rp. {{ isset($totalProfit) ? number_format($totalProfit) : '0' }}</b></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card aktiva">
                                <div class="card-body">
                                <img src="banner/asset.png" class="mw-100" width="90px" align="right">
                                    <h5 class="card-title"><b>TOTAL ASET</b></h5>
                                    <p class="card-text" style="{{ ($totalAset >0) ? 'color:#4CAF50;' : 'color:red;' }}"><i class="{{ ($totalAset >0) ? 'bi bi-graph-up-arrow' : 'bi bi-graph-down-arrow' }}"></i>&nbsp;&nbsp;<b>Rp. {{ isset($totalAset) ? number_format($totalAset) : '0' }}</b></p>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                <img src="banner/1.png" class="mw-100" width="90px" align="right">
                                    <h5 class="card-title"><b>TOTAL PASSIVA</b></h5>
                                    <p class="card-text" style="{{ ($totalPassiva >0) ? 'color:#4CAF50;' : 'color:red;' }}"><i class="{{ ($totalPassiva >0) ? 'bi bi-graph-up-arrow'  : 'bi bi-graph-down-arrow' }}"></i>&nbsp;&nbsp;<b>Rp. {{ isset($totalPassiva) ? number_format($totalPassiva) : '0' }}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('flash-message')
                    <h3 align="centre">Pencarian Laba Rugi</h3>
                    <form action="{{ url('/pencarian-laba')}}" method="post">{{ @csrf_field() }}

                    <div class="form-group row">
		                <label class="col-md-3">Bulan</label>
			                <div class="col-md-7">
			                    <select name="bulan" class="form-control select" required>
                                <option value="">Untuk filter liabilitas</option>
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
                                @for($i=2020; $i<2050; $i++)
                                <option value="{{ $i }}">{{ $i}} </option>
                                @endfor
                            </select>
		                </div>
	                </div>

                    <button type="submit" align="right" class="btn btn-primary btn-round" id="cari">
                <i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>
                </form>


                    <div id="linechart" style="width: 1500px; height: 500px"></div>
                    <!-- Akhir laporan keuangan -->

                            <div id="pie-chart"></div></div>
                            <!-- Pie chart liabilitas -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

@push('js')
<script type="text/javascript">
var laba = <?php echo $laba; ?>;
    console.log(laba);

    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(lineChart);

function lineChart() {
    var data = google.visualization.arrayToDataTable(laba);
    var options = {
            title: 'Grafik Laba per bulan',
            curveType: 'function',
            legend: {
            position: 'bottom'
        }
    };

    var chart = new google.visualization.LineChart(document.getElementById('linechart'));
    chart.draw(data, options);
}

$(function() {

    Highcharts.setOptions({
        colors: ['#0000FF', '#FF0000']
    });

    Highcharts.chart('pie-chart', {
        chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'

            },

            title: {
                text: 'Liabilitas'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.nominal}</b>'
            },

            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    color : [
                        '#FFF263',
                        '#6AF9C4'
                    ],
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.nominal} '
                }
            }
        },
        series: [{
            name: 'Liabilitas',
            colorByPoint: true,
            data: <?= $data ?>
        }]
    });
});
</script>
@endpush
