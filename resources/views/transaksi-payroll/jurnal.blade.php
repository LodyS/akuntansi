@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Pembayaran Payroll</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		    @include('flash-message')

                <form action="{{ url('/simpan-jurnal-transaksi-payroll')}}" method="post">{{ @csrf_field() }}

                <div class="form-group row">
		            <label class="col-md-3">Kode Jurnal</label>
			            <div class="col-md-7">
			            <input type="text" name="kode_jurnal" class="form-control" value="{{ $kodeJurnal  }}" readonly>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Keterangan</label>
			            <div class="col-md-7">
			            <input type="text" name="keterangan" class="form-control" value="Jurnal Payroll - {{ isset($payroll) ? $payroll->keterangan.' - '.$payroll->unit : '' }}" readonly>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Tanggal</label>
			            <div class="col-md-7">
			            <input type="date" name="tanggal" class="form-control" value="{{ date($tanggal_transaksi) }}">
		            </div>
	            </div>

                <table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                	<tr>
                    	<th>Kode Rekening</th>
                        <th>Cost Centre</th>
                        <th>Rekening</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Layer</th>
                        <th>Urutan</th>
                	</tr>

                    <input type="hidden" name="tanggal_transaksi" value="{{ $tanggal_transaksi }}">
                    <input type="hidden" name="keterangan_payroll" value="{{ isset($keterangan) ? $keterangan : '' }}">


                    @foreach ($data as $jurnal)
                    <tr>
                        <td>{{ $jurnal->kode_rekening }}</td>
                        <input type="hidden" name="id_perkiraan[]" value="{{ $jurnal->id_perkiraan }}">


                        <td>{{ $jurnal->cost_centre }}</td>
                        <td>{{ $jurnal->rekening }}</td>
                        <td><input type="hidden" name="debet[]" value="{{ $jurnal->debet }}">Rp. {{ number_format($jurnal->debet) }}</td>
                        <td><input type="hidden" name="kredit[]" value="{{ $jurnal->kredit }}">Rp. {{ number_format($jurnal->kredit) }}</td>
                        <td>{{ $jurnal->layer }}</td>
                        <td>{{ $jurnal->urutan }}</td>
                    </tr>

                    @endforeach

                    <tr>
                        <td><b>Total :</b></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($debet)}}</td>
                        <td><b>Rp. {{ number_format($kredit) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><b>Balance :</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($debet-$kredit) }}</td>
                        <td></td>
                        <td></td>

                        <td></td>

                    </tr>
                </table>
                <button type="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

