@extends('layouts.app')

@section('content')

<style>
.form-control{
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}
select {
    min-width: 125px;
}
<style>

th {
    text-align: left; font-weight: 600;
}

table {
    border-collapse: collapse; border: 1px solid #999; width: 100%;
}

table td,
table th {
    border: 1px solid #ccc;
}

table input {
    max-width: 100%;
    border: 1px solid #ccc;
}
table td:first-child input {
    width: 50px;
}

#master {
    display: none;
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
			                <input type="text" name="kode" class="form-control" value="{{ $kode_awal }}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Kode Jurnal</label>
			                <div class="col-md-7">
			                <input type="text" name="kode_jurnal" class="form-control" value="{{ $kode_jurnal }}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
				            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control">
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
				                @foreach ($kasBank as $id=> $bank)
				                <option value="{{ $id }}">{{ $bank }}</option>
				                @endforeach
          		            </select>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Diterima Dari</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control" name="diterima_oleh" id="diterima_oleh" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Total Nominal</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control total_nominal_rupiah" id="total_nominal_rupiah" readonly>
                            <input type="hidden" id="total_nominal" name="total_nominal" readonly>
                        </div>
                    </div>

                    <button type="button" name="add" id="add" class="btn btn-danger">Tambah Form</button>

                    <table class="table table-bordered" id="dynamic_field">
                        <tr>
                            <th>Pajak</th>
                            <th>Rekening</th>
                            <th>Cost Centre</th>
                            <th>Keterangan</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                        </tr>

                        <tr></tr>
                    </table>
                <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-floppy-saved" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

function formatRupiah(number) {
    return number.toString().replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).ready(function() {
    $('#id_bank').select2({   theme: "bootstrap-5", width:'100%'});
    var i = 1;

    $('#add').click(function() {
      i++;
      var baris = $('#dynamic_field').append('<tr id="row' + i + '" class="dynamic-added">\n\
        <td><select class="form-control cari " name="id_tarif_pajak[]" style="width: 100px;">\n\
            <option value="">Pilih Tarif Pajak</option>\n\
            @foreach ($tarifPajak as $id=> $tarif)<option value="{{ $id }}">{{ $tarif }}</option> @endforeach\n\
            </select></td>\n\
        <td><select class="form-control cari " name="id_perkiraan[]">\n\
            <option value="">Pilih Rekening</option>\n\
            @foreach ($perkiraan as $kira)<option value="{{ $kira->id }}">{{ $kira->kode_rekening }} - {{ $kira->nama }}</option> @endforeach\n\
            </select>\n\
        </td>\n\
        <td><select class="form-control cari" name="id_unit[]">\n\
            <option value="">Pilih Cost Centre</option>\n\
            @foreach ($unit as $u)<option value="{{ $u->id }}">{{ $u->code_cost_centre}} - {{ $u->nama }}</option> @endforeach\n\
            </select>\n\
        </td>\n\
        <td><input type="text" name="keterangan[]" class="form-control name_list "  style="width: 120px;"/></td>\n\
        <td><select class="form-control name_list  tipe" name="tipe[]">\n\
            <option value="">Pilih Tipe</option>\n\
            <option value="1">Penambah</option>\n\
            <option value="-1">Pengurang</option>\n\
            </select></td>\n\
        <td><input type="number" name="nominal[]" class="form-control name_list " required="" style="min-width:100px" required/>\n\
            <input type="hidden" name="jumlah[]" class="form-control name_list " required="" /></td>\n\
        <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td></tr>');

        //$('select[name="id_perkiraan[]"]:not([class^="select2"])').select2({width: "250px"});
        //$('select[name="id_unit[]"]:not([class^="select2"])').select2({width: "250px"});

        baris.find('.cari').select2({   theme: "bootstrap-5", width:'250px'});
    });

	function computeSum() {
    	var totalPrice = 0;
    	$('input[name="jumlah[]"]').each(function(key, total) {
      	    totalPrice += parseInt(total.value || 0, 10);
        });

        $('#total_nominal_rupiah').val(formatRupiah(totalPrice)).change();
        $('#total_nominal').val(totalPrice).change();
    }

    function eventChange (element) {
        const tr = $(element).closest('tr');
        const tipe = tr.find('select[name="tipe[]"]');
        const nominal = tr.find('input[name="nominal[]"]');
        const jumlah = tr.find('input[name="jumlah[]"]');
        const total = (tipe.val() || 1) * (nominal.val() || 0);
        jumlah.val( total );
        computeSum();
    }

    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");

        $('#row' + button_id + '').remove();
    });

    $(document).on('change', 'select[name="id_tarif_pajak[]"]', function(){
        var id_tarif_pajak = $(this).val();

        const elPerkiraan = $(this).closest('tr').find('select[name="id_perkiraan[]"]');

        var url = '{{ route("isiRekeningMasuk", ":id_tarif_pajak") }}';
        url = url.replace(':id_tarif_pajak', id_tarif_pajak);

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(response){
            if(response != null){
                elPerkiraan.val(response.id_perkiraan).change();
            }}
        });


        if (id_tarif_pajak) {
            $(this).closest('tr').find('select[name="tipe[]"]').val(-1);
        } else {
            $(this).closest('tr').find('select[name="tipe[]"]').val(1);
        }

        eventChange(this);
    });


    $(document).on('change', 'select[name="tipe[]"]', function(e) {
        if ($(this).val() == 1) {
            $(this).closest('tr').find('select[name="id_tarif_pajak[]"]').val('');
        }

        eventChange(this);


    	// var tipe = $(e.target);

        // if (tipe.val()) {
        //     var tr = tipe.closest('tr');
        //     var nominal = tr.find('input[name="nominal[]"]');
        //     var jumlah = tr.find('input[name="jumlah[]"]');
        //     jumlah.val(nominal.val() * parseInt(tipe.val()));
        //     computeSum();
        // }

    });


    $(document).on('input', 'input[name="nominal[]"]', function(e) {
        eventChange(this);

    	// var nominal = $(e.target);

        // if (nominal.val()) {

        //     var tr = nominal.closest('tr');
        //     var tipe = tr.find('select[name="tipe[]"]');
        //     var jumlah = tr.find('input[name="jumlah[]"]');
        //     jumlah.val(tipe.val() * nominal.val());
        //     computeSum();
        // }
    });


});
</script>
@endpush
