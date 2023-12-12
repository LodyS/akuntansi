@extends('layouts.app')

@section('content')

<style>
.form-control{
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}
</style>

<div class="page-header">
    <h1 class="page-title">Mutasi Penerimaan Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center">Tambah Mutasi Penerimaan Kas</h3><br/>
	            <form action="{{ url('/simpan-mutasi-penerimaan-kas')  }}" method="post">{{ @csrf_field() }}

                    <div class="form-group row">
		                <label class="col-md-3">Kode</label>
			                <div class="col-md-7">
			                <input type="text" name="kode" class="form-control round" value="{{ $kode_awal }}" readonly>
		                </div>
	                </div>

                    <!--<div class="form-group row">
			            <label class="col-md-3">Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
				                <select id="id_pembayaran" class="form-control select round">
            		            <option value="">Pilih Jenis Pembayaran Kas</option>
					            @foreach ($arusKas as $kas)
            		            <option value="{{ $kas->id}}">{{ $kas->nama }}</option>
            		            @endforeach
          		            </select>
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Sub Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
					        <select id="id_arus_kas" name="id_arus_kas" class="form-control select round">
					            <option value="">Pilih Sub Jenis Penerimaan Kas</option>
                            </select>
			            </div>
		            </div>-->

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
				            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control round">
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                            <div class="col-md-7">
                            <textarea class="form-control" id="keterangan" name="keterangan_awal" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
			            <label class="col-md-3">Penerimaan</label>
				            <div class="col-md-7">
				            <select name="id_kas_bank" id="id_bank" class="form-control select">
            	                <option value="">Pilih Bank</option>
				                @foreach ($kasBank as $bank)
				                <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
				                @endforeach
          		            </select>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Total Nominal</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control round total_nominal_rupiah" id="total_nominal_rupiah" readonly>
                            <input type="hidden" id="total_nominal total_nominal" name="total_nominal" readonly>
                        </div>
                    </div>

                    <tbody>
                        <button class="btn btn-dark btn-round" type="button" id="add"><i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i>Tambah</button>
                    </tbody>

                    <table class="table table-hover" id="tambah_form">
                        <tr>
                            <th>Pajak</th>
                            <th>Rekening</th>
                            <th>Tipe</th>
                            <th>Keterangan</th>
                            <th>Cost Centre</th>
                            <th>Nominal</th>
                        </tr>
                    </table>

                <button type="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-floppy-saved" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

function formatRupiah(number) {
  return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('#id_bank').select2({
        width: '100%',
        theme: 'bootstrap4'
    });

$(document).ready(function() {

var i = 0

$('#add').click(function() {
    //$(this).prop("disabled",true);

    i++;
    $('#tambah_form').append('<tr id="row' + i + '">\n\
        <td><select class="form-control select" name="id_tarif_pajak[]" id="id_tarif_pajak-' + i + '">\n\
            <option value="">Pilih Tarif Pajak</option>\n\
            @foreach ($tarifPajak as $tarif)<option value="{{ $tarif->id }}">{{ $tarif->nama_pajak }}</option> @endforeach\n\
            </select>\n\
        </td>\n\
        <td><select class="form-control select" name="id_perkiraan[]" id="id_perkiraan-' + i + '">\n\
            <option value="">Pilih Rekening</option>\n\
            @foreach ($perkiraan as $kira)<option value="{{ $kira->id }}">{{ $kira->kode_rekening }} - {{ $kira->nama }}</option> @endforeach\n\
            </select>\n\
        </td>\n\
        <td><select class="form-control tipe round" name="type[]" id="tipe-'+i+'">\n\
            <option value="">Pilih Tipe</option>\n\
            <option value="1">Penambah</option>\n\
            <option value="-1">Pengurang</option>\n\
            </select>\n\
        </td>\n\
        <td><input type="text" class="form-control round" name="keterangan[]" id="keterangan-'+i+'"></td>\n\
        <td><select class="form-control select" name="id_unit[]" id="id_unit-' + i + '">\n\
            <option value="">Pilih Cost Centre</option>\n\
            @foreach ($unit as $u)<option value="{{ $u->id }}">{{ $u->code_cost_centre}} - {{ $u->nama }}</option> @endforeach\n\
            </select>\n\
        </td>\n\
        <td><input type="text" class="form-control round" data-action="sumNominal" name="nominal[]" id="nominal-'+i+'" readonly></td>\n\
        <td><input type="hidden" class="form-control round total" data-action="sumTotal" name="total[]" id="total-'+i+'" readonly></td>\n\
        <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove btn-round"><i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i></button></td></tr>');


    $('#nominal-'+i).on('change click keyup input paste',(function (event) {
        $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('.tipe').change(function(){
    var tipe = $(this).val();

	if(tipe === "1" || tipe==='-1'){
		$('#nominal-'+i).attr("readonly", false);
        //$('#add').prop("disabled",false);
	} else {
		$('#nominal-'+i).attr("readonly", true);
        //$('#add').prop("disabled",true);
    }
});

//alert('Perhatian! harap isi tipe terlebih dahulu agar bisa mengisi nominal dan bisa menambah form selanjutnya.')

$('body').on('change keyup', '[data-action="sumNominal"]', function() {
  hitungTotal();
});

function hitungTotal() {
    var $tblrows = $("#tambah_form tbody tr");

        $tblrows.each(function (index) {
            var $tblrow = $(this);
            $tblrow.find('[data-action="sumNominal"]').on('change keyup', function () { //digunakan input kredit supaya mendapat jumlah debet/kredit otomatis

            var grandNominal = 0;
            var tipe = parseInt($tblrow.find('#tipe-'+i).val())
            var nominal = $tblrow.find('#nominal-'+i).val()
            var nominall = Number(nominal.replace(/[^0-9]+/g,""));
            var total_nominal = tipe * nominall


            $tblrow.find('#total-'+i).val(total_nominal);

            $('.total').each(function () {
                var total = $(this).val();
                var total2 = Number(total); //untuk menghapus koma pada nominal debet
                var total3 = grandNominal += +total2;

                $('#total_nominal').val(total3).change();
                $('#total_nominal_rupiah').val(formatRupiah(total3)).change();
            });
        });
    });
};

$('#id_tarif_pajak-' +i).change(function(){
    var id_tarif_pajak = $(this).val();
    var url = '{{ route("isiRekeningMasuk", ":id_tarif_pajak") }}';
    url = url.replace(':id_tarif_pajak', id_tarif_pajak);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        if(response != null){
            $('#id_perkiraan-' +i).val(response.id_perkiraan).change();
        }}
    });
});

$('#id_perkiraan-' + i).select2({
    theme: 'bootstrap4',
    width: '100%'
});

$('#tipe-' + i).select2({
    theme: 'bootstrap4',
    width: '100%'
});


$('#id_unit-' + i).select2({
    theme: 'bootstrap4',
    width: '100%'
});

$('#id_tarif_pajak-' + i).select2({
    theme: 'bootstrap4',
    width: '100%'
});


    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        }); // untuh hapus form dinamis
    });
});
</script>
@endpush
