<div class="modal-dialog modal-simple">

		{{ Form::model($deposit,array('route' => array((!$deposit->exists) ? 'deposit.store':'deposit.update',$deposit->pk()),
	        'class'=>'modal-content','id'=>'deposit-form','method'=>(!$deposit->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($deposit->exists?'Edit':'Tambah').' Deposit' }}</h4>
    </div>
    <div class="modal-body">

	<input type="hidden" name="status" id="status" value="1" class="form-control">
	<input type="hidden" name="ref" id="ref" value="N" class="form-control">

	<div class="form-group row">
		<label class="col-md-3">No Pasien</label>
			<div class="col-md-7">
			<input type="number" name="id_pelanggan" id="id_pelanggan" value="{{ isset($deposit) ? $deposit->id_pelanggan : '' }}" class="form-control">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">No Kunjungan</label>
			<div class="col-md-7">
			<input type="number" name="id_visit" id="id_visit" value="{{ isset($deposit) ? $deposit->id_visit : '' }}" class="form-control" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Nama Pasien</label>
			<div class="col-md-7">
			@if(isset($deposit))
			<input type="text" id="nama_pasien"  value="{{ $deposit->nama_pasien }}" class="form-control" readonly>
			@else
			<input type="text" id="nama_pasien"  class="form-control" readonly>
			@endif
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Waktu</label>
			<div class="col-md-7">
			@if (isset($deposit->waktu))
			<input type="date" name="waktu" value="{{ $deposit->waktu }}" class="form-control">
			@else
			<input type="date" name="waktu" value="{{ date('Y-m-d') }}"  class="form-control">
			@endif
		</div>
	</div>

    <div class="form-group row">
        <label class="col-md-3">Cara Bayar</label>
        <div class="col-md-7">
            <select name="id_bank" id="id_bank" class="form-control" required>
                <option value="">Pilih Bank</option>
                @foreach ($bank as $b)
                <option value="{{ $b->id}}">{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

	<div class="form-group row">
		<label class="col-md-3">Jumlah Deposit</label>
			<div class="col-md-7">
			@if (isset($deposit))
			<input type="text" name="kredit" id="kredit" value="{{ $deposit->kredit }}" class="form-control">
			@else
			<input type="text" name="kredit"  id="kredit" class="form-control">
			@endif
		</div>
	</div>

		<div class="col-md-12 float-right">
			<div class="text-right">
				<button class="btn btn-primary" id="simpan">Simpan</button>
			</div>
		</div>
	</div>
	{{ Form::close() }}
</div>


<script type="text/javascript">

$('#kredit').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\,\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
})); // untuk format uang di form

$('#id_pelanggan').change(function(){
    var id_pelanggan = $(this).val();
    var url = '{{ route("isiPasienDeposit", ":id_pelanggan") }}';
    url = url.replace(':id_pelanggan', id_pelanggan);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
            if( response.id_visit){
                if (response.flag_discharge == 'Y') {
                    swal('Warning','Maaf transaksi tidak bisa di lakukan karena pasien sudah pulang rawat. Terimakasih','warning')
                } else {
                    $('#id_visit').val(response.id_visit);
                    $('#nama_pasien').val(response.nama_pasien);
                }
            } else {
                swal('Warning','No Pasien tersebut tidak memiliki kunjungan hari ini.','warning')
            }
        }
    });
});

$(document).ready(function(){

$('#deposit-form').formValidation({
	framework: "bootstrap4",
	button: {
	selector: "#simpan",
	disabled: "disabled"
},

	icon: null,
	fields: {

	id_pelanggan : { validators: {
		notEmpty: {
			message: 'Kolom no pasien tidak boleh kosong'
			}
		}
	},

	no_kunjungan : { validators: {
		notEmpty: {
			message: 'Kolom no kunjungan tidak boleh kosong'
			}
		}
	},

	kredit : { validators: {
		notEmpty: {
			message: 'Kolom kredit tidak boleh kosong'
			}
		}
	}
},
err: {
	clazz: 'invalid-feedback'
},
control: {
	// The CSS class for valid control
	valid: 'is-valid',

	// The CSS class for invalid control
	invalid: 'is-invalid'
},
row: {
	invalid: 'has-danger'
}
});

});
</script>
