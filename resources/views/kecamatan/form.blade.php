<div class="modal-dialog modal-simple">

		{{ Form::model($kecamatan,array('route' => array((!$kecamatan->exists) ? 'kecamatan.store':'kecamatan.update',$kecamatan->pk()),
	        'class'=>'modal-content','id'=>'kecamatan-form','method'=>(!$kecamatan->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($kecamatan->exists?'Edit':'Tambah').' Kecamatan' }}</h4>
    </div>
    <div class="modal-body">
				<div class="form-group row">
					<label class="col-md-3">Kode Kecamatan</label>
					<div class="col-md-7">
					<input name="kode" id="kode" value="{{($kecamatan->exists?!empty($kecamatan->kode)?$kecamatan->kode:'':'')}}" class="form-control" type="text">
						<span class="help-block" id="kode_a"></span>
					</div>
				</div>
				{{-- <div class="modal-body"> --}}
				<div class="form-group row">
						<label class="col-md-3">Kecamatan</label>
						<div class="col-md-7">
						<input name="kecamatan" id="kecamatan" value="{{($kecamatan->exists?!empty($kecamatan->kecamatan)?$kecamatan->kecamatan:'':'')}}" class="form-control" type="text">
							<span class="help-block" id="kecamatan_a"></span>
				      </div>
				</div>
				<div class="form-group row">
					<label class="col-md-3">Kabupaten</label>
					<div class="col-md-7">
							<div class="input-search clearfix">
					<input name="kabupaten" id="kabupaten" value="{{($kabupaten->exists?!empty($kabupaten->kabupaten)?$kabupaten->kabupaten:'':'')}}" class="form-control" type="text">
					<button type="submit" class="input-search-btn">
							<i class="icon md-search" aria-hidden="true"></i>
						</button>
					</div>
					{!! App\Console\Commands\Generator\Form::input('id_kabupaten','hidden')->model($kecamatan)->showHidden(['label'=>'Kabupaten']) !!}
						<span class="help-block" id="kabupaten_a"></span>
					</div>
				</div>
{{-- {!! App\Console\Commands\Generator\Form::input('kode','text')->model($kecamatan)->show(['label'=>'ID Kecamatan']) !!} --}}
{{-- {!! App\Console\Commands\Generator\Form::input('kecamatan','text')->model($kecamatan)->show(['label'=>'Kecamatan']) !!} --}}

{{-- {!! App\Console\Commands\Generator\Form::autocomplete('kabupaten',array('value'=>$kecamatan->exists?(isset($kecamatan->kabupaten)?$kecamatan->kabupaten->kabupaten:null):null))->model(null)->show() !!} --}}


{{-- {!! App\Console\Commands\Generator\Form::checkbox('flag_aktif','Flag Aktif',array('label'=>'','value'=>'Y'),$kecamatan->exists?$kecamatan->flag_aktif:'N') !!} --}}
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
// 	$('#kecamatan-form').formValidation({
// 	  framework: "bootstrap4",
// 	  button: {
// 	    selector: "#simpan",
// 	    disabled: "disabled"
// 	  },
// 	  icon: null,
// 	  fields: {
// 	kode : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kode Kecamatan tidak boleh kosong'
// 							}
// 						}
// 					},
// 					kecamatan : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kecamatan tidak boleh kosong'
// 							}
// 						}
// 					},

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
	$("#kecamatan-form").find("#kode").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kode_a').fadeIn();
			$("#kode_a").html("* Kode Kecamatan Wajib Diisi");
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
  $("#kecamatan-form").find("#kecamatan").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kecamatan_a').fadeIn();
			$("#kecamatan_a").html("* Kecamatan Wajib Diisi");
			$("#kecamatan_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$(this).css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid",
					 });
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kecamatan_a').fadeOut();
			$(this).css("border",'');
		}  
  	// $("input").css("background-color", "pink");
  });

  $("#kecamatan-form").find("#kabupaten").keyup(function(){
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
}

function submit()
{
	$('#kecamatan-form').submit('#simpan',function(e){
		if($("#kecamatan-form").find('#kode').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kode_a').fadeIn();
			$("#kode_a").html("* Kode Kecamatan Wajib Diisi");
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
			$('#kode').css("border",'');
		} 
		if($("#kecamatan-form").find('#kecamatan').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kecamatan_a').fadeIn();
			$("#kecamatan_a").html("* Kecamatan Wajib Diisi");
			$("#kecamatan_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$('#kecamatan').css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kecamatan_a').fadeOut();
			$('#kecamatan').css("border",'');
		} 
		if($("#kecamatan-form").find('#kabupaten').val()==''){
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
	});
	
}
	
					var kabupatenEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/kabupaten") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#kabupaten").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: kabupatenEngine.ttAdapter(),
									name: "kabupaten",
									displayKey: "kabupaten",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{kabupaten}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>kabupaten tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_kabupaten").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					

});
</script>
