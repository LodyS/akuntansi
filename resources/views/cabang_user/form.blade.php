<div class="modal-dialog modal-simple">

		{{ Form::model($cabangUser,array('route' => array((!$cabangUser->exists) ? 'cabang-user.store':'cabang-user.update',$cabangUser->pk()),
	        'class'=>'modal-content','id'=>'cabang-user-form','method'=>(!$cabangUser->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($cabangUser->exists?'Edit':'Tambah').' Cabang User' }}</h4>
    </div>
    <div class="modal-body">

	<div class="form-group row">
				<label class="col-md-3">User</label>
					<div class="col-md-7">
						<select id="id_user" name="id_user" class="form-control">
                            <option value="--">Pilih User</option>
							  	@foreach ($user as $users)
							  		<option value="{{ $users->id}} ">{{ $users->name }}</option>
							@endforeach
                     </select>			
					</div>
				</div>


			<div class="form-group row">
				<label class="col-md-3">Perusahaan</label>
					<div class="col-md-7">
					<select id="id_perusahaan" name="id_perusahaan" class="form-control">
                        <option value="--">Pilih Perusahaan</option>
							@foreach ($Perusahaan as $perusahaan)
							  	<option value="{{ $perusahaan->id}} ">{{ $perusahaan->nama_badan_usaha }}</option>
							  @endforeach
                     </select>			
					</div>
				</div>
																      
					{!! App\Console\Commands\Generator\Form::input('nama','text')->model($cabangUser)->show() !!}
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
	$('#cabang-user-form').formValidation({
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
					},id_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom id_perusahaan tidak boleh kosong'
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
					

});
</script>
