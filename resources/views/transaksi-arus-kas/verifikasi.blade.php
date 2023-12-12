@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Verifikasi Arus Kas</h1>
    	@include('layouts.inc.breadcrumb')
		<div class="page-header-actions">
        <a class="btn btn-block btn-primary btn-pill-right" href="form">
        <i class="icon glyphicon glyphicon-pencil" aria-hidden="true"></i>&nbsp;Tambah</a>
    </div>
</div>

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
       	@include('flash-message')
           <form action="{{ url('mutasi-pengeluaran-kas/pencarian-verifikasi')  }}" method="post" >{{ @csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-3">Tanggal Awal :</label>
                      	<div class="col-md-7">
                      	<input type="date" name="tanggal_awal" value="{{ date('Y-m-d')}}" class="form-control">
                  	</div>
                </div>

                <div class="form-group row">
                  	<label class="col-md-3">Tanggal Akhir :</label>
                    	<div class="col-md-7">
                    	<input type="date" name="tanggal_akhir" value="{{ date('Y-m-d')}}" class="form-control">
                  	</div>
                </div>

                <div class="form-group row">
			        <label class="col-md-3">Bank</label>
				        <div class="col-md-7">
				            <select name="id_bank" class="form-control">
                      		<option value="">Pilih Bank</option>
                      		@foreach ($bank as $bang)
                      		<option value="{{ $bang->id }}">{{ $bang->nama }}</option>
                      		@endforeach
                    	</select>
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-md-3">COA</label>
				        <div class="col-md-7">
				            <select name="id_perkiraan" class="form-control">
                      		<option value="">Pilih Perkiraan</option>
                      		@foreach ($perkiraan as $kira)
                      		<option value="{{ $kira->id }}">{{ $kira->nama }}</option>
                      		@endforeach
                    	</select>
			        </div>
		        </div>

                <button type="submit" align="right" class="btn btn-dark">
                    <i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>
            </form><br/><br/>

        			<table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                		<tr>
                    		<th>No</th>
                    		<th>Kode</th>
                    		<th>Tanggal</th>
                    		<th>Pembayaran</th>
                    		<th>Pemasukan</th>
                    		<th>Nominal</th>
                    		<th>Keterangan</th>
                            <th>Status</th>
                            <th>Detail</th>
                    		<th>Verifikasi</th>
                		</tr>

                        @foreach ($data as $key =>$rekap)
                        <tr>
                            <form action="{{ url('/update-verifikasi-mutasi-pengeluaran-kas') }}" method="post" id="create">{{ @csrf_field() }}
                            <input type="hidden" name="id[]" value="{{ $rekap->id }}">
                            <td>{{ $key + $data->firstItem() }}</td>
                            <td>{{ $rekap->kode }}</td>
                            <td>{{date('d-m-Y', strtotime($rekap->tanggal))}}</td>
                            <td>{{ $rekap->perkiraan }}</td>
                            <td>{{ $rekap->kas_bank }}</td>
                            <td>Rp. {{ number_format($rekap->nominal,2, ",", ".") }}</td>
                            <td>{{ $rekap->keterangan }}</td>
                            <td>
                                @if ($rekap->status == 'Sudah Dijurnal')
                                <a class='btn bg-green-300 btn-xs btn-round'>
                                <b>{{ $rekap->status }}</b></a>
                                @endif
                                @if ($rekap->status == 'Belum Dijurnal')
                                <a class='btn btn-warning btn-xs btn-round'><b>{{ $rekap->status }}</b></a>
                                @endif
                                @if ($rekap->status == 'Tidak Dijurnal')
                                <a class='btn btn-danger btn-xs btn-round'><b>{{ $rekap->status }}</b></a>
                                @endif</td>
                            <td><a href='detail/{{ $rekap->id }}' class='btn btn-xs btn-info btn-sm btn-round'
                                data-toggle="tooltip" data-original-title="Detail">
                                <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Detail</a></td>
                            <td><input type="radio" class="check" name="centang[]{{ $i }}" value="Y">Ya
                                <input type="radio" name="centang[]{{ $i }}" value="N" checked>Tidak</td>
                        </tr>
                    @endforeach
        		</table>
            <button type="submit" align="right" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

</script>
@endpush
