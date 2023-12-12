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
    <h1 class="page-title">Mutasi Jurnal</h1>
        <div class="page-header-actions">
    </div>
</div>
@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">

    <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
            <div>
        </div>
    </div>
</header>

        <div class="panel-body">
            <form action="{{ url('mutasi-jurnal/laporan') }}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
		            <label class="col-md-3">Bulan</label>
			            <div class="col-md-7">
			                <select name="bulan" class="form-control" required>
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
                            <option value="">Pilih Semua Tahun</option>
                            @for($i=2020; $i<2050; $i++)
                            <option value="{{ $i }}">{{ $i}} </option>
                            @endfor
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button>
            </form>
        </div>
        <div style="overflow-x:auto;">

            <table class="table table-hover" id="mutasi-jurnal">
                <tr>
                    <th>No</th>
                    <th>Kode Rekening</th>
                    <th>Code Cost Centre</th>
                    <th>Keterangan</th>
                    <th>Debet (Saldo Awal)</th>
                    <th>Kredit (Saldo Awal)</th>
                    <th>Debet (Mutasi)</th>
                    <th>Kredit (Mutasi)</th>
                    <th>Debet (Saldo Akhir)</th>
                    <th>Kredit (Saldo Akhir)</th>
                    <!--<th>Aksi</th>-->
                </tr>


            @if (isset($data))

                @php ($total_debet_saldo_akhir=0)
                @php ($total_kredit_saldo_akhir=0)
                @foreach ($data as $key=>$rekap)
                <tr>
                    <td>{{ ++$key}}</td>
                    <td>{{ $rekap->kode_rekening }}</td>
                    <td>{{ $rekap->code_cost_centre }}</td>
                    <td>{{ $rekap->perkiraan }} - {{ $rekap->unit }}</td>
                    <td>Rp. {{ number_format($rekap->debet_saldo_awal,2) }}</td>
                    <td>Rp. {{ number_format($rekap->kredit_saldo_awal,2) }}</td>
                    <td>Rp. {{ number_format($rekap->debet_mutasi,2) }}</td>
                    <td>Rp. {{ number_format($rekap->kredit_mutasi,2) }}</td>
                    <?php
                        $debet_saldo_akhir = $rekap->debet_saldo_awal - $rekap->debet_mutasi;
                        $kredit_saldo_akhir = $rekap->kredit_saldo_awal - $rekap->kredit_mutasi;
                    ?>
                    <td>Rp. {{ number_format($debet_saldo_akhir,2) }}</td>
                    <td>Rp. {{ number_format($kredit_saldo_akhir,2) }}</td>


                    @php ($total_debet_saldo_akhir += $debet_saldo_akhir)
                    @php ($total_kredit_saldo_akhir += $kredit_saldo_akhir)
                </tr>
                @php($i++)
                @endforeach
                <tr>
                    <td><b>Total </b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Rp. {{ number_format($debetSaldoAwal,2, ",", ".") }}</td>
                    <td>Rp. {{ number_format($kreditSaldoAwal,2, ",", ".") }}</td>
                    <td>Rp. {{ number_format($debetMutasi,2, ",", ".") }}</td>
                    <td>Rp. {{ number_format($kreditMutasi,2, ",", ".") }}</td>

                    <td>Rp. {{ number_format($total_debet_saldo_akhir,2, ",", ".") }}</td>
                    <td>Rp. {{ number_format($total_kredit_saldo_akhir,2, ",", ".") }}</td>
                </tr>

            @endif
            </table>
            <button type="button" align="right" class="btn btn-success" id="excel">Excel</button>
        </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('js')
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">

$("#excel").click(function () {
        $("#mutasi-jurnal").table2excel({
            filename: "mutasi-jurnal.xls"
        });
    });
</script>
@endpush
