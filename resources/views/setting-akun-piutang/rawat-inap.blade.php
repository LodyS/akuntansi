@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Rekening Kontrol Piutang</h1>
    <div class="page-header-actions"></div>
</div>

    <div class="page-content">

        <div class="panel">
            <header class="panel-heading">
                <div class="form-group col-md-12">
                <div class="form-group"></div>
            </div>
       </header>

        <div class="panel-body">
        @include('flash-message')
            <div class="form-group row">
			    <div class="col-sm-3">
			        <a href="index" class="btn btn-xs btn-success" >Beranda</a>
			    </div>
		    </div>

            <h4>Jenis Piutang : Piutang Rawat Inap </h4><br/>
            <form action="{{ ($setting == null) ? url('/simpan-setting-piutang') : url('/update-setting-piutang') }}" method="post">{{ @csrf_field() }}

                <input type="hidden" name="keterangan" value="Piutang">
                <input type="hidden" name="type" value="{{ $tipe }}">
                <input type="hidden" name="tipe_pasien" value="{{ $tipe_pasien }}">

                <div class="form-group row">
			        <label class="col-md-3">Piutang Pasien Masih Dirawat</label>
				        <div class="col-md-7">
					        <select name="id_perkiraan[]" class="form-control" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $id =>$p)
                            <option value="{{ $id }}" {{ (isset($piutang->id_perkiraan)) && ($piutang->id_perkiraan == $id)?'selected':''}}>{{ $p }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="id[]" value="{{ optional($piutang)->id }}">
                        <input type="hidden" name="jenis[]" value="Piutang Pasien Masih Dirawat RI">
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-md-3">Penagihan Piutang</label>
				        <div class="col-md-7">
				            <select name="id_perkiraan[]" class="form-control" required>
                            <option value="">Pilih Perkiraan</option>
					        @foreach ($perkiraan as $id=>$p)
                            <option value="{{ $id }}" {{ (isset($penagihan->id_perkiraan)) && ($penagihan->id_perkiraan == $id)?'selected':''}}>{{ $p }}</option>
					        @endforeach
                            </select>
                        <input type="hidden" name="id[]" value="{{ optional($penagihan)->id }}">
                        <input type="hidden" name="jenis[]" value="Penagihan Piutang RI">
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-md-3">Piutang Pasien Pulang Rawat RI</label>
				        <div class="col-md-7">
					        <select name="id_perkiraan[]" class="form-control" required>
                            <option value="">Pilih Perkiraan</option>
					        @foreach ($perkiraan as $id=>$p)
                            <option value="{{ $id }}" {{( (isset($pasienPulang->id_perkiraan)) && $pasienPulang->id_perkiraan == $id)?'selected':''}}>{{ $p }}</option>
					        @endforeach
                            </select>
                        <input type="hidden" name="id[]" value="{{ optional($pasienPulang)->id }}">
                        <input type="hidden" name="jenis[]" value="Piutang Pasien Pulang Rawat RI">
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-md-3">Pelunasan Piutang</label>
				        <div class="col-md-7">
					        <select name="id_perkiraan[]" class="form-control" required>
                            <option value="">Pilih Perkiraan</option>
					        @foreach ($perkiraan as $id=>$p)
                            <option value="{{ $id }}" {{ (isset($pelunasan->id_perkiraan)) && ($pelunasan->id_perkiraan == $id)?'selected':''}}>{{ $p }}</option>
					        @endforeach
                            </select>
                        <input type="hidden" name="id[]" value="{{ optional($pelunasan)->id }}">
                        <input type="hidden" name="jenis[]" value="Pelunasan Piutang RI">
		            </div>
	            </div>

            <button type="submit" align="right" class="btn btn-primary">{{($setting == null)?'Simpan':'Edit'}}</button>
        </div>
    </div>
</div>
@endsection
