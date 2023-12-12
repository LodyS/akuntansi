<div class="modal-dialog modal-simple">

		{{ Form::model($transaksi,array('route' => array((!$transaksi->exists) ? 'transaksi.store':'transaksi.update',$transaksi->pk()),
	        'class'=>'modal-content','id'=>'transaksi-form','method'=>(!$transaksi->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($transaksi->exists?'Edit':'Tambah').' Saldo Awal' }}</h4>
    </div>
    <div class="modal-body">
	<div class="form-group row">
					<label class="col-md-3">User</label>
						<div class="col-md-7">
							<input type="text" name="id_user"  value="{{ Auth::user()->id }}" class="form-control" disabled >

						<span class="help-block" id="kode_a"></span>
					</div></div>

					<div class="form-group row">
					<label class="col-md-3">ID Perkiraan</label>
					<div class="col-md-7">
				<select class="form-control" name="id_perkiraan">
				       @foreach ($kira as $f)
						<option value="{{ $f->id }}">{{ $f->nama }}</option>
					  @endforeach
				</select>
						<span class="help-block" id="kode_a"></span>
					</div>
				</div>
				    {!! App\Console\Commands\Generator\Form::input('tanggal','date')->model($transaksi)->show() !!}
					{!! App\Console\Commands\Generator\Form::input('keterangan','text')->model($transaksi)->show() !!}
					{!! App\Console\Commands\Generator\Form::input('debet','text')->model($transaksi)->show() !!}
					{!! App\Console\Commands\Generator\Form::input('kredit','text')->model($transaksi)->show() !!}
					{!! App\Console\Commands\Generator\Form::input('id_periode','text')->model($transaksi)->show() !!}

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
	$('#transaksi-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	id_user : { validators: {
				        notEmpty: {
				          message: 'Kolom id_user tidak boleh kosong'
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

					var userEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/user") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#user").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: userEngine.ttAdapter(),
									name: "user",
									displayKey: "user",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{user}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>user tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_user").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});

					var perkiraanEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/perkiraan") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#perkiraan").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: perkiraanEngine.ttAdapter(),
									name: "perkiraan",
									displayKey: "perkiraan",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{perkiraan}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>perkiraan tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_perkiraan").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});

					var periodeEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/periode") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#periode").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: periodeEngine.ttAdapter(),
									name: "periode",
									displayKey: "periode",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{periode}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>periode tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_periode").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});


});
</script>
