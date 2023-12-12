@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Form Setting Cash Flow</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')

        <h3 align="center">{{ ($aksi == "create") ? 'Tambah' : 'Edit' }} Setting Cash Flow</h3><br/>
            <form action="{{ ($aksi == 'create') ? url('/simpan-setting-cash-flow') : url('/update-setting-cash-flow') }}" method="post">{{ @csrf_field() }}

            <input type="hidden" name="id" value="{{ optional($jenisTransaksi)->id }}">

            <div class="form-group row">
                <label class="col-md-3">Tipe</label>
                    <div class="col-md-7">
                    <input type="radio" name="tipe" class="tipe" id="Induk" {{ ($jenisTransaksi->id_induk == null && $aksi =="update") ? "checked" : '' }} onClick="javascript:showForm()" required><label>Induk</label>
                    <input type="radio" name="tipe" class="tipe" id="Child" {{ ($jenisTransaksi->id_induk != null && $aksi =="update") ? "checked" : '' }} onClick="javascript:showForm()"><label>Child</label>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3">Transaksi</label>
                    <div class="col-md-7">
                    <select name="id_transaksi_jurnal" id="id_transaksi_jurnal" class="form-control select">
                        <option value="">Pilih Transaksi</option>
                        @foreach ($transaksi as $indok)
                        <option value="{{ $indok->id }}" {{ ($jenisTransaksi->id_transaksi_jurnal == $indok->id)?'selected':''}}>{{ $indok->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

			<div id="tampil" style="display:none" class="none">
				<div class="form-group row">
                    <label class="col-md-3">Induk</label>
                        <div class="col-md-7">
                        <select name="id_induk" id="induk" class="form-control select">
                        	<option value="">Pilih Induk</option>
                        	@foreach ($induk as $indok)
                        	<option value="{{ $indok->id }}" {{ ($jenisTransaksi->id_induk == $indok->id)?'selected':''}}>{{ $indok->nama }}</option>
                        	@endforeach
                    	</select>
                	</div>
            	</div>
			</div>

			<div class="form-group row">
                <label class="col-md-3">Kode</label>
                    <div class="col-md-7">
                    <input type="text" name="kode" id="kode" class="form-control" value="{{ ($aksi == 'update') ? $jenisTransaksi->kode : $kodeS }}" readonly>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Level</label>
                    <div class="col-md-7">
                    <input type="text" name="level" id="level" class="form-control" value="{{ ($aksi == 'update') ? $jenisTransaksi->level : 0 }}" readonly>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Urutan</label>
                    <div class="col-md-7">
                    <input type="text" name="urutan" id="urutan" class="form-control" value="{{ ($aksi == 'update') ? $jenisTransaksi->urutan : 0 }}" readonly>
                </div>
            </div>

                <button type="submit" align="right" class="btn btn-primary" id="simpan">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

function showForm() {
    if (document.getElementById('Induk').checked) {
        document.getElementById('tampil').style.display = 'none';
    } else {
        document.getElementById('tampil').style.display = 'block';
    }
} //untuk menampilkan dan menyembunyikan kolom induk

$(document).ready(function(){

	$("#induk").select2({
		width: '100%'
	});

    $("#id_transaksi_jurnal").select2({
		width: '100%'
	});

	$('#induk').change(function(){
    	var induk = $(this).val();
    	var url = '{{ route("isiCashFlow", ":induk") }}';
    	url = url.replace(':induk', induk);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        	if(response != null){
				$('#kode').val(response.kode);
            	$('#urutan').val(response.urutan);
				$('#level').val(response.level);
        	}}
    	});
	});
});
</script>
@endpush
