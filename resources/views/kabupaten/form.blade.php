
<div class="modal-dialog modal-simple">

		{{ Form::model($kabupaten,array('route' => array((!$kabupaten->exists) ? 'kabupaten.store':'kabupaten.update',$kabupaten->pk()),
	        'class'=>'modal-content','id'=>'kabupaten-form','method'=>(!$kabupaten->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($kabupaten->exists?'Edit':'Tambah').' Kabupaten' }}</h4>
    </div>
    <div class="modal-body">
			{{-- <div class="form-body"> --}}
					<div class="form-group row">
						<label class="col-md-3">Kode Kabupaten</label>
						<div class="col-md-7">
						<input name="kode" id="kode" value="{{($kabupaten->exists?!empty($kabupaten->kode)?$kabupaten->kode:'':'')}}" class="form-control" type="text">
							<span class="help-block" id="kode_a"></span>
						</div>
					</div>
					<div class="form-group row">
							<label class="col-md-3">Kabupaten</label>
							<div class="col-md-7">
							<input name="kabupaten" id="kabupaten" value="{{($kabupaten->exists?!empty($kabupaten->kabupaten)?$kabupaten->kabupaten:'':'')}}" class="form-control" type="text">
								<span class="help-block" id="kabupaten_a"></span>
							</div>
						</div>
						<div class="form-group row">
								<label class="col-md-3">Provinsi</label>
								<div class="col-md-7">
										<div class="input-search clearfix">
								<input name="provinsi" id="provinsi" value="{{($provinsi->exists?!empty($provinsi->provinsi)?$provinsi->provinsi:'':'')}}" class="form-control" type="text">
								<button type="submit" class="input-search-btn">
										<i class="icon md-search" aria-hidden="true"></i>
									</button>
								</div>
								{!! App\Console\Commands\Generator\Form::input('id_provinsi','hidden')->model($kabupaten)->showHidden() !!}
									<span class="help-block" id="provinsi_a"></span>
								</div>
							</div>
				{{-- </div> --}}
																				        {{-- {!! App\Console\Commands\Generator\Form::input('kode','text')->model($kabupaten)->show(['label'=>'Kode Kabupaten']) !!} --}}
																        {{-- {!! App\Console\Commands\Generator\Form::input('kabupaten','text')->model($kabupaten)->show(['label'=>'Kabupaten']) !!} --}}
																        
{{-- {!! App\Console\Commands\Generator\Form::autocomplete('provinsi',array('value'=>$kabupaten->exists?(isset($kabupaten->provinsi)?$kabupaten->provinsi->provinsi:null):null))->model(null)->show() !!} --}}
{{-- {!! App\Console\Commands\Generator\Form::checkbox('flag_aktif','Flag Aktif',array('label'=>'','value'=>'Y'),$kabupaten->exists?$kabupaten->flag_aktif:'N') !!} --}}
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
	cek();
	submit();
// 	$('#kabupaten-form').formValidation({
// 	  framework: "bootstrap4",
// 	  button: {
// 	    selector: "#simpan",
// 	    disabled: "disabled"
// 	  },
// 	  icon: null,
// 	  fields: {
// 	kode : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kode Kabupaten tidak boleh kosong'
// 							}
// 						}
// 					},
// 					kabupaten : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kabupaten tidak boleh kosong'
// 							}
// 						}
// 					}
// },
// err: {
// 	clazz: 'invalid-feedback'
// },
// control: {
// 	// The CSS class for valid control
// 	valid: 'is-valid',

// 	// The CSS class for invalid control
// 	invalid: 'is-invalid'
// },
// row: {
// 	invalid: 'has-danger'
// }
// });

function cek()
{
	$("#kabupaten-form").find("#kode").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kode_a').fadeIn();
			$("#kode_a").html("* Kode Kabupaten Wajib Diisi");
			$("#kode_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$(this).css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid",
					 });
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kode_a').fadeOut();
			$(this).css("border",'');
		}  
  	// $("input").css("background-color", "pink");
  });
  $("#kabupaten-form").find("#kabupaten").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kabupaten_a').fadeIn();
			$("#kabupaten_a").html("* Kabupaten Wajib Diisi");
			$("#kabupaten_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$(this).css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid",
					 });
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kabupaten_a').fadeOut();
			$(this).css("border",'');
		}  
  	// $("input").css("background-color", "pink");
  });

  $("#kabupaten-form").find("#provinsi").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#provinsi_a').fadeIn();
			$("#provinsi_a").html("* Provinsi Wajib Diisi");
			$("#provinsi_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$(this).css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid",
					 });
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#provinsi_a').fadeOut();
			$(this).css("border",'');
		}  
  	// $("input").css("background-color", "pink");
  });
}

function submit()
{
	$('#kabupaten-form').submit('#simpan',function(e){
		if($("#kabupaten-form").find('#kode').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kode_a').fadeIn();
			$("#kode_a").html("* Kode Kabupaten Wajib Diisi");
			$("#kode_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$('#kode').css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kode_a').fadeOut();
			$('#kd_kabupaten').css("border",'');
		} 
		if($("#kabupaten-form").find('#kabupaten').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kabupaten_a').fadeIn();
			$("#kabupaten_a").html("* Kabupaten Wajib Diisi");
			$("#kabupaten_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$('#kabupaten').css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kabupaten_a').fadeOut();
			$('#kabupaten').css("border",'');
		} 
		if($("#kabupaten-form").find('#provinsi').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#provinsi_a').fadeIn();
			$("#provinsi_a").html("* Provinsi Wajib Diisi");
			$("#provinsi_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$('#provinsi').css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#provinsi_a').fadeOut();
			$('#provinsi').css("border",'');
		} 
	});
	
}
	
					var provinsiEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/provinsi") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#provinsi").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: provinsiEngine.ttAdapter(),
									name: "provinsi",
									displayKey: "provinsi",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{provinsi}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>provinsi tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_provinsi").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					

});
</script>
