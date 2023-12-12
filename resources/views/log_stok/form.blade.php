<div class="modal-dialog modal-simple">

{{ Form::model($logStok,array('route' => array((!$logStok->exists) ? 'log-stok.store':'log-stok.update',$logStok->pk()),
'class'=>'modal-content','id'=>'log-stok-form','method'=>(!$logStok->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="formModalLabel">{{ ($logStok->exists?'Edit':'Tambah').' Log Stok' }}</h4>
</div>
    
	<div class="modal-body">
		<input type="hidden" name="id_transaksi" value="{{ isset($jenisTransaksi) ? $jenisTransaksi->id :''}}">
		<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">	
		<input type="hidden" name="user_update" value="{{ Auth::user()->id }}">	
		
	<div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			@if (isset($logStok->waktu))
			<input type="text" value="{{date('d-m-Y', strtotime($logStok->waktu)) }}" class="form-control" name="waktu" id="waktu" readonly>
			@else
			<input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="waktu" id="waktu" readonly>
			@endif
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Barcode</label>
			<div class="col-md-7">
			@if (isset($logStok->barcode))
			<input type="text" class="form-control" name="barcode" value="{{ $logStok->barcode }}" id="barcode" readonly>
			@else
			<input type="text" class="form-control" name="barcode" id="barcode">
			@endif
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-md-3">Barang</label>
			<div class="col-md-7">
			<input type="text" class="form-control" name="barang" id="barang" value="{{ isset($logStok) ? $logStok->barang : '' }}" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Unit</label>
			<div class="col-md-7">
				<select name="id_unit" id="id_unit" class="form-control" required>  
        		<option value="">Pilih Unit</option>
            	@foreach ($unit as $Unit)
            	<option value="{{ $Unit->id}}" {{($logStok->id_unit == $Unit->id)?'selected':''}}>{{ $Unit->nama }}</option>
            	@endforeach
          	</select>
		</div>
	</div>

	<input type="hidden" class="form-control" value="{{ isset($logStok) ? $logStok->id_stok :'' }}" name="id_stok" id="id_stok" readonly>
	<input type="hidden" class="form-control" name="id_packing_barang" id="id_packing_barang" readonly>
	
	<div class="form-group row">
		<label class="col-md-3">Stok Awal</label>
			<div class="col-md-7">
			<input type="text" class="form-control" name="stok_awal" id="stok_awal" value="{{ isset($logStok) ? $logStok->stok_awal :'' }}" readonly>
		</div>
	</div>

	{!! App\Console\Commands\Generator\Form::input('selisih','text')->model($logStok)->show() !!}
	
	<div class="form-group row">
		<label class="col-md-3">Stok Akhir</label>
			<div class="col-md-7">
			<input type="text" class="form-control" name="stok_akhir" value="{{ isset($logStok) ? $logStok->stok_akhir : '' }}" id="stok_akhir" readonly>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">HNA</label>
			<div class="col-md-7">
			<input type="text" class="form-control" name="hna" 
			value="{{ isset($logStok) ? number_format($logStok->hna,2,",",".") : '' }}" id="hna">
		</div>
	</div>

	{!! App\Console\Commands\Generator\Form::input('hpp','text')->model($logStok)->show() !!}
				
	<div class="col-md-12 float-right">
		<div class="text-right">
			<button class="btn btn-primary" id="simpan">Simpan</button>
		</div>
	</div>
		
		</div>
	{{ Form::close() }}
</div>


<script type="text/javascript">

$('#hna').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).on('keyup change', "#selisih", "#stok_awal",  function() {
    var val1 = $("#stok_awal").val()
    var val2 = $("#selisih").val()
    var result = parseInt(val1) + parseInt(val2)
    $("#stok_akhir").val(result || 0)
}); // menghitung sisa tagihan

$(document).ready(function(){

$('#barcode').change(function(){
    var barcode = $(this).val();
    var url = '{{ route("isibarcode", ":barcode") }}';
    url = url.replace(':barcode', barcode);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    success: function(response){
      	if(response != null){
        	$('#barang').val(response.barang);
			$('#id_stok').val(response.id_stok);
        	$('#stok_awal').val(response.jumlah_stok);
			$('#id_packing_barang').val(response.id_packing_barang);
      	}}
  	});
});


	$('#log-stok-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	waktu : { validators: {
				        notEmpty: {
				          message: 'Kolom waktu tidak boleh kosong'
							}
						}
					},stok_awal : { validators: {
				        notEmpty: {
				          message: 'Kolom stok_awal tidak boleh kosong'
							}
						}
					},
					
					selisih : { validators: {
				        notEmpty: {
				          message: 'Kolom selisih tidak boleh kosong'
							}
						}
					},
					
					hna : { validators: {
				        notEmpty: {
				          message: 'Kolom HNA tidak boleh kosong'
							}
						}
					},

					hpp : { validators: {
				        notEmpty: {
				          message: 'Kolom HPP tidak boleh kosong'
							}
						}
					},


					stok_akhir : { validators: {
				        notEmpty: {
				          message: 'Kolom stok_akhir tidak boleh kosong'
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
