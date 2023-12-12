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
                    <div class="form-group">
                </div>
            </div>
        </header>

        <div class="panel-body">


            <form action="{{ url('setting-akun-piutang/setting') }}" method="post">{{ @csrf_field() }}

            <div class="form-group row">
				<label class="col-md-3">Tipe Pasien</label>
					<div class="col-md-7">
					    <select name="tipe_pasien" class="form-control" required>
                        <option value="">Pilih Tipe Pasien</option>
					    @foreach ($tipe_pasien as $id=> $tipe)
					    <option value="{{ $id }}">{{ $tipe }}</option>
					    @endforeach
                    </select>
                </div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Tipe Kunjungan</label>
					<div class="col-md-7">
					    <select name="tipe" class="form-control" required>
                        <option value="">Pilih Tipe Kunjungan</option>
					    <option value="RJ">Rawat Jalan</option>
                        <option value="RI">Rawat Inap</option>
                    </select>
                </div>
			</div>

            <button type="submit" align="right" class="btn btn-primary">Cari</button>
        </div>
    </div>
</div>
@endsection
