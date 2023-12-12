@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Sales Report</h1>
    <div class="page-header-actions">
     <a class="btn btn-block btn-primary data-modal" href="form">Tambah</a>
     </div>
</div>
  @include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <form action="{{ url('sales-report/pencarian') }}" method="POST" id="laporan">{{ @csrf_field() }}

            <div class="form-group row">
                <label class="col-md-3">Bulan</label>
                    <div class="col-md-7">
                        <select name="bulan" class="form-control select">
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

            <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-search"></i>Cari</button>

        <h3 align="center">Sales Report<br/>{{ optional($setting)->nama }}<br/>Periode {{$tanggal_awal}} s/d {{$tanggal_abis}}</h3>
            <table class="table table-hover" id="laporan-perubahan-ekuitas">
                <tr>
                    <th>Bulan Satu</th>
                    <th>Bulan Dua</th>
                    <th>Billed</th>
                    <th>Persen Billed</th>
                    <th>Dispute</th>
                    <th>Total</th>
                </tr>

                @foreach($data as $lap)
                <tr>
                    <td>{{ $lap->bulan_satu }}</td>
                    <td>{{ $lap->bulan_dua }}</td>
                    <td>{{ $lap->BILLED }}</td>
                    <td>{{ $lap->KETERANGAN }}</td>
                    <td>{{ $lap->PERSEN_BILLED }}</td>
                    <td>Rp. {{ number_format($lap->total) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">


</script>
@endpush

