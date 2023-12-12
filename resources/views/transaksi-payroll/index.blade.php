@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Transaksi Payroll</h1>
    	@include('layouts.inc.breadcrumb')
		<div class="page-header-actions">
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
           <form action="{{ url('transaksi-payroll/pencarian')  }}" method="post" >{{ @csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-3">Tanggal</label>
                      	<div class="col-md-7">
                      	<input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="form-control" required>
                  	</div>
                </div>

                <div class="form-group row">
                  	<label class="col-md-3">Keterangan</label>
                    	<div class="col-md-7">
                    	<input type="text" name="keterangan"  class="form-control">
                  	</div>
                </div>

                <button type="submit" align="right" class="btn btn-dark">
                    <i class="icon glyphicon glyphicon-search"></i> Cari</button>
            </form>
        <br/>

                    <form action="{{ url('transaksi-payroll/jurnal')}}" method="post">{{ @csrf_field() }}

                        <input type="hidden" name="tanggal_transaksi" value="{{ isset($tanggal_transaksi) ? $tanggal_transaksi : date('Y-m-d') }}">
        			    <table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                			<tr>
                    			<th>No</th>
                    			<th>Tanggal Transaksi</th>
                                <th>Unit</th>
                    			<th>Keterangan</th>
                    			<th>Rekening</th>
                    			<th>Pemilik Rekening</th>
                    			<th>Total Tagihan</th>
                    			<th>Biaya ADM</th>
                                <th>Pajak</th>
                                <th>Total</th>
                    			<th>Aksi</th>
                			</tr>
                            @if(isset($data))
                            @foreach ($data as $key =>$rekap)
                            <tr>
                                <td>{{ $key + $data->firstItem() }}</td>
                                <td>{{date('d-m-Y', strtotime($rekap->tanggal_transaksi))}}</td>
                                <td>{{ $rekap->unit }}</td>
                                <td>{{ $rekap->keterangan }}</td>
                                <td>{{ $rekap->no_rekening }}</td>
                                <td>{{ $rekap->pemilik_rekening }}</td>
                                <td>Rp. {{ number_format($rekap->total_tagihan) }}</td>
                                <td>Rp. {{ number_format($rekap->biaya_adm_bank) }}</td>
                                <td>Rp. {{ number_format($rekap->pajak) }}</td>
                                <td>Rp. {{ number_format($rekap->total_uang_diterima) }}</td>
                                <td><a href='detail/{{ $rekap->id }}' class='btn btn-success btn-xs btn-round'>
                                <i class="icon glyphicon glyphicon-info-sign"></i> Detail</a>
                            </tr>
                            @endforeach
                            <button type="submit" id="submit" align="right" class="btn btn-danger"><i class="icon glyphicon glyphicon-list"></i> Jurnal</button><br/><br/>
                            {{ $data->appends(request()->toArray())->links() }}
                        </table>
                        @endif
                        </table>
                    </div>
                </div>
            </div>
     	</div>
 	</div>
@endsection

@push('js')
<script type="text/javascript">

</script>
@endpush
