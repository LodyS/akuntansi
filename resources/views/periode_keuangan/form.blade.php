<div class="modal-dialog modal-simple">

		{{ Form::model($periodeKeuangan,array('route' => array((!$periodeKeuangan->exists) ? 'periode-keuangan.store':'periode-keuangan.update',$periodeKeuangan->pk()),
	        'class'=>'modal-content','id'=>'periode-keuangan-form','method'=>(!$periodeKeuangan->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($periodeKeuangan->exists?'Edit':'Tambah').' Periode Keuangan' }}</h4>
    </div>
    <div class="modal-body">

	<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">

		<div class="form-group row">
			<label class="col-md-3">Tanggal Awal</label>
				<div class="col-md-7">
				@if (isset($periodeKeuangan->tanggal_awal))
				<input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $periodeKeuangan->tanggal_awal }}" class="form-control" readonly>
				@else
				<input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $carbon->firstOfMonth()->toDateString() }}" class="form-control" readonly>
				@endif
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Tanggal akhir</label>
				<div class="col-md-7">
				@if (isset($periodeKeuangan->tanggal_akhir))
				<input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $periodeKeuangan->tanggal_akhir }}" class="form-control" readonly>
				@else
				<input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $carbon->lastOfMonth()->toDateString() }}" class="form-control" readonly>
				@endif
			</div>
		</div>

		<p id="pesan"></p>

		<input type="hidden" name="status_aktif" id="status_aktif"
		value="{{($periodeKeuangan->exists?!empty($periodeKeuangan->status_aktif)?$periodeKeuangan->status_aktif:'Y':'Y')}}" class="form-control" type="text">

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
	$('#periode-keuangan-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	tanggal_awal : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal_awal tidak boleh kosong'
							}
						}
					},tanggal_akhir : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal_akhir tidak boleh kosong'
							}
						}
					},status_aktif : { validators: {
				        notEmpty: {
				          message: 'Kolom status_aktif tidak boleh kosong'
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
