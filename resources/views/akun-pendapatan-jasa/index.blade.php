@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Setting COA Pendapatan Jasa</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ url('akun-pendapatan-jasa/pencarian')}}" method="post">{{ @csrf_field() }} 

                <div class="form-group row">
		            <label class="col-md-3">Tipe Pasien</label>
			            <div class="col-md-7">
			            <select name="tipe_pasien" class="form-control" required>
				        <option value="">Pilih Tipe Pasien</option>
                        @foreach ($tipe_pasien as $tipe)
				        <option value="{{ $tipe->id }}">{{ $tipe->tipe_pasien }}</option>
                        @endforeach
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Tipe Kunjungan Pasien</label>
			            <div class="col-md-7">
			                <select name="tipe_kunjungan" class="form-control" required>
				            <option value="">Pilih Tipe Kunjungan Pasien</option>
				            <option value="RJ">Rawat Jalan</option>
                            <option value="RI">Rawat Inap</option>
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Tipe Pembayaran</label>
			            <div class="col-md-7">
			                <select name="tipe_bayar" class="form-control" required>
				            <option value="">Pilih Tipe Pembayaran</option>
				            <option value="Tunai">Tunai</option>
                            <option value="Kredit">Kredit</option>
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>
</div>
@endsection