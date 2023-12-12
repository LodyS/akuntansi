@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Tambah Saldo</h1>
</div>
@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            
	<form action="{{ url('simpan-saldo-hutang')}}" method="post">{{ @csrf_field() }} 

    <input type="hidden" name="id_perkiraan" value="{{ $InstansiRelasi->id_perkiraan }}" >
    <input type="hidden" name="id_periode" value="{{ isset($periodeKeuangan) ? $periodeKeuangan->id : '' }}">
    <input type="hidden" name="id_instansi_relasi" value="{{ $InstansiRelasi->id }}">               

    <div class="form-group row">
		<label class="col-md-3">No Faktur</label>
			<div class="col-md-7">
            <input name="no_faktur" value="{{ isset($faktur) ? $faktur->no_faktur : 'PU-1' }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Nama</label>
			<div class="col-md-7">
            <input type="text" name="" value="{{ $InstansiRelasi->nama }}" class="form-control" readonly>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Hutang</label>
			<div class="col-md-7">
			<input type="date" id="tanggal_hutang" name="tanggal_hutang" class="form-control" required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Jatuh Tempo</label>
			<div class="col-md-7">
			<input type="text" name="jatuh_tempo" id="jatuh_tempo"  value="{{ $InstansiRelasi->jumlah_hari }}" class="form-control" required readonly>
		</div>
	</div>

    <a class="btn btn-success btn-xs" href="#" onclick="getdate()" style="color:white; font-family:Arial">Lihat Tanggal Jatuh Tempo</a>
    <br/><br/>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Jatuh Tempo</label>
			<div class="col-md-7">
			<input type="text" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control" readonly required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Saldo Piutang</label>
			<div class="col-md-7">
			<input type="text" name="saldo_hutang" id="saldo_hutang" class="form-control" required>
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

$('#saldo_hutang').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
})); // untuk format uang di form

function getdate() { //fungsi untuk mendapat tanggal jatuh tempo
    var tt = document.getElementById('tanggal_hutang').value;
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