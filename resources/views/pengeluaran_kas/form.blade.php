<div class="modal-dialog modal-simple">

<form action="{{ ($aksi == 'update') ? url('/update-pengeluaran-kas') : url('/simpan-pengeluaran-kas')  }}" method="post" id="pengeluaran-kas-form"
class="modal-content" enctype="multipart/form-data">
{{ @csrf_field() }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($aksi == 'update') ? 'Edit' : 'Tambah' }} Pengeluaran Kas</h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
        <input type="hidden" name="user_update" value="{{ Auth::user()->id }}">
        <input type="hidden" name="ref" value="N">
        {{-- <input type="hidden" name="no_jurnal" value="0"> --}}
        <input type="hidden" name="tipe" value="1">
        <input type="hidden" name="id" value="{{ isset($MutasiKas) ? $MutasiKas->id : '' }}">

        <div class="form-group row">
            <label class="col-md-3">Kode</label>
            <div class="col-md-7">
                @if ($MutasiKas->kode != null)
                <input type="text" name="kode" value="{{ $MutasiKas->kode }}" class="form-control" readonly>
                @elseif ($kode == null)
                <input type="text" name="kode" value="BKK-1" class="form-control" readonly>
                @else
                <input type="text" name="kode" value="{{ $kode->kode }}" class="form-control" readonly>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Tanggal</label>
            <div class="col-md-7">
                @if ($MutasiKas->tanggal == null)
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control">
                @else
                <input type="date" name="tanggal" value="{{ $MutasiKas->tanggal }}" class="form-control">
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Jenis Pengeluaran Kas</label>
            <div class="col-md-7">
                <select id="id_pengeluaran" class="form-control select">
                    <option value="">-- Pilih Jenis Pengeluaran Kas --</option>
                    @foreach ($ArusKas as $kas)
                    <option value="{{ $kas->id }}" {{( $MutasiKas->id_induk == $kas->id)?'selected':''}}>{{ $kas->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Sub Jenis Pengeluaran Kas</label>
            <div class="col-md-7">
                <select id="id_arus_kas" name="id_arus_kas" class="form-control select">
                <option value="">-- Pilih Sub Jenis Pengeluaran Kas --</option>
                    @if ($MutasiKas == null)
                    @foreach ($Arus as $kas)
                    <option value="{{ $kas->id }}" {{( $MutasiKas->id_arus_kas == $kas->id)?'selected':''}}>{{ $kas->nama }}</option>
                    @endforeach
                    @else
                    @foreach ($Arus as $kas)
                    <option value="{{ $kas->id }}" {{( $MutasiKas->id_arus_kas == $kas->id)?'selected':''}}>{{ $kas->nama }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>

        {!! App\Console\Commands\Generator\Form::textarea( 'keterangan' )->model($MutasiKas)->show() !!}

        <div class="form-group row">
            <label class="col-md-3">Pengeluaran</label>
            <div class="col-md-7">
                <select name="id_perkiraan" class="form-control select">
                    <option value="">-- Pilih Pengeluaran --</option>
                    @foreach ($Perkiraan as $perkiraan)
                    <option value="{{ $perkiraan->id }}" {{( $MutasiKas->id_perkiraan == $perkiraan->id)?'selected':''}}>{{ $perkiraan->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
			<label class="col-md-3">Cost Centre</label>
				<div class="col-md-7">
				<select name="id_unit" class="form-control select">
            		<option value="">Pilih Cost Centre</option>
					@foreach ($unit as $u)
					<option value="{{ $u->id }}" {{( $MutasiKas->id_unit == $u->id)?'selected':''}}>{{ $u->code_cost_centre}} - {{ $u->nama }}</option>
					@endforeach
          		</select>
			</div>
		</div>

        <div class="form-group row">
            <label class="col-md-3">Pembayaran</label>
            <div class="col-md-7">
                <select name="id_kas_bank" class="form-control select">
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($KasBank as $bank)
                    <option value="{{ $bank->id }}" {{( $MutasiKas->id_kas_bank == $bank->id)?'selected':''}}> {{ $bank->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Nominal</label>
                <div class="col-md-7">
                <input type="text" id="nominal" name="nominal" value="{{ isset($MutasiKas) ? number_format($MutasiKas->nominal,2,",",".") : '' }}"
                class="form-control">
            </div>
        </div>

        <div class="form-group row">
			<label class="col-md-3">Upload Bukti Transaksi</label>
				<div class="col-md-7">
				<input type="file" name="file" id="file" value="{{ isset($MutasiKas) ? $MutasiKas->file :'' }}" class="form-control">
				<input type="hidden" name="file_cek" value="{{ isset($MutasiKas) ? $MutasiKas->file : '' }}" class="form-control" readonly>
				Nama File : <input type="text" name="nama_file" id="nama_file" value="{{ isset($MutasiKas) ? $MutasiKas->nama_file :''}}"
				class="form-control">
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
    $(document).ready(function () {

$('#nominal').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

        $(".select").select2({
            dropdownParent: $("#pengeluaran-kas-form"),
            width: '100%'
        });

        //Pengeluaran Ubah
        $('#id_pengeluaran').change(function () {

            // Department id
            var id_pengeluaran = $(this).val();

            // Empty the dropdown
            $('#id_arus_kas').find('option').not(':first').remove();

            // AJAX request
            $.ajax({
                url: 'pengeluaran-kas/get-id/' + id_pengeluaran,
                type: 'get',
                dataType: 'json',
                success: function (response) {

                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {

                            var id = response['data'][i].id;
                            var nama = response['data'][i].nama;
                            var option = "<option value='" + id + "'>" + nama + "</option>";

                            $("#id_arus_kas").append(option);
                        }
                    }

                }
            });
        });

        $('#pengeluaran-kas-form').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                kode: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom kode tidak boleh kosong'
                        }
                    }
                }, nominal: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom nominal tidak boleh kosong'
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
