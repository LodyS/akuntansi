@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Cash Flow</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">Parameter Pencarian</h3>
        </div>

        <div class="panel-body">

            <form action="{{ url('laporan-cash-flow/laporan') }}" method="POST">{{ @csrf_field() }}
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
		            <label class="col-md-3">Bank</label>
			            <div class="col-md-7">
			                <select name="id_bank" id="id_bank" class="form-control select" required>
                            <option value="">Pilih</option>
                            @foreach($kasBank as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }} </option>
                            @endforeach
                        </select>
		            </div>
	            </div>

                <div class="text-right">
                    <button class="btn btn-primary">Cari</button>
                </div>
            </form>

        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
                <h4 align="center">
                    LAPORAN ARUS KAS<br/>
                    BULAN  {{ isset($bulan) ? strtoupper(bulan($bulan)) : '' }} {{ isset($tahun) ? $tahun : '' }}
                </h4>

                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Arus Kas</th>
                        <th>COA</th>
                        <th>Saldo</th>
                        <th>Total</th>
                    </tr>
                    @if(isset($data))
                    @foreach ($data as $key=>$rekap)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $rekap->arus_kas}}</td>
                        <td>{{ $rekap->coa}}</td>
                        <td>Rp.{{ number_format($rekap->saldo) }}</td>
                        <td>Rp. {{ number_format($rekap->total) }}</td>
                    </tr>

                    @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')

<script type="text/javascript">
$(".select").select2({
    theme: 'bootstrap4',
    width : '100%'
});



</script>
@endpush
