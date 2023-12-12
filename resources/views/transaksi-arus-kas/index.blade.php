@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Transaksi Arus Kas</h1>
    	@include('layouts.inc.breadcrumb')
		<div class="page-header-actions">
        <a class="btn btn-block btn-primary" href="form">
        <i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i>&nbsp;Tambah</a>
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
           <form action="{{ url('transaksi-arus-kas/pencarian')  }}" method="post" >{{ @csrf_field() }}
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
                      		@foreach ($bank as $id=>$bang)
                      		<option value="{{ $id }}">{{ $bang }}</option>
                      		@endforeach
                    	</select>
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-md-3">COA</label>
				        <div class="col-md-7">
				            <select name="id_perkiraan" class="form-control">
                      		<option value="">Pilih Perkiraan</option>
                      		@foreach ($perkiraan as $id =>$kira)
                      		<option value="{{ $id }}">{{ $kira }}</option>
                      		@endforeach
                    	</select>
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-md-3">Status Verifikasi</label>
				        <div class="col-md-7">
				            <select name="flag_bayar" class="form-control">
                      		<option value="">Pilih</option>
                      		<option value="Y">Sudah diverifikasi</option>
                            <option value="N">Belum diverifikasi</option>
                    	</select>
			        </div>
		        </div>

                <button type="submit" align="right" class="btn btn-dark">
                    <i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>
            </form>
        <br/><br/>
        <a class="btn btn-sm btn-danger" href="verifikasi"><i class="icon glyphicon glyphicon-check" aria-hidden="true"></i>Verifikasi</a><br/>
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
                                <th>Verfikasi</th>
                    			<th>Detail</th>
                			</tr>

                            @foreach ($data as $key =>$rekap)
                            <tr>
                                <td>{{ $key + $data->firstItem() }}</td>
                                <td>{{ $rekap->kode }}</td>
                                <td>{{date('d-m-Y', strtotime($rekap->tanggal))}}</td>
                                <td>{{ $rekap->perkiraan }}</td>
                                <td>{{ $rekap->kas_bank }}</td>
                                <td>Rp. {{ number_format($rekap->nominal,2, ",", ".") }}</td>
                                <td>{{ $rekap->keterangan }}</td>

                                <td>
                                @if ($rekap->status == 'Sudah Dijurnal')
                                <button type="button" class='btn bg-green-300 btn-xs'>
                                <i class="icon glyphicon glyphicon-ok-sign"></i>{{ $rekap->status }}</button>
                                @endif
                                @if ($rekap->status == 'Belum Dijurnal')
                                <button type="button" class='btn btn-warning btn-xs'>
                                <i class="icon glyphicon glyphicon-question-sign" ></i>{{ $rekap->status }}</button>
                                @endif
                                @if ($rekap->status == 'Tidak Dijurnal')
                                <button type="button" class='btn btn-danger btn-xs'>
                                <i class="icon glyphicon glyphicon-remove-sign" ></i>{{ $rekap->status }}</button>
                                @endif</td>

                                <td>@if ($rekap->verifikasi =='Sudah Diverifikasi')
                                <button type="button" class='btn btn-pure btn-xs bg-green-300'>
                                <i class="icon glyphicon glyphicon-check" ></i>{{ $rekap->verifikasi}}</button>
                                @endif

                                @if ($rekap->verifikasi =='Belum Diverifikasi')
                                <button type="button" class='btn btn-danger btn-xs'>
                                <i class="icon glyphicon glyphicon-unchecked" ></i>{{ $rekap->verifikasi}}</button>
                                @endif</td>

                                <td><a href='detail/{{ $rekap->id }}' class='btn btn-primary btn-xs'>
                                <i class="icon glyphicon glyphicon-info-sign"></i>Detail</a></td>
                            </tr>
                            @endforeach
        			</table>
                    {{ $data->appends(request()->toArray())->links() }}
        		</div>
     		</div>
 		</div>


@endsection

@push('js')
<script type="text/javascript">

</script>
@endpush
