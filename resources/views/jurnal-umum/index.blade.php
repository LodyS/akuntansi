@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Umum </h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            @include('flash-message')
	        <form action="{{ url('simpan-jurnal-umum')}}" method="post">{{ @csrf_field() }}
            <input type="hidden" name="id_periode" value="{{ isset($periodeKeuangan) ? $periodeKeuangan->id : '' }}">

                <div class="form-group row">
		            <label class="col-md-3">Tipe Jurnal</label>
			            <div class="col-md-7">
                            <select class="form-control select" name="tipe_jurnal" id="tipe_jurnal" required>
                            <option value="">Pilih Tipe Jurnal</option>
                            @foreach ($tipe_jurnal as $id =>$jurnal)
                            <option value="{{ $id }}">{{ $jurnal }}</option>
                            @endforeach
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Kode Jurnal</label>
			            <div class="col-md-7">
                        <input type="text" name="kode_jurnal" id="kode_jurnal" class="form-control" readonly>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Tanggal</label>
			            <div class="col-md-7">
			            <input type="date" name="tanggal" value="{{ date('Y-m-d')}}" class="form-control"  required>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Keterangan</label>
			            <div class="col-md-7">
			            <textarea class="form-control" name="keterangan" rows="4" required></textarea>
		            </div>
	            </div>

                <button class="btn btn-dark" type="button" id="add">
                    <i class="icon glyphicon glyphicon-plus-sign"></i></i>Tambah</button>
                    <table class="table table-hover" id="tambah_form">
                        <tr>
                            <th>Perkiraan</th>
                            <th>Unit</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th></th>
                        </tr>

                        <tbody>
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td><input type="text" id="get_debet" class="form-control" readonly></td>
                                <td><input type="text" id="get_kredit" class="form-control" readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Balance</td>
                                <td></td>
                                <td></td>
                                <td><input type="text" id="get_balance" name="balance" class="form-control" readonly></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-list-alt"></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('.select').select2({  theme: "bootstrap-5", width:'100%'});

function formatRupiah(number) {
  return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");

}

$(document).ready(function() {

var i = 0;

$("#add").on("click", function() {
    var newRow = $("<tr>");
    var cols = "";

    cols += '<td><select name="id_perkiraan[]" class="form-control cari"><option value="">Pilih</option>@foreach($perkiraan as $kira)<option value="{{ $kira->id }}">{{ $kira->perkiraan }}</option>@endforeach</select></td>';
    cols += '<td><select name="id_unit[]" class="form-control cari"><option value="">Pilih</option>@foreach($unit as $u)<option value="{{ $u->id }}">{{ $u->code_cost_centre }} - {{ $u->nama }}</option>@endforeach </select></td>';
    cols += '<td><input type="text" class="form-control nominal" data-action="sumDebet" name="debet[]"/></td>';
    cols += '<td><input type="text" class="form-control nominal" data-action="sumKredit" name="kredit[]"/></td>';
    cols += '<td><button type="button" class="btn btn-danger adRow ibtnDel" style="width:25%;">x</button></a></td>';
    newRow.append(cols);

    newRow.find('.cari').select2({  theme: "bootstrap-5", width:'300px'});

    newRow.find('.nominal').on('change click keyup input paste',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });
    }));

    $("#tambah_form").append(newRow);
    i++;
});

$("#tambah_form").on("click", ".ibtnDel", function(_event) {
    $(this).closest("tr").remove();
    $("#get_kredit").val();
    $("#get_balance").val();
    i -= 1
    evaluateTotal()
});

// script form dinamis dan hitung debet dan kredit

$('body').on('change keyup', '[data-action="sumDebet"]', function() {
    evaluateTotal();
    balance();
}); // hitung total kredit dari jumlah form yang di klik beserta penjumlahan balance (debet -kredit)

$('body').on('change keyup', '[data-action="sumKredit"]', function() {
    evaluateKredit();
    balance();
}); // hitung total kredit dari jumlah form yang di klik beserta penjumlahan balance (debet -kredit)

function evaluateTotal() {
    var total = 0;

    $('[data-action="sumDebet"]').each(function(_i, e) {

    var val = Number(e.value.replace(/[^0-9,-]+/g,""));


    if (!isNaN(val))
        total += val;
    });

    $('#get_debet').val(formatRupiah(total));
    balance();
} // cari menghitung akumulasi debet

function evaluateKredit() {
    var total = 0;

    $('[data-action="sumKredit"]').each(function(_i, e) {
    var val = Number(e.value.replace(/[^0-9,-]+/g,""));

    if (!isNaN(val))
        total += val;
    });

    $('#get_kredit').val(formatRupiah(total));
    balance();
} // cari menghitung akumulasi kredit

function balance ()
{
    var debet = $('#get_debet').val();
    var kredit  = $('#get_kredit').val();

    var balance = Number(debet.replace(/[^0-9,-]+/g,"")) - Number(kredit.replace(/[^0-9,-]+/g,""));
    $('#get_balance').val(formatRupiah(balance)).change();
} // fungsi untuk menghitung balance

$('#tipe_jurnal').change(function(){
    var tipe_jurnal = $(this).val();
    var url = '{{ route("isiKodeJurnal", ":tipe_jurnal") }}';
    url = url.replace(':tipe_jurnal', tipe_jurnal);

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',

            success: function(response){
                if(response != null){
                $('#kode_jurnal').val(response.kode);
            }}
        });
    });
}); // untuk mengirim request ajax ke controller untuk mendapat kode jurnal dan id tipe jurnal
</script>
@endpush
