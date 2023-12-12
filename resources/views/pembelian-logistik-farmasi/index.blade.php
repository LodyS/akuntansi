@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pembelian Logistik Dan Farmasi</h1>
        @include('layouts.inc.breadcrumb')
        <div class="page-header-actions">
    </div>
</div>

    <div class="page-content">
     <!-- Panel Table Tools -->
        <div class="panel">
            <header class="panel-heading">
                <div class="form-group col-md-12">
                <div class="form-group">
            </div>
        </div>
    </header>

    <div class="panel-body">
        <form action="{{ url('simpan-pembelian-logistik-farmasi')}}" method="post">{{ @csrf_field() }}

        <input type="hidden" name="id_periode" value="{{ isset($PeriodeKeuangan) ? $PeriodeKeuangan->id : '' }}">

            <div class="form-group row">
			    <label class="col-md-3">Kode Mutasi Kas</label>
				    <div class="col-md-7">
                    <input type="text" name="kode_mutasi_kas" value="{{ isset($kodeBkm) ? $kodeBkm->kode : 'BKK-1' }}" class="form-control" required readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">No Faktur</label>
				    <div class="col-md-7">
                    <input type="text" name="no_faktur" class="form-control" required>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Pemasok</label>
				    <div class="col-md-7">
				    <select name="id_pemasok" id="id_pemasok" class="form-control select" required>
                    <option value="">Pilih Pemasok</option>
                    @foreach ($instansiRelasi as $pemasok)
                    <option value="{{ $pemasok->id }}">{{ $pemasok->nama }}</option>
                    @endforeach
                    </select>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">ID Perkiraan</label>
				    <div class="col-md-7">
                    <input type="text" id="perkiraan" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Jenis Pembayaran</label>
				    <div class="col-md-7">
                    <input type="radio" id="jenis_pembayaran" name="jenis_pembayaran" value="tunai" class="jenis_pembayaran" required><span>Tunai</span>
                    <input type="radio" id="jenis_pembayaran" name="jenis_pembayaran" value="kredit" class="jenis_pembayaran"><span>Kredit</span>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Tanggal Pembelian</label>
				    <div class="col-md-7">
                    <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" value="{{date('Y-m-d')}}" class="form-control" >
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Jumlah Hari</label>
				    <div class="col-md-7">
                    <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control" readonly>
			    </div>
		    </div>

            <a class="btn btn-warning btn-xs" href="#" onclick="getdate()" style="color:white; font-family:Arial">
            <i class="icon glyphicon glyphicon-calendar" aria-hidden="true"></i>Lihat Tanggal Jatuh Tempo</a><br/><br/>

            <div class="form-group row">
			    <label class="col-md-3">Tanggal Jatuh Tempo</label>
				    <div class="col-md-7">
                    <input type="text" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control" readonly required>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Pajak</label>
				    <div class="col-md-7">
                    <input type="text" name="pajak" id="pajak" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Tarif Pajak</label>
				    <div class="col-md-7">
                    <input type="text" name="tarif_pajak" id="tarif_pajak" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Cara Bayar</label>
				    <div class="col-md-7">
				        <select name="id_bank" id="id_bank" class="form-control select" required>
                        <option value="">Pilih Bank</option>
                        @foreach ($KasBank as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                        @endforeach
                    </select>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Jenis Pembelian</label>
				    <div class="col-md-7">
				    <select name="jenis_pembelian" class="form-control" required>
                    <option value="">Pilih Jenis Pembelian</option>
                    @foreach ($jenisPembelian as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                    @endforeach
                    </select>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Total Sebelum Diskon</label>
				    <div class="col-md-7">
                    <input type="hidden" name="total_before_diskon" id="total_before_diskon" class="form-control" readonly>
                    <input type="text" id="total_before_diskon_rupiah" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Total Diskon</label>
				    <div class="col-md-7">
                    <input type="hidden" name="total_diskon" id="total_diskon" class="form-control" readonly>
                    <input type="text" id="total_diskon_rupiah" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Total</label>
				    <div class="col-md-7">
                    <input type="hidden" name="total" id="total" class="form-control" readonly>
                    <input type="text" id="total_rupiah" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">PPN</label>
				    <div class="col-md-7">
                    <input type="hidden" name="ppn" id="ppn" class="form-control" readonly>
                    <input type="text" id="ppn_rupiah" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
                <label class="col-md-3">Biaya Materai</label>
                    <div class="col-md-7">
                    <input type="text" name="biaya_materai" id="biaya_materai" class="form-control" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3">Charge</label>
                    <div class="col-md-7">
                    <input type="text" name="biaya_charge" id="biaya_charge" class="form-control" required>
                </div>
            </div>

            <div class="form-group row">
			    <label class="col-md-3">Total Yang Harus Dibayar</label>
				    <div class="col-md-7">
                    <input type="hidden" name="total_yang_harus_dibayar" id="total_yang_harus_dibayar" class="form-control" readonly>
                    <input type="text"  id="total_yang_harus_dibayar_rupiah" class="form-control" readonly>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Pembayaran</label>
				    <div class="col-md-7">
                    <input type="text" name="pembayaran" id="pembayaran" class="form-control" required>
			    </div>
		    </div>

            <div class="form-group row">
			    <label class="col-md-3">Sisa Tagihan</label>
				    <div class="col-md-7">
                    <input type="hidden" name="sisa_tagihan" id="sisa_tagihan" class="form-control" readonly>
                    <input type="text" id="sisa_tagihan_rupiah" class="form-control" readonly>
			    </div>
		    </div>

            <button class="btn btn-dark btn-round" type="button" id="add"><i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i>Tambah</button>
                <table class="table table-hover" id="tambah_form">
                    <thead>
                        <td>Barcode</td>
                        <td>Barang</td>
                        <td>QTY</td>
                        <td>Stok Akhir</td>
                        <td>Kemasan</td>
                        <td>Harga</td>
                        <td>Total Sebelum Diskon</td>
                        <td>Diskon (%)</td>
                        <td>Total Harga Setelah Diskon</td>
                    </thead>

                    <tbody></tbody>

                                <tfoot>
                            <tr></tr>
                        </tfoot>
                    </table>
                <button type="submit" id="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-save" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
     </div>
 </div>

@endsection

@push('js')

<script type="text/javascript">

$(".select").select2({
	width: '100%'
});

$('.jenis_pembayaran').click(function(){
    var status = $(this).val();
	var nilai = 0;

	if(status == "tunai"){
		$("#pembayaran").attr("readonly", false);
	} else {
		$("#pembayaran").attr("readonly", true);
	    $("#pembayaran").val(nilai);
        $('#id_bank').val('1').change();
    }
});

function formatRupiah(number) {
  return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('#total_rupiah').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#ppn_rupiah').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#biaya_materai').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#biaya_charge').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#pembayaran').on('change click keyup input paste',(function (event) {
  $(this).val(function (index, value) {
    return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#sisa_tagihan_rupiah').on('change click keyup input paste',(function (event) {
  $(this).val(function (index, value) {
    return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  });
}));

$(document).on('keyup change', "#biaya_materai, #biaya_charge, #ppn, #total",  function() {
    var val1 = $("#biaya_materai").val() || 0;
    var val2 = $("#ppn").val() || 0;
    var val3 = $("#total").val() || 0;
    var val4 = $("#biaya_charge").val() || 0;

    var biaya_materai = Number(val1.replace(/[^0-9]+/g,""));
    var biaya_charge = Number(val4.replace(/[^0-9]+/g,""));

    var result = parseFloat(biaya_materai) + parseFloat(val2) + parseFloat(val3) + parseFloat(biaya_charge);
    $("#total_yang_harus_dibayar").val(result);
    $("#total_yang_harus_dibayar").change();

    $("#total_yang_harus_dibayar_rupiah").val(formatRupiah(result));
    $("#total_yang_harus_dibayar_rupiah").change();
}); // hitung total yang harus dibayar

$(document).on('keyup change', "#pembayaran, #total_yang_harus_dibayar",  function() {
    var val1 = $("#total_yang_harus_dibayar").val();
    var val2 = $("#pembayaran").val();
    //var pembayaran = Number(val2.replace(/[^0-9.-]+/g,""));

    var result = parseInt(val1) - Number(val2.replace(/[^0-9]+/g,""));
    $("#sisa_tagihan").val(result);
    $("#sisa_tagihan_rupiah").val(formatRupiah(result));
}); // hitung sisa tagihan

function getdate() { //fungsi untuk mendapat tanggal jatuh tempo
  var tt = document.getElementById('tanggal_pembelian').value;
  var date = new Date(tt);
  var newdate = new Date(date);
	var jatuh_tempo = parseInt(document.getElementById('jumlah_hari').value);

  newdate.setDate(newdate.getDate() + jatuh_tempo);

  var dd = newdate.getDate();
  var mm = newdate.getMonth() + 1;
  var y = newdate.getFullYear();

  if (dd <10 && mm <10){
      var someFormattedDate = y + '-' + '0' + mm + '-' + '0'+dd;
      document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;
    }
    else if (dd <10 && mm >9){
      var someFormattedDate = y + '-'  + mm +  '-' + '0' + dd;
      document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;
    }
    else if (dd >10 && mm <10){
      var someFormattedDate = y + '-' + '0' + mm + '-'  + dd;
      document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;
    }
    else {
      var someFormattedDate = y + '-' +  + mm + '-' +  dd;
      document.getElementById('tanggal_jatuh_tempo').value = someFormattedDate;
    }
  } // untuk mendapat tanggal jatuh tempo

$('#id_pemasok').change(function(){
    var id_pemasok = $(this).val();
    var url = '{{ route("CariPemasok", ":id_pemasok") }}';
    url = url.replace(':id_pemasok', id_pemasok);

$.ajax({
  url: url,
    type: 'get',
      dataType: 'json',
        success: function(response){
        if(response != null){
        $('#jumlah_hari').val(response.jumlah_hari);
        $('#pajak').val(response.nama_pajak);
        $('#tarif_pajak').val(response.persentase_pajak);
        $('#perkiraan').val(response.perkiraan);
    }}
  });
});

$(document).ready(function() {
var i = 0

$('#add').click(function() {
    i++;

$('#tambah_form').append('<tr id="row'+i+'">\n\
<td><input type="text" id="barcode-'+i+'" name="barcode[]" class="form-control"></td>\n\
<td><input type="text" id="barang-'+i+'" name="barang[]" class="form-control" readonly></td>\n\
<input type="hidden" id="id_barang-'+i+'" name="id_barang[]" class="form-control">\n\
<input type="hidden" id="id_packing_barang-'+i+'" name="id_packing_barang[]" class="form-control">\n\
<input type="hidden" id="id_stok-'+i+'" name="id_stok[]" class="form-control">\n\
<input type="hidden" id="stok-'+i+'" name="stok_awal[]" class="form-control">\n\
<td><input type="text" id="qty-'+i+'" name="qty[]" class="form-control" ></td>\n\
<td><input type="text" id="stok_akhir-'+i+'" name="stok_akhir[]" class="form-control" readonly></td>\n\
<td><input type="text" id="kemasan-'+i+'" name="kemasan[]" class="form-control" readonly></td>\n\
<td><input type="text" id="harga-'+i+'" name="harga[]" class="form-control"></td>\n\
<td><input type="text" id="total_sebelum_diskon-'+i+'" name="total_sebelum_diskon[]" class="form-control total_sebelum_diskon" readonly required></td>\n\
<td><input type="number" id="diskon-'+i+'" name="diskon[]" class="form-control"  required></td>   \n\
<td><input type="text" id="total_harga_setelah_diskon-'+i+'" name="total_harga_setelah_diskon[]" class="form-control total_harga_setelah_diskon" readonly required></td>\n\
<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-round"><i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i></button></td></tr>');

$('#harga-'+i).on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#total_sebelum_diskon-'+i).on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#total_harga_setelah_diskon-'+i).on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(function () {
  $('#total_sebelum_diskon-'+i,
    '#diskon-'+i,
    '#total_harga_setelah_diskon-'+i,
    '#ppn').prop('readonly', true);

  var $tblrows = $("#tambah_form tbody tr");

  $tblrows.each(function (index) {
    var $tblrow = $(this);
      $tblrow.find('#diskon-'+i).on('keyup change', function () {

    var total_sebelum_diskon = $tblrow.find('#total_sebelum_diskon-'+i).val() || 0;
    var total_sebelum_diskonn = Number(total_sebelum_diskon.replace(/[^0-9]+/g,""));
    var diskon = $tblrow.find('#diskon-'+i).val() || 0;
    var subTotal = total_sebelum_diskonn * parseInt(diskon, 10) / 100;
    var afterDiskon = total_sebelum_diskonn - subTotal;

    if (!isNaN(subTotal)) {
        $tblrow.find('#total_harga_setelah_diskon-'+i).val(formatRupiah(afterDiskon));

        var TotalAfterDiskon = 0;
        var TotalBeforeDiskon =0;

        $(".total_harga_setelah_diskon").each(function () {
            var stval = $(this).val();
            var stval_rupiah = Number(stval.replace(/[^0-9]+/g,""));
            TotalAfterDiskon += isNaN(stval_rupiah) ? 0 : stval_rupiah;
        });

        $(".total_sebelum_diskon").each(function () {
            var stvall = $(this).val();
            var stvall_rupiah = Number(stvall.replace(/[^0-9]+/g,""));
            TotalBeforeDiskon += isNaN(stvall_rupiah) ? 0 : stvall_rupiah;
        });

        var total_diskon = parseInt(TotalBeforeDiskon, 10) - parseInt(TotalAfterDiskon, 10);

        $('#total_diskon').val(total_diskon.toFixed(2));
        $('#total_diskon_rupiah').val(total_diskon.toFixed(2));
        $('#total').val(TotalAfterDiskon.toFixed(2));
        $('#total_rupiah').val(formatRupiah(TotalAfterDiskon));

        var ppn = $('#tarif_pajak').val() || 0;
        var hitung_ppn = parseInt(TotalAfterDiskon, 10) * parseInt(ppn, 10) /100;
        $('#ppn').val(hitung_ppn.toFixed(2));
        $('#ppn_rupiah').val(formatRupiah(hitung_ppn));
        $('#ppn').change();
        $('#ppn_rupiah').change();
    }});
  });
}); // hitung total sesudah diskon

$(function () {
    $('#qty-'+i, '#harga-'+i, '#total_sebelum_diskon').prop('readonly', true);
      var $tblrows = $("#tambah_form tbody tr");

  $tblrows.each(function (index) {
    var $tblrow = $(this);
      $tblrow.find('#harga-'+i).on('keyup change', function () {

    var harga = $tblrow.find('#harga-'+i).val();
    var qty = $tblrow.find('#qty-'+i).val();
    var harga_rupiah = Number(harga.replace(/[^0-9]+/g,""));
    var subTotal = harga_rupiah * parseInt(qty);

    if (!isNaN(subTotal)) {
        $tblrow.find('#total_sebelum_diskon-'+i).val(formatRupiah(subTotal));
        var grandTotal = 0;

        $(".total_sebelum_diskon").each(function () {
            var stval = $(this).val();
            var getBeforeDiskon = Number(stval.replace(/[^0-9]+/g,""));
            grandTotal += isNaN(getBeforeDiskon) ? 0 : getBeforeDiskon;
        });
        console.log(grandTotal);
      $('#total_before_diskon').val(grandTotal.toFixed(2));
      $('#total_before_diskon_rupiah').val(formatRupiah(grandTotal));

    }});
  });
}); // hitung total sebelum diskon

$(function () {
    $('#stok-'+i, '#qty-'+i, '#stok_akhir'+i).prop('readonly', true);
    var $tblrows = $("#tambah_form tbody tr");

  $tblrows.each(function (index) {
    var $tblrow = $(this);
    $tblrow.find('#qty-'+i).on('change', function () {

    var stok = $tblrow.find('#stok-'+i).val();
    var qty = $tblrow.find('#qty-'+i).val();
    var stok_akhir = parseInt(stok, 10) + parseInt(qty, 10);

    if (!isNaN(stok_akhir)) {
        $tblrow.find('#stok_akhir-'+i).val(stok_akhir.toFixed(2));
    }});
  });
}); // menghitung stok akhir yang akan di update & input ke table stok dan log stok

$('#barcode-' +i).change(function(){
  var barcode = $(this).val();
  var url = '{{ route("CariBarang", ":barcode") }}';
  url = url.replace(':barcode', barcode);

$.ajax({
  url: url,
  type: 'get',
  dataType: 'json',
    success: function(response){
      if(response != null){
        $('#kemasan-' +i).val(response.satuan);
        $('#id_packing_barang-' +i).val(response.id_packing_barang);
        $('#barang-' +i).val(response.barang);
        $('#id_barang-' +i).val(response.id_barang);
        $('#id_stok-' +i).val(response.id_stok);
        $('#stok-' +i).val(response.jumlah_stok);
      }}
    });
  });
});

$(document).on('click', '.btn_remove', function() {
  var button_id = $(this).attr("id");
    $('#row' + button_id + '').remove();
  });
});
</script>
@endpush
