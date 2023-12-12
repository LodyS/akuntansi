@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Transaksi Arus Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
    <div class="page-content">
        <div class="panel">
            <div class="panel-body">
		        @include('flash-message')
                    <div class="form-group row">
		                <label class="col-md-3">Kode</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control btn-round" value="{{ optional($mutasiKas)->kode }}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="{{ optional($mutasiKas)->induk }}" readonly>
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Sub Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly value="{{ optional($mutasiKas)->arus_kas }}">
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
				            <input type="date" value="{{ optional($mutasiKas)->tanggal  }}" class="form-control btn-round" readonly>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" value="{{ optional($mutasiKas)->keterangan  }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
			            <label class="col-md-3">Penerimaan</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly value="{{ optional($mutasiKas)->bank  }}">
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Total Nominal</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control btn-round" readonly value="Rp. {{ number_format(optional($mutasiKas)->nominal,2, ",", ".") }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
