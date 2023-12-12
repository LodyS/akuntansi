<div class="modal-dialog modal-simple">

		{{ Form::model($permissionRole,array('route' => array((!$permissionRole->exists) ? 'permission-role.store':'permission-role.update',$permissionRole->pk()),
	        'class'=>'modal-content','id'=>'permission-role-form','method'=>(!$permissionRole->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($permissionRole->exists?'Edit':'Tambah').' Permission Role' }}</h4>
    </div>
    <div class="modal-body">
												        {!! App\Console\Commands\Generator\Form::input('permission_id','hidden')->model($permissionRole)->showHidden() !!}
{!! App\Console\Commands\Generator\Form::autocomplete('permission',array('value'=>$permissionRole->exists?(isset($permissionRole->permission)?$permissionRole->permission->permission:null):null))->model(null)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('role_id','hidden')->model($permissionRole)->showHidden() !!}
{!! App\Console\Commands\Generator\Form::autocomplete('role',array('value'=>$permissionRole->exists?(isset($permissionRole->role)?$permissionRole->role->role:null):null))->model(null)->show() !!}
												<div class="col-md-12 float-right">
					<div class="text-right">
						<button class="btn btn-primary" id="simpan">Simpan</button>
					</div>
				</div>
		</div>

	    {{ Form::close() }}
</div>

<script src="{{ asset('admin_remark_base/') }}/assets/js/ModalShow.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#permission-role-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	permission_id : { validators: {
				        notEmpty: {
				          message: 'Kolom permission_id tidak boleh kosong'
							}
						}
					},role_id : { validators: {
				        notEmpty: {
				          message: 'Kolom role_id tidak boleh kosong'
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
	
					var permissionEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/permission") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#permission").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: permissionEngine.ttAdapter(),
									name: "permission",
									displayKey: "permission",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{permission}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>permission tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_permission").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					
					var roleEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/role") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#role").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: roleEngine.ttAdapter(),
									name: "role",
									displayKey: "role",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{role}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>role tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_role").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					

});
</script>
