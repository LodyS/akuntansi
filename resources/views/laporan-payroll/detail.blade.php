@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Payroll</h1>
</div>
@include('layouts.inc.breadcrumb')
    <div class="page-content">
        <div class="panel">
            <div class="panel-body">
                <h3 align="center">Detail Payroll</h3><br/>

                    <div class="form-group row">
                        <label class="col-md-3">Nama Pegawai</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="{{ isset($payroll->pemilik_rekening) ? $payroll->pemilik_rekening : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Unit</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="{{ isset($payroll->unit) ? $payroll->unit : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Nominal</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="Rp. {{ isset($payroll->total_tagihan) ? number_format($payroll->total_tagihan) : '0' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Pajak</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="Rp. {{ isset($payroll->pajak) ? number_format($payroll->pajak) : '0' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Biaya Administrasi</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="Rp. {{ isset($payroll->biaya_adm_bank) ? number_format($payroll->biaya_adm_bank) : '0' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
			            <label class="col-md-3">Rekening</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly value="{{isset($payroll->no_rekening) ? $payroll->no_rekening : ''}}">
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Pengiriman</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly
                            value="{{ isset($payroll->tanggal_transaksi) ? date('d-m-Y', strtotime($payroll->tanggal_transaksi)) : '' }}">
                        </div>
                    </div>

                    <h3>Detail Payroll</h3><br/>
                    <table class="table table-hover">
                        <tr>
                            <th>No</th>
                            <th>Komponen</th>
                            <th>Nominal</th>
                        </tr>

                        @foreach ($payrollDetail as $key=> $data)
                        <tr>
                            <td>{{ $key + $payrollDetail->firstItem() }}</td>
                            <td>{{ $data->komponen }}</td>
                            <td>Rp. {{ number_format($data->nominal) }}</td>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $payrollDetail->appends(request()->toArray())->links() }}
            </form>
        </div>
    </div>
</div>
@endsection


