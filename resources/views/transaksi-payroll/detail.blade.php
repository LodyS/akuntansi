@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Payroll</h1>
</div>
@include('layouts.inc.breadcrumb')
    <div class="page-content">
        <div class="panel">
            <div class="panel-body">
		    @include('flash-message')
                <h3 align="center"></h3>

                    <div class="form-group row">
		                <label class="col-md-3">Tanggal</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control btn-round" value="{{ isset($payroll) ? date('d-m-Y', strtotime($payroll->tanggal_transaksi)) : ''}}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Keterangan</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="{{ isset($payroll) ? $payroll->keterangan : ''}}" readonly>
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Rekening</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly value="{{isset($payroll) ? $payroll->no_rekening : ''}}">
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Pemilik Rekening</label>
				            <div class="col-md-7">
				            <input type="text" value="{{ isset($payroll) ? $payroll->pemilik_rekening : '' }}" class="form-control btn-round" readonly>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Biaya ADM</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="Rp. {{ isset($payroll) ? number_format($payroll->biaya_adm_bank) : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Pajak</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="Rp. {{ isset($payroll) ? number_format($payroll->pajak) : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Total Uang Diterima</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly value="Rp. {{ isset($payroll) ? number_format($payroll->total_uang_diterima) : '0'}}">
                        </div>
                    </div>

                    <table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                		<tr>
                    		<th>No</th>
                    		<th>Komponen Gaji</th>
                            <th>Nominal</th>
                		</tr>
                        @foreach ($detail as $key =>$rekap)
                            <tr>
                                <td>{{ $key + $detail->firstItem() }}</td>
                                <td>{{ $rekap->komponen }}</td>
                                <td>Rp. {{ number_format($rekap->nominal) }}</td>
                            </tr>
                            @endforeach
                    </table>
                    {{ $detail->appends(request()->toArray())->links() }}
                </form>
            </div>
        </div>
    </div>
@endsection


