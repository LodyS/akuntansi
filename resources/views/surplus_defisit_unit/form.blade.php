<div class="modal-dialog modal-simple">

		{{ Form::model($surplusDefisitUnit,array('route' => array((!$surplusDefisitUnit->exists) ? 'surplus-defisit-unit.store':'surplus-defisit-unit.update',$surplusDefisitUnit->pk()),
	        'class'=>'modal-content','id'=>'surplus-defisit-unit-form','method'=>(!$surplusDefisitUnit->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($surplusDefisitUnit->exists?'Edit':'Tambah').' Surplus Defisit Unit' }}</h4>
    </div>
    <div class="modal-body">
    <div class="form-group row">

            <label class="col-md-3">Surplus Defisit</label>
				<div class="col-md-7">
				<select name="id_surplus_defisit_detail" id="id_surplus_defisit_detail" class="form-control select">
					<option value="">Pilih</option>
                    @foreach ($surplusDefisitDetail as $p)
					<option value="{{ $p->id}}" {{ ($surplusDefisitUnit->id_surplus_defisit_detail==$p->id)?'selected':''}}>{{ $p->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>

        <div class="form-group row">
			<label class="col-md-3">Unit</label>
				<div class="col-md-7">
				<select name="id_unit" id="id_unit" class="form-control select">
					<option value="">Pilih</option>
                    @foreach ($unit as $p)
					<option value="{{ $p->id}}" {{ ($surplusDefisitUnit->id_unit==$p->id)?'selected':''}}>{{ $p->nama }}</option>
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
	$('#surplus-defisit-unit-form').formValidation({
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

					var unitEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/unit") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#unit").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: unitEngine.ttAdapter(),
									name: "unit",
									displayKey: "unit",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{unit}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>unit tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_unit").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});


});
</script>
