<div class="modal-dialog modal-simple">

		{{ Form::model($barang,array('route' => array((!$barang->exists) ? 'barang.store':'barang.update',$barang->pk()),
	        'class'=>'modal-content','id'=>'barang-form','method'=>(!$barang->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($barang->exists?'Edit':'Tambah').' Barang' }}</h4>
    </div>
    <div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($barang)->show() !!}

		<div class="form-group row">
			<label class="col-md-3">Sub Kategori Barang</label>
				<div class="col-md-7">
				<select name="id_sub_kategori_barang" class="form-control select" id="id_sub_kategori_barang">
				<option value="--">Pilih Kategori Barang</option>
                @foreach ($SubKategoriBarang as $subbarang)
                <option value="{{ $subbarang->id}}" {{($subbarang->id == $barang->id_sub_kategori_barang)?'selected':''}}>{{ $subbarang->nama}}</option>
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
	$('#barang-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama : { validators: {
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
