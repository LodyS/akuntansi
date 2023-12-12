@extends('layouts.app')

@section('content')

<style>
.judul { font-weight: bold; }
</style>

<div class="page-header">
    <h1 class="page-title">Laporan Neraca Bulan : {{ isset($bulan_indonesia) ? $bulan_indonesia : '' }} {{ isset($tahun) ?  $tahun : '' }}</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ url('neraca-laporan/laporan') }}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
		            <label class="col-md-3">Bulan</label>
			            <div class="col-md-7">
			                <select name="bulan" id="bulan" class="form-control btn-round" required>
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
			                <select name="tahun" id="tahun" class="form-control btn-round" required>
                            <option value="">Pilih</option>
                            @for($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                            <option value="{{ $i }}">{{ $i}} </option>
                            @endfor
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary btn-round" id="cari">Cari</button>
            </form>
        </div>

<div class="container">
    <div class="row">

        <div class="col-md-6 pb-30">
            <table class="table table-hover aktiva" id="aktiva">
                <thead>
                    <tr>
                        <th><span class="judul">AKTIVA</span></th>
                        <th class="text-right"><span class="judul">SALDO</span></th>
                    </tr>
                </thead>

                <tbody>
                    @if(isset($aktiva))
                    @foreach($aktiva as $a)
                    <tr style="font-weight: {{ $a->total > 0 ? 'bold' : 'normal' }}">

                        <td>{{ $a->nama }}</td>
                        @php ($nominal = $a->saldo + $a->total)
                        <td class="text-nowrap text-right">{{ ($nominal  !==0) ? nominalTitik($a->saldo + $a->total, false) : 0 }}</td>
                    </tr>

                @endforeach
                @else
                    <tr>
                        <td></td>
                        <td class="text-right">0</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

                        <div class="col-md-6 pb-30">
                            <table class="table table-hover passiva" id="passiva">
                                <thead>
                                    <tr>
                                        <th><span class="judul">PASSIVA</span></th>
                                        <th class="text-right"><span class="judul">SALDO</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($passiva))
                                @forelse($passiva as $pass)
                                    <tr style="font-weight: {{ $pass->total_passiva > 0 ? 'bold' : 'normal' }}">
                                        <td>{{ $pass->passiva}}</td>
                                        <td class="text-nowrap text-right">{{ ($pass->total !== null) ? nominalTitik($pass->total_passiva,false) : 0 }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td></td>
                                        <td class="text-right">0</td>
                                    </tr>
                                    @endforelse
                                    @else
                                    <tr>
                                        <td></td>
                                        <td class="text-right">0</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        <input id="ExporttoExcel" class="show" type="button" onclick="toExcel()" value="Export to Excel">
                        <button type="button" class="btn btn-info" onclick="exportExcelManual()">Export Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
function toExcel() {

var cache = {};
this.tmpl = function tmpl(str, data) {
    var fn = !/\W/.test(str) ? cache[str] = cache[str] || tmpl(document.getElementById(str).innerHTML) :
    new Function("obj",
                 "var p=[],print=function(){p.push.apply(p,arguments);};" +
                 "with(obj){p.push('" +
                 str.replace(/[\r\t\n]/g, " ")
                 .split('@{{').join('\t').replace(/((^|}})[^\t]*)'/g, "$1\r")
                 .replace(/\t=(.*?)}}/g, "',$1,'")
                 .split("\t").join("');")
                 .split("}}").join("p.push('")
                 .split("\r").join("\\'") + "');}return p.join('');");
    return data ? fn(data) : fn;
};
var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>@{{=worksheet}}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>@{{for(var i=0; i<tables.length;i++){ }}<table>@{{=tables[i]}}</table>@{{ } }}</body></html>',
        base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)));
        },
        format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            });
        };
    return function (tableList, name) {
        if (!tableList.length > 0 && !tableList[0].nodeType) table = document.getElementById(table);
        var tables = [];
        for (var i = 0; i < tableList.length; i++) {
            tables.push(tableList[i].innerHTML);
        }
        var ctx = {
            worksheet: name || 'Worksheet',
            tables: tables
        };
        window.location.href = uri + base64(tmpl(template, ctx));
    };
})();
tableToExcel(document.getElementsByTagName("table"), "one");
}
</script>

<script>
    function exportExcelManual () {
        const tabelExport = document.createElement("table");
        tabelExport.innerHTML = `
        <tr>
            <td>AKTIVA</td>
            <td>SALDO</td>
            <td></td>
            <td>PASSIVA</td>
            <td>SALDO</td>
        </tr>
        `;

        const rowAktive = $('#aktiva tbody tr');
        const rowPasiva = $('#passiva tbody tr');

        const maxRow = rowAktive.length > rowPasiva.length ? rowAktive.length : rowPasiva.length;

        for (let index = 0; index < maxRow; index++) {
            const tdSpace = document.createElement("td");
            let newRow = document.createElement("tr");

            if (typeof rowAktive[index] === 'undefined') {
                newRow.append(tdSpace);
                newRow.append(tdSpace);
            } else {
                newRow.append(...rowAktive[index].cloneNode(true).children);
            }

            newRow.append(tdSpace);

            if (typeof rowPasiva[index] === 'undefined') {
                newRow.append(tdSpace);
                newRow.append(tdSpace);
            } else {
                newRow.append(...rowPasiva[index].cloneNode(true).children);
            }

            $(tabelExport).append(newRow);
        }

        $(tabelExport).table2excel({
            filename: "laporan-neraca.xls"
        });
}
</script>

@endpush
