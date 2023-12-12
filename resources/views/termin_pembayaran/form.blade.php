<div class="modal-dialog modal-simple">

		{{ Form::model($terminPembayaran,array('route' => array((!$terminPembayaran->exists) ? 'termin-pembayaran.store':'termin-pembayaran.update',$terminPembayaran->pk()),
	        'class'=>'modal-content','id'=>'termin-pembayaran-form','method'=>(!$terminPembayaran->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($terminPembayaran->exists?'Edit':'Tambah').' Termin Pembayaran' }}</h4>
    </div>
    <div class="modal-body">
		{{-- <div class="form-body"> --}}

				<div class="form-group row">
					<label class="col-md-3">Kode</label>
					<div class="col-md-7">
					<input name="kode" id="kode" value="{{($terminPembayaran->exists?!empty($terminPembayaran->kode)?$terminPembayaran->kode:'':'')}}" class="form-control" type="text">
						<span class="help-block" id="kode_a"></span>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Termin</label>
					<div class="col-md-7">
					<input name="termin" id="termin" value="{{($terminPembayaran->exists?!empty($terminPembayaran->termin)?$terminPembayaran->termin:'':'')}}" class="form-control" type="text">
						<span class="help-block" id="termin_a"></span>
					</div>
				</div>

				<div class="form-group row">
						<label class="col-md-3">Keterangan</label>
						<div class="col-md-7">
						<input name="deskripsi" id="deskripsi" value="{{($terminPembayaran->exists?!empty($terminPembayaran->deskripsi)?$terminPembayaran->deskripsi:'':'')}}" class="form-control" type="text">
							<span class="help-block" id="deskripsi_a"></span>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3">Diskon</label>
						<div class="col-md-7">
						<input name="diskon" id="diskon" value="{{($terminPembayaran->exists?!empty($terminPembayaran->diskon)?$terminPembayaran->diskon:'':'')}}" class="form-control" type="number">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3">Minimal tanggal pembayaran</label>
						<div class="col-md-7">
						<input name="min_pembayaran" id="min_pembayaran" value="{{($terminPembayaran->exists?!empty($terminPembayaran->min_pembayaran)?$terminPembayaran->min_pembayaran:'':'')}}" class="form-control" type="number" min="0" max="31">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3">Denda</label>
						<div class="col-md-7">
						<input name="denda" id="denda" value="{{($terminPembayaran->exists?!empty($terminPembayaran->denda)?$terminPembayaran->denda:'':'')}}" class="form-control" type="number">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3">Maksimal tanggal pembayaran</label>
						<div class="col-md-7">
						<input name="jumlah_hari" id="jumlah_hari" value="{{($terminPembayaran->exists?!empty($terminPembayaran->jumlah_hari)?$terminPembayaran->jumlah_hari:'':'')}}" class="form-control" type="number" min="0" max="31">
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
$(document).ready(function(){

	$('#kode').change(function () {
    var kode = $(this).val();
    var url = '{{ route("cekKodeTerminPembayaran", ":kode") }}';
    url = url.replace(':kode', kode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Kode Termin Pembayaran sudah ada','warning')
        }}
    });
});

	$('#termin-pembayaran-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode : { validators: {
				        notEmpty: {
				          message: 'Kolom Termin tidak boleh kosong'
							}
						}
					},deskripsi : { validators: {
				        notEmpty: {
				          message: 'Kolom Keterangan tidak boleh kosong'
							}
						}
					},diskon : { validators: {
				        notEmpty: {
				          message: 'Kolom diskon tidak boleh kosong'
							}
						}
					},min_pembayaran : { validators: {
				        notEmpty: {
				          message: 'Kolom Minimal tanggal Pembayaran tidak boleh kosong'
							}
						}
					},denda : { validators: {
				        notEmpty: {
				          message: 'Kolom denda tidak boleh kosong'
							}
						}
					},jumlah_hari : { validators: {
				        notEmpty: {
				          message: 'Kolom Maksimal tanggal pembayaran tidak boleh kosong'
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
