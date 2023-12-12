<div class="modal-dialog modal-simple">

	{{ Form::model($produkAsuransi,array('route' => array((!$produkAsuransi->exists) ? 'produk-asuransi.store':'produk-asuransi.update',$produkAsuransi->pk()),
	        'class'=>'modal-content','id'=>'produk-asuransi-form','method'=>(!$produkAsuransi->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="formModalLabel">{{ ($produkAsuransi->exists?'Edit':'Tambah').' Produk Asuransi' }}</h4>
</div>

<div class="modal-body">

	<div class="form-group row">
		<label class="col-md-3">Kode</label>
			<div class="col-md-7">
			<input type="text" name="kode" id="kode" class="form-control" value="{{ ($aksi =='create') ? $lastCode : $produkAsuransi->kode }}" readonly>
		</div>
	</div>

		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($produkAsuransi)->show() !!}

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
	$('#produk-asuransi-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode : { validators: {
				        notEmpty: {
				          message: 'Kolom kode tidak boleh kosong'
							}
						}
					},nama : { validators: {
				        notEmpty: {
				          message: 'Kolom nama tidak boleh kosong'
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
