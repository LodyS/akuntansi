@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Penagihan Piutang Pasien</h1>
</div>
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        
    <form action="{{ url('penagihan-piutang-pasien/rekapitulasi')}}" method="post" id="cari">{{ @csrf_field() }} 

    <div class="form-group row">
        <label class="col-sm-12" style="font-weight: bold">Parameter Pencarian :</label>
    </div>

    <div class="form-group row required">
		<label class="col-md-3">Tipe Pasien</label>
			<div class="col-md-7">
			<input type="radio" name="tipe_pasien" id="perusahaan_langganan"  value="1" onClick="javascript:showForm()"><label>Perusahaan Langganan</label>
			<input type="radio" name="tipe_pasien" id="antar_unit" value="2" onClick="javascript:showForm()"><label>Antar Unit</label>
		</div>
	</div>

    <div class="form-group row">
        <label class="col-md-3">Tipe Kunjungan Pasien</label>
            <div class="col-md-7">
                <select name='tipe_kunjungan_pasien' class="form-control" id='tipe_kunjungan_pasien' required>
                <option value=''>-- Pilih Tipe Pasien --</option>
                <option value='RJ'>RJ</option>
                <option value='RI'>RI</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3">Pasien</label>
            <div class="col-md-7">
            <select name='id_pasien' class="form-control" id='id_pasien'>
            <option value=''>-- Pilih Pasien --</option>
            @foreach($pasien as $Pasien)
            <option value="{{ $Pasien->id_pelanggan}}">{{ $Pasien->nama}}</option>
            @endforeach
            </select>
        </div>
    </div>
                        
    <div class="form-group row">
        <label class="col-md-3">Tanggal Penagihan</label>
            <div class="col-md-7">
            <input name="tanggal_penagihan" id="tanggal_penagihan" class="form-control" value="{{ date('Y-m-d')}}" type="date" required>
        </div>
    </div>
                        
    <div class="form-group row">
        <label class="col-md-3"></label>
            <div class="col-md-7">
            <button type="submit" class="btn btn-primary float-right" style="margin-top: 15px;">Cari</button>
            </div>
        </div>
    </div>
     
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">

$('#id_pasien').select2({
    width : '100%'
});

$('#asuransi').select2({
    width : '100%'
});

</script>
@endpush