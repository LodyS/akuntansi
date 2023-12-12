@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Tambah Saldo</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            
	<form action="{{ url('simpan-saldo')}}" method="post">{{ @csrf_field() }} 
    <input type="hidden" name="id_perkiraan" value="{{ $pelanggan->id_perkiraan }}" >
	<input type="hidden" name="id_pelanggan" value="{{ $pelanggan->id }}">
	@if ($periodeKeuangan == null)
	<input type="hidden" name="id_periode" value="">
	@else
    <input type="hidden" name="id_periode" value="{{ $periodeKeuangan->id }}">
	@endif
   
	<div class="form-group row">
		<label class="col-md-3">No Transaksi</label>
			<div class="col-md-7">
            @if ($bukti == null)
			<input name="bukti_transaksi"  value="PJ-1" class="form-control" readonly>
            @else 
            <input name="bukti_transaksi" value="{{$bukti->bukti_transaksi }}" class="form-control" readonly>
            @endif
		</div>
	</div>
                   
    <div class="form-group row">
		<label class="col-md-3">Nama</label>
			<div class="col-md-7">
        	<input type="text" name="" value="{{ $pelanggan->nama }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Jenis</label>
			<div class="col-md-7">
				<select name="jenis_pasien" id="id"  class="form-control" required>
				<option value="">Pilih Jenis Kunjungan</option>
                <option value="RJ">Rawat Jalan</option>
                <option value="RI">Rawat Inap</option>
            </select>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tipe Pasien</label>
			<div class="col-md-7">
			<input type="radio" name="tipe_pasien" id="1" value="1"  onClick="javascript:showForm()"><label>Perusahaan Langganan</label>
			<input type="radio" name="tipe_pasien"  id="2" value="2" onClick="javascript:showForm()"><label>Antar Unit</label>
		</div>
	</div>

 	<div id="tampil" style="display:none" class="none">
    	<div class="form-group row">
			<label class="col-md-3">Asuransi</label>
				<div class="col-md-7">
				<select name="id_asuransi" class="form-control">
				<option value="">Pilih Asuransi</option>
                @foreach ($produkAsuransi as $asuransi)
                <option value="{{ $asuransi->id }}">{{ $asuransi->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Perusahaan</label>
			<div class="col-md-7">
			<input type="text" name="perusahaan" class="form-control">
			</div>
		</div>
    </div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Piutang</label>
			<div class="col-md-7">
			<input type="date" id="tanggal_piutang" name="tanggal_piutang" value="{{ date('Y-m-d')}}"class="form-control" required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Jatuh Tempo</label>
			<div class="col-md-7">
			<input type="text" name="jatuh_tempo" id="jatuh_tempo"  class="form-control" required>
		</div>
	</div>

        <a class="btn btn-success btn-xs" href="#" onclick="getdate()" style="color:white; font-family:Arial">Lihat Tanggal Jatuh Tempo</a>
        <br/><br/>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Jatuh Tempo</label>
			<div class="col-md-7">
			<input type="text" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Saldo Piutang</label>
			<div class="col-md-7">
			<input type="text" id="saldo_piutang" name="saldo_piutang" class="form-control" required>
		</div>
	</div>

        <button type="submit" align="right" class="btn btn-primary">Simpan</button>
                
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('#saldo_piutang').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
})); // untuk format uang di form

function showForm () { // fungsi untu menampilkan form kolom asuransi jika yang dipilih perusahaan langganan
	if (document.getElementById('1').checked){
		document.getElementById('tampil').style.display = 'block';
	} 
	else {
		document.getElementById('tampil').style.display = 'none'; 
	}
}

function getdate() { //fungsi untuk mendapat tanggal jatuh tempo
    var tt = document.getElementById('tanggal_piutang').value;

    var date = new Date(tt);
    var newdate = new Date(date);
	var jatuh_tempo = parseInt(document.getElementById('jatuh_tempo').value);
    newdate.setDate(newdate.getDate() + jatuh_tempo);
    
    var dd = newdate.getDate();
    var mm = newdate.getMonth() + 1;
    var y = newdate.getFullYear();

	if (dd <10 && mm <10){

		var someFormattedDate = y + '-' + '0' + mm + '-' + '0' +dd;
		document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;

	} else if (dd >=10 && mm >9){

		var someFormattedDate = y + '-'  + mm +  '-'  + dd;
		document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;

	} else if (dd <10 && mm >=10){

		var someFormattedDate = y + '-'  + mm +  '-' +'0' + dd;
		document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;

	} else if (dd >10 && mm <=10){

		var someFormattedDate = y + '-' + '0' + mm + '-'  + dd;
		document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;

	} else if (dd ==10 && mm < 10){

		var someFormattedDate = y + '-' + '0' + mm + '-'  + dd;
		document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;

	} else {

		var someFormattedDate = y + '-'  +'0'+ mm + '-' +  dd;
		document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;
	}
}
</script>
@endpush