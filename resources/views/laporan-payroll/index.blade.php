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
    @include('layouts.inc.breadcrumb')
    <h1 class="page-title">Laporan Payroll</h1>
</div>

<div class="page-content">
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
            <div class="form-group">
        </div>
    </div>
</header>

        <div class="panel-body">
        @include('flash-message')
            <form action="{{ url('laporan-payroll/laporan')}}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
		            <label class="col-md-3">Unit</label>
			            <div class="col-md-7">
                            <select name="id_unit" id="id_unit" class="form-control select">
                            <option value="">Pilih Unit</option>
                            @foreach ($unit as $unite)
                            <option value="{{ $unite->id }}">{{ $unite->nama }}</option>
                            @endforeach
			            </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Jenis Data</label>
			            <div class="col-md-7">
                            <select name="jenis_data" id="jenis_data" class="form-control select">
                            <option value="">Pilih Jenis Data</option>
                            <option value="Y">Sudah Dijurnal</option>
                            <option value="N">Belum Dijurnal</option>
			            </select>
		            </div>
	            </div>

                <div class="form-group row">
                    <label class="col-md-3">Tanggal Posting</label>
                        <div class="col-md-7">
                        <input type="date" name="tanggal_posting" id="tanggal_posting" value="{{ date('Y-m-d')}}" class="form-control" required>
                    </div>
                </div>
            <button type="submit" align="right" class="btn btn-primary btn-round" id="cari">
            <i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>
        </form>
    <h3 align="center">Laporan Payroll {{ isset($tanggal_posting) ? date('d-m-Y', strtotime($tanggal_posting)) : '' }}</h3>
</div>

<div style="overflow-x:auto;">
    <table class="table table-hover" id="laporan-neraca-saldo">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Unit</th>
                <th>Nominal</th>
                <th>Pajak</th>
                <th>Adm Bank</th>
                <th>Rekening</th>
                <th>Tanggal Pengiriman</th>
                <th>Detail</th>
                <th>Aksi</th>
            </tr>
            <form action="{{ url('/verifikasi-laporan-payroll') }}" method="post" id="create">{{ @csrf_field() }}

            @php ($i=1)
            @foreach ($data as $jasa)
            <tr>
                <input type="hidden" name="id_payroll[]" value="{{ $jasa->id }}">
                <td>{{ $i }}</td>
                <td>{{ $jasa->pemilik_rekening }}</td>
                <td>{{ $jasa->unit }}</td>
                <td>Rp. {{ number_format($jasa->total_tagihan,2) }}</td>
                <td>Rp. {{ number_format($jasa->pajak,2) }}</td>
                <td>Rp. {{ number_format($jasa->biaya_adm_bank,2) }}</td>
                <td>{{ $jasa->no_rekening }}</td>
                <td>{{ date('d-m-Y', strtotime($jasa->tanggal_transaksi)) }}</td>
                <td><a href='detail/{{ $jasa->id }}' class='btn btn-success btn-xs btn-round'
                data-toggle="tooltip" data-original-title="Detail">
                <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i> Detail</a>
                <td><input type="radio" class="check" name="flag_verif[]{{ $i }}" value="Y">Ya
                <input type="radio" name="flag_verif[]{{ $i }}" value="N" checked>Tidak</td>

            </tr>
            @php($i++)
            @endforeach

            </table>
            @if ($hitung->total == 0)
            @else
            <button type="submit" align="right" class="btn btn-danger btn-sm btn-round">
                <i class="icon glyphicon glyphicon-check" aria-hidden="true"></i>Verifikasi</button>
            @endif
        </div>
    </div>
</div>
@endsection


@push('js')

<script type="text/javascript">

$(".select").select2({
    theme: "bootstrap-5",
	width: '100%'
});

var checks = document.querySelectorAll(".check");
var max = 10;

for (var i = 0; i < checks.length; i++)
    checks[i].onclick = selectiveCheck;

function selectiveCheck (event)
{
    var checkedChecks = document.querySelectorAll(".check:checked");

    if (checkedChecks.length >= max + 1)
    {
        alert("Sudah tidak bisa checklist data");
        return false;
    }
}

</script>
@endpush
