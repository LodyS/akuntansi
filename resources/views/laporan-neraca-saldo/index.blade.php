@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Trial Balance</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <form action="{{ url('laporan-neraca-saldo/laporan') }}" method="POST" id="laporan">{{ @csrf_field() }}

                <div class="form-group row">
		            <label class="col-md-3">Bulan</label>
			            <div class="col-md-7">
			                <select name="bulan" class="form-control select" required>
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
			                <select name="tahun" id="tahun" class="form-control select" required>
                            <option value="">Pilih</option>
                            <option value="">Pilih Semua Tahun</option>
                            @for($i=2020; $i<2050; $i++)
                            <option value="{{ $i }}">{{ $i}} </option>
                            @endfor
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Kode Cost Centre</label>
			            <div class="col-md-7">
			                <select name="id_unit" id="id_unit" class="form-control select">
                            <option value="">Pilih</option>
                            @foreach($unit as $unitt)
                            <option value="{{ $unitt->id }}">{{ $unitt->code_cost_centre }} - {{ $unitt->nama }} </option>
                            @endforeach
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Rekening</label>
			            <div class="col-md-7">
			                <select name="id_perkiraan" id="id_perkiraan" class="form-control select">
                            <option value="">Pilih</option>
                            @foreach($perkiraan as $kira)
                            <option value="{{ $kira->id }}">{{ $kira->kode_rekening }} - {{ $kira->nama }}</option>
                            @endforeach
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary btn-round" id="cari">
                <i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>

            </form><br/><br/>
            <h3 align="center">TRIAL BALANCE<br>{{ optional($setting)->nama }}<br/>
            Per {{ isset($tanggal_akhir) ? $tanggal_akhir : '' }}</h3><br/>
            <table class="table table-hover" id="laporan-neraca-saldo">
                <tr>
                    <th>No</th>
                    <th>Account</th>
                    <th>COST</th>
                    <th>Chart Of Account</th>
                    <th>Keterangan</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                </tr>


            @if (isset($data))


                @foreach ($data as $key=> $rekap)
                <tr>
                    <td>{{ ++$key}}</td>
                    <td>{{ $rekap->kode }}</td>
                    @if (substr(preg_replace('/[^0-9]/','', $rekap->kode),0, 1) == 1 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode),0, 1) == 2 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode),0, 1) == 3)

                    <td>NER</td>
                    @else
                    <td>{{ $rekap->code_cost_centre }}</td>
                    @endif

                    @if(substr(preg_replace('/[^0-9]/','', $rekap->kode),0, 1) == 1 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode),0, 1) == 2 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode),0, 1) == 3)
                    <td>{{ $rekap->kode }}</td>
                    @else
                    <td>{{ $rekap->code_cost_centre }} - {{ $rekap->kode }}</td>
                    @endif

                    <td>{{ $rekap->unit }} - {{ $rekap->perkiraan }}</td>
                    <td>Rp. {{ number_format($rekap->debet,2, ",", ".") }}</td>
                    <td>Rp. {{ number_format($rekap->kredit,2, ",", ".") }}</td>

                </tr>

                @endforeach

                <tr>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Rp. {{ number_format($total_debet,2, ",", ".") }}</b></td>
                    <td><b>Rp. {{ number_format($total_kredit,2, ",", ".") }}</b></td>
                </tr>

                <tr>
                    <td><b>Laporan DPPK Berjalan</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Rp. {{ number_format( optional($dppk)->total,2, ",", ".") }}</b></td>
                    <!-- Jika variabel ddpk ada maka akan menampilkan total, jika tidak maka akan menghasilkan nilai nol-->
                </tr>
            @endif

            </table>
            <button type="button" align="right" class="btn btn-dark btn-round" id="excel">
            <i class="icon glyphicon glyphicon-file" aria-hidden="true"></i>Excel</button>
            <button class="btn btn-danger btn-round print-link no-print" onclick="jQuery('#laporan-neraca-saldo').print()">
            <i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Cetak</button>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"
integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous"
referrerpolicy="no-referrer"></script>

<script type="text/javascript">
$(".select").select2({
    dropdownParent : $("#laporan"),
    theme: 'bootstrap4',
    width : '100%'
});

$("#excel").click(function () {
    $("#laporan-neraca-saldo").table2excel({
        filename: "laporan-trial-balance.xls"
    });
});

</script>
@endpush
