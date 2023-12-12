<div class="modal-dialog modal-simple">

		{{ Form::model($tarifPajak,array('route' => array((!$tarifPajak->exists) ? 'tarif-pajak.store':'tarif-pajak.update',$tarifPajak->pk()),
	        'class'=>'modal-content','id'=>'tarif-pajak-form','method'=>(!$tarifPajak->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($tarifPajak->exists?'Edit':'Tambah').' Tarif Pajak' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('nama_pajak','text')->model($tarifPajak)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('persentase_pajak','text')->model($tarifPajak)->show() !!}
                                                                        <div class="form-group row">
		<label class="col-md-3">Rekening</label>
			<div class="col-md-7">
			<select name="id_perkiraan" id="id_perkiraan"  class="form-control">
				<option value="">Pilih Rekening</option>
				@foreach ($perkiraan as $rekening)
                <option value="{{ $rekening->id}}" {{ ($rekening->id== $tarifPajak->id_perkiraan)?'selected':''}}>{{ $rekening->kode_rekening }} - {{ $rekening->nama }}</option>
				@endforeach
            </select>
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
	$('#tarif-pajak-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama_pajak : { validators: {
				        notEmpty: {
				          message: 'Kolom nama_pajak tidak boleh kosong'
							}
						}
					},persentase_pajak : { validators: {
				        notEmpty: {
				          message: 'Kolom persentase_pajak tidak boleh kosong'
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
