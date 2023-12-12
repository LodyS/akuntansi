@extends('layouts.app')

@section('content')

<style>
.judul { font-weight: bold; }
</style>

<div class="page-header">

</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ url('neraca-laporan/laporan') }}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
		            <label class="col-md-3">Bulan</label>
			            <div class="col-md-7">
			                <select name="bulan" id="bulan" class="form-control" required>
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
			                <select name="tahun" id="tahun" class="form-control" required>
                            <option value="">Pilih</option>
                            @for($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                            <option value="{{ $i }}">{{ $i}} </option>
                            @endfor
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button>
            </form>
        </div>

            <div class="container">
            <h3 align="center">BALANCE SHEET <br/>
            {{optional($setting)->nama }}<br/> {{ $tanggal_awal }} s/d {{ $tanggal_abis }}</h3>
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
                                    @if($a->nama !== 'TOTAL ASET')
                                    <td>{{ $a->nama }}</td>
                                    @endif

                                    @if($a->nama !== 'TOTAL ASET')
                                    @php ($nominal = $a->saldo + $a->total)
                                    <td class="text-nowrap text-right">{{ ($nominal  !==0) ? number_format($a->saldo + $a->total,2, ",", ".") : 0 }}</td>
                                    @endif
                                </tr>
                            @endforeach

                                @for($i=0; $i < $selisihPassiva; $i++)
                                    <tr>
                                        <td>&nbsp;&nbsp;</td>
                                        <td> </td>
                                    </tr>
                                @endfor

                                <tr style="font-weight: {{ $a->total > 0 ? 'bold' : 'normal' }}">
                                    <td>TOTAL ASET </td>
                                    <td class="text-nowrap text-right">Rp. {{ number_format($totalAset,2, ",", ".") }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td></td>
                                    <td class="text-right">Rp. 0,00</td>
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
                            @foreach($passiva as $pass)
                                <tr style="font-weight: {{ $pass->total > 0 ? 'bold' : 'normal' }}">
                                    @if ($pass->passiva !== 'TOTAL MODAL DAN LIABILITAS')
                                    <td>{{ $pass->passiva}}</td>
                                    @endif

                                    @if ($pass->passiva !== 'TOTAL MODAL DAN LIABILITAS')
                                    <td class="text-nowrap text-right">Rp. {{ ($pass->total !== null) ? number_format($pass->saldo_passiva + $pass->total,2,",", ".") : 0 }}</td>

                                    @endif
                                </tr>
                                @endforeach


                                @for($i=0; $i < $selisihAktiva; $i++)
                                    <tr>
                                        <td>&nbsp;&nbsp;</td>
                                        <td> </td>
                                    </tr>
                                @endfor

                                <tr style="font-weight: {{ $a->total > 0 ? 'bold' : 'normal' }}">
                                    <td>TOTAL MODAL DAN LIABILITAS </td>
                                    <td class="text-nowrap text-right">Rp. {{ number_format($saldo_passiva,2, ",", ".") }}</td>
                                </tr>


                                @else
                                <tr>
                                    <td></td>
                                    <td class="text-right">Rp. 0,00</td>
                                </tr>
                                @endif
                                </tbody>
                            </table>
                        <button type="button" class="btn btn-info float-right" onclick="exportExcelManual()">Export Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')


<script>
    function exportExcelManual () {
        const tabelExport = document.createElement("table");
        tabelExport.innerHTML = `
        <table>
        <tr>
        <th></th>
        <th></th>
        <th  text-align="center"><b><p align="center">RS<br/> NERACA</p><br/>BULAN {{ isset($bulan_indonesia) ? strtoupper($bulan_indonesia) : '' }} TAHUN {{ isset($tahun) ? $tahun : '' }}</b></th>
        <th></th>
        <th></th>
        </tr>
        <tr>
            <td>AKTIVA</td>
            <td>SALDO</td>
            <td></td>
            <td>PASSIVA</td>
            <td>SALDO</td>
        </tr>
        </table>
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
