@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Penjualan Obat Resep</h1>
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
          <a href="penjualan-obat-bebas"  class="btn btn-success">Penjualan Bebas</a>
        </div>
      </div>
    </header>

  <div class="panel-body">
    <form action="{{ url('simpan-penjualan-obat')}}" method="post">{{ @csrf_field() }}

    <div class="form-group row">
			<label class="col-md-3">Kode Penjualan</label>
				<div class="col-md-7">
        <input type="text" id="id" name="kode_penjualan" value="{{ isset($Penjualan) ? $Penjualan->kode : 'SR-1' }}" class="form-control" readonly>
		  </div>
		</div>

    <input type="hidden" name="id_periode" value="{{ isset($PeriodeKeuangan) ? $PeriodeKeuangan->id : '' }}">
    <input type="hidden" name="id_arus_kas" value="{{ isset($arusKas) ? $arusKas->id : '' }}">
 
    <div class="form-group row">
			<label class="col-md-3">Kode Mutasi Kas</label>
				<div class="col-md-7">
        <input name="kode_mutasi_kas" value="{{ isset($MutasiKas) ? $MutasiKas->kode : 'BKM-1' }}" class="form-control" readonly>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Tanggal Penjualan</label>
				<div class="col-md-7">
        <input type="date" name="tanggal_penjualan" value="{{date('Y-m-d')}}" class="form-control" >
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Dokter</label>
				<div class="col-md-7">
				<select name="id_nakes" class="form-control select" required>
          <option value="">Pilih Dokter</option>
          @foreach ($Nakes as $dokter)
          <option value="{{ $dokter->id }}">{{ $dokter->nama }}</option>
          @endforeach
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">No Kunjungan</label>
				<div class="col-md-7">
				<input type="text" id="id_visit" name="id_visit" class="form-control" required>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Pasien</label>
				<div class="col-md-7">
				<input type="text" id="nama_pasien" name="nama_pasien" class="form-control" readonly>
			</div>
    </div>

    <div class="form-group row">
			<label class="col-md-3">ID Pasien</label>
				<div class="col-md-7">
        <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" readonly required>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">ID Perkiraan</label>
				<div class="col-md-7">
        <input type="text" name="id_perkiraan" id="id_perkiraan" class="form-control" readonly required>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Jenis Pembayaran</label>
				<div class="col-md-7">
				<select name="jenis_pembayaran" class="form-control" required>
          <option value="">Pilih Jenis Pembayaran</option>
          <option value="Tunai">Tunai</option>
          <option value="Kredit">Kredit</option>
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Cara Bayar</label>
				<div class="col-md-7">
				<select name="id_bank" class="form-control" required>
          <option value="">Pilih Bank</option>
          @foreach ($KasBank as $bank)
          <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
          @endforeach
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Jenis Pasien</label>
				<div class="col-md-7">
				<select name="jenis_pasien" class="form-control" required>
        <option value="">Pilih Jenis Pasien</option>
        <option value="RJ">Rawat Jalan</option>
        <option value="RI">Rawat Inap</option>
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Tipe Pasien</label>
				<div class="col-md-7">
				<select name="tipe_pasien" class="form-control" required>
        <option value="">Pilih Tipe Pasien</option>
        <option value="1">Perusahaan Langganan</option>
        <option value="2">Antar Unit</option>
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Kelas</label>
				<div class="col-md-7">
				<select name="id_kelas" class="form-control" required>
        <option value="">Pilih Kelas</option>
        @foreach ($Kelas as $kelas)
        <option value="{{$kelas->id}}">{{$kelas->nama}}</option>
        @endforeach
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Asuransi</label>
				<div class="col-md-7">
				<select name="id_asuransi" class="form-control select">
        <option value="">Pilih Asuransi</option>
        @foreach ($ProdukAsuransi as $asuransi)
        <option value="{{ $asuransi->id }}">{{ $asuransi->nama }}</option>
        @endforeach
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Total Penjualan</label>
				<div class="col-md-7">
				<input type="hidden" id="total_penjualan" name="total_penjualan" class="form-control" readonly>
        <input type="text" id="total_penjualan_rupiah" class="form-control" readonly>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Total Setelah Diskon</label>
				<div class="col-md-7">
				<input type="hidden" id="total_after_diskon" name="total_after_diskon" class="form-control" readonly>
        <input type="text" id="diskon_rupiah" class="form-control" readonly>
			</div>
		</div>

    <div class="form-group row">
	    <label class="col-md-3">Pembayaran Tanpa Pajak</label>
			  <div class="col-md-7">
			  <input type="text" id="pembayaran_tanpa_pajak" name="pembayaran_tanpa_pajak" class="form-control">
		  </div>
	  </div>

    <div class="form-group row">
			<label class="col-md-3">Pajak</label>
				<div class="col-md-7">
				<input type="radio" id="Ya" name="status_pajak" value="Y" onClick="javascript:showForm()" required><label>Ya</label>
				<input type="radio" id="Tidak" name="status_pajak" value="N" onClick="javascript:showForm()"><label>Tidak</label>
			</div>
		</div>

    <div id="tax" style="display:none" class="none">
      <div class="form-group row">
			  <label class="col-md-3">Pajak</label>
				<div class="col-md-7">
				<input type="hidden" id="pajak" name="pajak" class="form-control" readonly>
        <input type="text" id="pajak_rupiah" class="form-control" readonly>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Total Setelah Pajak</label>
				<div class="col-md-7">
				<input type="hidden" id="total_setelah_pajak" name="total_setelah_pajak" class="form-control" readonly>
        <input type="text" id="total_setelah_pajak_rupiah" class="form-control" readonly>
			</div>
		</div>

    <div class="form-group row">
	    <label class="col-md-3">Pembayaran Dengan Pajak</label>
			  <div class="col-md-7">
			  <input type="text" id="pembayaran" name="pembayaran" class="form-control">
		  </div>
	  </div>

    <div class="form-group row">
			<label class="col-md-3">Total Tagihan Setelah Pajak</label>
				<div class="col-md-7">
				<input type="hidden" id="sisa_tagihan" name="tagihan_pajak" class="form-control" readonly>
        <input type="text" id="sisa_tagihan_pajak_rupiah" class="form-control" readonly>
			  </div>
			</div>
    </div>

    <div id="notax" style="display:none" class="none">
      <div class="form-group row">
				<label class="col-md-3">Sisa Tagihan Tanpa Pajak</label>
					<div class="col-md-7">
					<input type="hidden" id="sisa_tagihan_tanpa_pajak" name="tagihan_tanpa_pajak" class="form-control" readonly>
          <input type="text" id="sisa_tagihan_tanpa_pajak_rupiah" class="form-control" readonly>
				</div>
			</div>
    </div>

    <button class="btn btn-success" type="button" id="add">Tambah</button>

<table class="table table-hover" id="tambah_form">
    <thead>
      <td>Barcode</td>
      <td>Barang</td>
      <td>HNA</td>
      <td>Margin</td>
      <td>QTY</td>
      <td>Satuan</td>
      <td>Harga</td>
      <td>Diskon (%)</td>
      <td>Total Setelah Diskon</td>
    </thead>

    <tbody></tbody>

    <tfoot>
      <tr></tr>
  </tfoot>
</table>

    <button type="submit" id="submit" align="right" class="btn btn-primary">Simpan</button>
</form>

       </div>
     </div>
     <!-- End Panel Table Tools -->
 </div>
@endsection

@push('js')

<script type="text/javascript">

$(".select").select2({
	width: '100%'
});

function formatRupiah(number) {
  return number.toString().replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$('#pembayaran_tanpa_pajak').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

$('#pembayaran').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

function showForm ()
{
	if (document.getElementById('Ya').checked){
		document.getElementById('tax').style.display   = 'block';
    document.getElementById('notax').style.display = 'none';
	}
  else if (document.getElementById('Tidak').checked)
  {
    document.getElementById('notax').style.display = 'block';
    document.getElementById('tax').style.display   = 'none';
	}
} // untuk menampilkan/menyembunyikan form pajak dan total setelah pajak */

$(document).on('change keyup', "#Tidak",  "#total_after_diskon, #pembayaran_tanpa_pajak", function() {
    var after_diskon = $("#total_after_diskon").val()
    var pembayaran = $("#pembayaran_tanpa_pajak").val()

    var dua = Number(pembayaran.replace(/[^0-9.-]+/g,""));

    var total_tanpa_pajak = parseFloat(after_diskon) - parseFloat(dua)
    $("#sisa_tagihan_tanpa_pajak").val(total_tanpa_pajak) // menghitung tagihan tanpa pajak
    $("#sisa_tagihan_tanpa_pajak_rupiah").val(formatRupiah(total_tanpa_pajak))
});

$(document).on('change keyup', "#Ya", "#total_after_diskon",  function() {
    var val1 = $("#total_after_diskon").val()
    var result = (val1 * 10) /100
    $("#pajak").val(result)
    $("#pajak_rupiah").val(formatRupiah(result))

    total_setelah_pajak = parseFloat(result) + parseFloat(val1)
    $('#total_setelah_pajak').val(total_setelah_pajak)
    $('#total_setelah_pajak_rupiah').val(formatRupiah(total_setelah_pajak))
}); // hitung pajak

$(document).on('change keyup', "#pembayaran", "#total_setelah_pajak",  function() {
    var val1 = $("#total_setelah_pajak").val()
    var val2 = $("#pembayaran").val()

    var tiga = Number(val2.replace(/[^0-9.-]+/g,""));
    var result = parseFloat(val1) - parseFloat(tiga)
    $("#sisa_tagihan").val(result)
    $("#sisa_tagihan_pajak_rupiah").val(formatRupiah(result))
}); // sisa tagihan

$('#id_visit').change(function(){
    var id_visit = $(this).val();
    var url = '{{ route("cariPasien", ":id_visit") }}';
    url = url.replace(':id_visit', id_visit); // mengirim parameter ke ajax untuk request cari pasien

$.ajax({
  url: url,
  type: 'get',
  dataType: 'json',
  success: function(response){
    if(response != null){
      if (response.flag_discharge == 'Y') {
          swal('Warning','Maaf transaksi tidak bisa di lakukan karena pasien sudah pulang rawat. Terimakasih','warning')
      } else {
        $('#nama_pasien').val(response.nama_pasien);
        $('#id_pelanggan').val(response.id_pelanggan);
        $('#id_perkiraan').val(response.id_perkiraan);
    }}}
  });
}); // request ajax untuk mendapat nama pasien

//fungsi form dinamis
$(document).ready(function() {
var i = 0

$('#add').click(function() {
i++;

$('#tambah_form').append('<tr id="row' + i + '">\n\
  <td><input type="text" id="barcode-' + i + '" name="barcode[]" class="form-control" ></td>\n\
  <input type="hidden" id="id_barang-' + i + '" name="id_barang[]" class="form-control" readonly >\n\
  <input type="hidden" id="id_packing_barang-' + i + '" name="id_packing_barang[]" class="form-control" readonly>\n\
  <td><input type="text" id="barang-' + i + '" name="barang[]" class="form-control" readonly required></td>\n\
  <td width="13%"><input type="text" id="hna-' + i + '" name="hna[]" class="form-control" readonly required></td>   \n\
  <td width="6%"><input type="text" id="margin-' + i + '" name="margin[]" class="form-control" required></td>\n\
  <td width="7%"><input type="text" id="qty-' + i + '" name="qty[]" class="form-control" required></td>\n\
  <td><input type="text" id="satuan-' + i + '" name="satuan[]"  class="form-control" readonly></td>\n\
  <td><input type="text" id="harga-' + i + '" name="harga[]" class="form-control harga" readonly></td>\n\
  <td width="7%"><input type="text" id="diskon-' + i + '" name="diskon[]" class="form-control"></td></div>\n\
  <td><input type="text" id="total_setelah_diskon-' + i + '" name="total_setelah_diskon[]" class="form-control total_setelah_diskon" readonly></td>\n\
  <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td></tr>');


$('#hna-'+i).on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

 // hitung total penjualan
$(function () {
    $('#hna-'+i, '#margin-' +i, '#qty-'+i, '#harga-'+i, '#total_penjualan').prop('readonly', true);
      var $tblrows = $("#tambah_form tbody tr");

  $tblrows.each(function (index) {
    var $tblrow = $(this);
      $tblrow.find('#qty-'+i).on('keyup change', function () {

    var hna = $tblrow.find('#hna-'+i).val();
    var margin = $tblrow.find('#margin-'+i).val();
    var qty = $tblrow.find('#qty-'+i).val();
    var hna_rupiah = Number(hna.replace(/[^0-9.-]+/g,""));
    var subTotal = hna_rupiah * parseFloat(margin) * parseInt(qty, 10);

    if (!isNaN(subTotal)) {
        $tblrow.find('#harga-'+i).val(formatRupiah(subTotal));
        var grandTotal = 0;

        $(".harga").each(function () {
            var stval = $(this).val();
            var total_harga = Number(stval.replace(/[^0-9.-]+/g,""));
            grandTotal += isNaN(total_harga) ? 0 : total_harga;
        });

      $('#total_penjualan').val(grandTotal.toFixed(2));
      $('#total_penjualan_rupiah').val(formatRupiah(grandTotal));
      }
    });
  });
});

//hitung total setelah diskon
$(function totaldiskon() {
  $('#harga-'+i, '#id_barang-'+i, '#diskon-'+i, '#total_setelah_diskon-'+i).prop('readonly', true);
      var $tabelrows = $("#tambah_form tbody tr");

$tabelrows.each(function (totaldiskon) {
  var $tabelrow = $(this);
    $tabelrow.find('#diskon-'+i).on('keyup change', function () {

  var harga = $tabelrow.find("#harga-"+i).val();
  var diskon = $tabelrow.find("#diskon-"+i).val();
  var harga_rupiah = Number(harga.replace(/[^0-9.-]+/g,""));

  var SubTotal = harga_rupiah * parseInt(diskon,10) /100;
  var total_setelah_diskon = harga_rupiah - SubTotal;

    if (!isNaN(total_setelah_diskon)) {
        $tabelrow.find('#total_setelah_diskon-'+i).val(formatRupiah(total_setelah_diskon));

        var grandTotal = 0;

        $('.total_setelah_diskon').each(function () {
          var total_potong_diskon = $(this).val();
          var totpotkon = Number(total_potong_diskon.replace(/[^0-9.-]+/g,""));
            grandTotal += totpotkon;
        });
        $('#total_after_diskon').val(grandTotal.toFixed(2));
        $('#diskon_rupiah').val(formatRupiah(grandTotal));
    }});
  });
});

// isi barcode untuk cari barang hna qty
$('#barcode-' +i).change(function(){
    var barcode = $(this).val();
    var url = '{{ route("isiBarang", ":barcode") }}';
    url = url.replace(':barcode', barcode);
// isi diskon otomatis
$('#status_diskon-' +i).change(function(){
    var status_diskon = $(this).val();
    var link = '{{ route("isiDiskon", ":status_diskon") }}';
    link = link.replace(':status_diskon', status_diskon);
}); // akhir dari fungsi form dinamis

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
      success: function(response){
        if(response != null){
          $('#id_packing_barang-'+i).val(response.id_packing_barang)
          $('#id_barang-' +i).val(response.id_barang);
          $('#barang-' +i).val(response.barang);
          $('#satuan-' +i).val(response.satuan);
          $('#hna-' +i).val(formatRupiah(response.hna));
        }}
     });
   });
}); // request ajax untuk mendapat data ke form dinamis

$(document).on('click', '.btn_remove', function() {
    var button_id = $(this).attr("id");
    $('#row' + button_id + '').remove();
  }); // fungsi untuk hapus form dinamis
});

</script>
@endpush
