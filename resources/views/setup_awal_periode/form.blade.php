<div class="modal-dialog modal-simple">

		{{ Form::model($setupAwalPeriode,array('route' => array((!$setupAwalPeriode->exists) ? 'setup-awal-periode.store':'setup-awal-periode.update',$setupAwalPeriode->pk()),
	        'class'=>'modal-content','id'=>'setup-awal-periode-form','method'=>(!$setupAwalPeriode->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($setupAwalPeriode->exists?'Edit':'Tambah').' Setup Awal Periode' }}</h4>
    </div>
    <div class="modal-body">
	
	<div class="form-group row">
		<label class="col-md-3">Tanggal Setup</label>
			<div class="col-md-7">
            <input type="date" name="tanggal_setup"  class="form-control" value="{{ date('Y-m-d')}}" required>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Transaksi Pertama</label>
			<div class="col-md-7">
            <input type="date" name="transaksi_pertama" id="transaksi_pertaman" class="form-control" value="{{ date('Y-m-d')}}" required>
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
	$('#setup-awal-periode-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	tanggal_setup : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal_setup tidak boleh kosong'
							}
						}
					},transaksi_pertama : { validators: {
				        notEmpty: {
				          message: 'Kolom transaksi_pertama tidak boleh kosong'
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
