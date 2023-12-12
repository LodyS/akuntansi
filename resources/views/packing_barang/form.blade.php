<div class="modal-dialog modal-simple">

		{{ Form::model($packingBarang,array('route' => array((!$packingBarang->exists) ? 'packing-barang.store':'packing-barang.update',$packingBarang->pk()),
	        'class'=>'modal-content','id'=>'packing-barang-form','method'=>(!$packingBarang->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($packingBarang->exists?'Edit':'Tambah').' Packing Barang' }}</h4>
    </div>
    <div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('barcode','text')->model($packingBarang)->show() !!}
		{!! App\Console\Commands\Generator\Form::input('satuan','text')->model($packingBarang)->show() !!}
	
		<div class="form-group row">
			<label class="col-md-3">Persediaan</label>
				<div class="col-md-7">
				<select name="id_barang" class="form-control select" id="id_barang">
					<option value="--">Pilih Barang</option>
                    @foreach ($Barang as $barang)
                    <option value="{{ $barang->id}}" {{( $packingBarang->id_barang == $barang->id)?'selected':''}}>{{ $barang->nama}}</option>
                    @endforeach
                </select>		
			</div>
		</div>	

		<div class="form-group row">
			<label class="col-md-3">Departemen</label>
				<div class="col-md-7">
				<select name="id_unit" class="form-control select" id="id_unit">
					<option value="--">Pilih Unit</option>
                    @foreach ($Unit as $unit)
                    <option value="{{ $unit->id}}" {{($packingBarang->id_unit == $unit->id)?'selected':''}}>{{ $unit->nama}}</option>
                    @endforeach
                </select>		
			</div>
		</div>	

	<div class="form-group row">
		<label class="col-md-3">HNA</label>
			<div class="col-md-7">
			<input type="text" class="form-control" name="hna" 
			value="{{ isset($packingBarang) ? number_format($packingBarang->hna,2,",",".") : '' }}" id="hna">
		</div>
	</div>

		{!! App\Console\Commands\Generator\Form::input('hpp','text')->model($packingBarang)->show() !!}
	
		<div class="col-md-12 float-right">
			<div class="text-right">
				<button class="btn btn-primary" id="simpan">Simpan</button>
				</div>
			</div>
		</div>
	{{ Form::close() }}
</div>


<script type="text/javascript">

$(".select").select2({
    dropdownParent: $("#packing-barang-form"),
    width: '100%'
});

$('#hna').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).ready(function(){
	$('#packing-barang-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	barcode : { validators: {
				        notEmpty: {
				          message: 'Kolom barcode tidak boleh kosong'
							}
						}
					},

					hpp : { validators: {
				        notEmpty: {
				          message: 'Kolom barang tidak boleh kosong'
							}
						}
					},

					hna : { validators: {
				        notEmpty: {
				          message: 'Kolom hna tidak boleh kosong'
							}
						}
					},
					
					
					
					
					satuan : { validators: {
				        notEmpty: {
				          message: 'Kolom hpp tidak boleh kosong'
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
