@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Aktiva Tetap</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value="Aktiva Tetap : {{ optional($master)->nama }}" readonly>
		        </div>
	        </div>

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value="Kode : {{ optional($master)->kode }}" readonly>
		        </div>
	        </div>

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value="Kelompok Aktiva : {{ optional($master)->kelompok_aktiva }}" readonly>
		        </div>
	        </div>

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value="Tanggal Pembelian : {{ date('d-m-Y', strtotime($master->tanggal_pembelian)) }}" readonly>
		        </div>
	        </div>

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value='Harga Perolehan : Rp. {{ number_format(optional($master)->harga_perolehan,2, ",", ".") }}' readonly>
		        </div>
	        </div>

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value='Nilai Residu : Rp. {{ number_format(optional($master)->nilai_residu,2, ",", ".") }}' readonly>
		        </div>
	        </div>

            <div class="form-group row">
			    <div class="col-md-3">
			    <input type="text" class="form-control" value="Umur Ekonomis : {{ optional($master)->umur_ekonomis }}" readonly>
		        </div>
	        </div>

            <table class="table table-hover" id="detail">
                <tr>

                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Penyusutan</th>
                    <th>Nilai Buku</th>
                </tr>

                @foreach($barisSatu as $key=>$d)
                <tr>

                    <td>{{ $d->tahun }}</td>
                    <td>{{ $d->bulan }}</td>
                    <td>Rp. {{ number_format($d->penyusutan) }}</td>
                    <td>Rp. {{ number_format($d->nilai_buku) }} </td>
                </tr>
                @endforeach

                @foreach($barisTiga as $key=>$d)
                <tr>

                    <td>{{ $d->tahun }}</td>
                    <td>{{ $d->bulan }}</td>
                    <td>Rp. {{ number_format($d->penyusutan) }}</td>
                    <td>Rp. {{ number_format($d->nilai_buku) }} </td>
                </tr>
                @endforeach
	        </table>
            <button class="btn btn-success" onclick="tableToExcel(['detail'], 'Detail Aktiva Tetap')"><i class="icon glyphicon glyphicon-file"></i>Excel</button>
            <button class="btn btn-danger print-link no-print" id="print" ><i class="icon glyphicon glyphicon-print"></i>Cetak</button>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>

<script type="text/javascript">

var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
      return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        window.location.href = uri + base64(format(template, ctx))
    }
})()

$(document).ready(function () {
    $('#print').click(function (e) {
        e.preventDefault();
        $('#detail').print({
            noPrintSelector: ".no-print",
            //prepend : generateHeader()
        })
    });
});
</script>
