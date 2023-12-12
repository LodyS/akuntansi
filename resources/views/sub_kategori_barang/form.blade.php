<div class="modal-dialog modal-simple">

		{{ Form::model($subKategoriBarang,array('route' => array((!$subKategoriBarang->exists) ? 'sub-kategori-barang.store':'sub-kategori-barang.update',$subKategoriBarang->pk()),
	        'class'=>'modal-content','id'=>'sub-kategori-barang-form','method'=>(!$subKategoriBarang->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($subKategoriBarang->exists?'Edit':'Tambah').' Sub Kategori Barang' }}</h4>
    </div>
    <div class="modal-body">
																	
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($subKategoriBarang)->show() !!}
		
		<div class="form-group row">
			<label class="col-md-3">Kategori Barang</label>
				<div class="col-md-7">
				<select name="id_kategori_barang" class="form-control select" id="id_kategori_barang">
					<option value="--">Pilih Kategori Barang</option>
                    @foreach ($kategoriBarang as $barang)
                    <option value="{{ $barang->id}}" {{($barang->id == $subKategoriBarang->id_kategori_barang)?'selected':''}}>{{ $barang->nama}}</option>
                    @endforeach
                </select>		
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Permintaan Penjualan</label>
				<div class="col-md-7">
				<select name="permintaan_penjualan" class="form-control" id="permintaan_penjualan">
					<option value="--">Pilih</option>
                 
                    <option value="Bisa" {{($subKategoriBarang->permintaan_penjualan == "Bisa")?'selected':''}}>Bisa</option>
					<option value="Tidak" {{($subKategoriBarang->permintaan_penjualan == "Tidak")?'selected':''}}>Tidak</option>
                </select>		
			</div>
		</div>		
			
				<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">	

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
    dropdownParent: $("#sub-kategori-barang-form"),
	width: '100%'
});

$(document).ready(function(){
	$('#sub-kategori-barang-form').formValidation({
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
					},permintaan_penjualan : { validators: {
				        notEmpty: {
				          message: 'Kolom permintaan_penjualan tidak boleh kosong'
							}
						}
					},user_input : { validators: {
				        notEmpty: {
				          message: 'Kolom user_input tidak boleh kosong'
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
	
					var kategori_barangEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/kategori_barang") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#kategori_barang").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: kategori_barangEngine.ttAdapter(),
									name: "kategori_barang",
									displayKey: "kategori_barang",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{kategori_barang}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>kategori_barang tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_kategori_barang").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					

});
</script>
