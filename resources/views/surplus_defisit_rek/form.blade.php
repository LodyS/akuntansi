<div class="modal-dialog modal-simple">

		{{ Form::model($surplusDefisitRek,array('route' => array((!$surplusDefisitRek->exists) ? 'surplus-defisit-rek.store':'surplus-defisit-rek.update',$surplusDefisitRek->pk()),
	        'class'=>'modal-content','id'=>'surplus-defisit-rek-form','method'=>(!$surplusDefisitRek->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($surplusDefisitRek->exists?'Edit':'Tambah').' Surplus Defisit Rek' }}</h4>
    </div>
    <div class="modal-body">

    <div class="form-group row">
    <label class="col-md-3">Surplus Defisit</label>


				<div class="col-md-7">
				<select name="id_surplus_defisit_detail" id="id_surplus_defisit_detail" class="form-control select">
					<option value="">Pilih</option>
                    @foreach ($surplusDefisitDetail as $p)
					<option value="{{ $p->id}}" {{ ($surplusDefisitRek->id_surplus_defisit_detail==$p->id)?'selected':''}}>{{ $p->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>

        <div class="form-group row">
			<label class="col-md-3">Rekening</label>
				<div class="col-md-7">
				<select name="id_perkiraan" id="id_perkiraan" class="form-control select">
					<option value="">Pilih</option>
                    @foreach ($perkiraan as $p)
					<option value="{{ $p->id}}" {{ ($surplusDefisitRek->id_perkiraan==$p->id)?'selected':''}}>{{ $p->nama }}</option>
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

$(".select").select2({
        width : '100%'
    });

$(document).ready(function(){
	$('#surplus-defisit-rek-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {

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

					var surplus_defisit_detailEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/surplus_defisit_detail") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#surplus_defisit_detail").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: surplus_defisit_detailEngine.ttAdapter(),
									name: "surplus_defisit_detail",
									displayKey: "surplus_defisit_detail",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{surplus_defisit_detail}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>surplus_defisit_detail tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_surplus_defisit_detail").val(datum.id);
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


});
</script>
