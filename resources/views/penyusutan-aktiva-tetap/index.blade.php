@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Penyusutan Aktiva Tetap</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	        <form action="{{ url('penyusutan-aktiva-tetap/rekapitulasi') }}" method="post" id="index">{{ @csrf_field() }}

	        <div class="form-group row">
		        <label class="col-md-3">Aktiva Tetap</label>
			        <div class="col-md-7">
			            <select name="id_aktiva_tetap" class="form-control select">
                        <option value="">Pilih Aktiva Tetap</option>
                        @foreach($aktiva as $a)
                        <option value="{{ $a->id }}">{{ $a->nama }}</option>
                        @endforeach
                    </select>
		        </div>
	        </div>

            <div class="form-group row">
		        <label class="col-md-3">Bulan</label>
			        <div class="col-md-7">
			            <select name="bulan" class="form-control" required>
                        <option value="">Pilih Bulan</option>
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
            <button type="submit" align="right" class="btn btn-primary">Cari</button>
        </form><br/>

            <form action="{{ url('jurnal-pembayaran-hutang/jurnal')}}" method="post">{{ @csrf_field() }}
                <table class="table table-hover">
                    <tr>
                        <th>No</th>
                        <th>Nama Aktiva</th>
                        <th>Harga Perolehan</th>
                        <th>Periode</th>
                        <th>Residu</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>

                    @if (isset($bulan))
                    @foreach ($aktivaTetap as $key=> $rekap)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $rekap->nama_aktiva }}</td>
                            <td>Rp. {{ number_format($rekap->hp) }}</td>
                            <td>{{ $rekap->periode }}</td>
                            <td>Rp. {{ number_format($rekap->residu,2, ",", ".") }}</td>
                            <td>{{date('d-m-Y', strtotime($rekap->tanggal_beli))}}</td>
                            <td>@if($rekap->status == 'Tidak Ada')
                                <a href="{{ url('penyusutan-aktiva-tetap/penyusutan-aktiva/' .$rekap->id.'/'. $bulan) }}" class="btn btn-danger">Penyusutan</a>
                                @endif</td>
                        </tr>
                    @endforeach
                    @endif
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push ('js')
<script type="text/javascript">

$(document).ready(function(){

$(".select").select2({

    dropdownParents : $('#index'),
    width : '100%'
});

});
</script>
@endpush
