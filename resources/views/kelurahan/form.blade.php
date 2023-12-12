<div class="modal-dialog modal-simple">

		{{ Form::model($kelurahan,array('route' => array((!$kelurahan->exists) ? 'kelurahan.store':'kelurahan.update',$kelurahan->pk()),
	        'class'=>'modal-content','id'=>'kelurahan-form','method'=>(!$kelurahan->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($kelurahan->exists?'Edit':'Tambah').' Kelurahan' }}</h4>
    </div>
    <div class="modal-body">
			<div class="form-group row">
					<label class="col-md-3">Kode Kelurahan</label>
					<div class="col-md-7">
					<input name="kode" id="kode" value="{{($kelurahan->exists?!empty($kelurahan->kode)?$kelurahan->kode:'':'')}}" class="form-control" type="text">
						<span class="help-block" id="kode_a"></span>
					</div>
				</div>
				<div class="form-group row">
						<label class="col-md-3">Kelurahan</label>
						<div class="col-md-7">
						<input name="kelurahan" id="kelurahan" value="{{($kelurahan->exists?!empty($kelurahan->kelurahan)?$kelurahan->kelurahan:'':'')}}" class="form-control" type="text">
							<span class="help-block" id="kelurahan_a"></span>
						</div>
					</div>
						<div class="form-group row">
								<label class="col-md-3">Kecamatan</label>
								<div class="col-md-7">
									<div class="input-search clearfix">
								<input name="kecamatan" id="kecamatan" value="{{($kecamatan->exists?!empty($kecamatan->kecamatan)?$kecamatan->kecamatan:'':'')}}" class="form-control" type="text">
								<button type="submit" class="input-search-btn">
										<i class="icon md-search" aria-hidden="true"></i>
									</button>
								</div>
								{!! App\Console\Commands\Generator\Form::input('id_kecamatan','hidden')->model($kelurahan)->showHidden() !!}
									<span class="help-block" id="kecamatan_a"></span>
								</div>
							</div>
							<div class="form-group row">
									<label class="col-md-3">Kode Pos</label>
									<div class="col-md-7">
									<input name="kodepos" id="kodepos" value="{{($kelurahan->exists?!empty($kelurahan->kodepos)?$kelurahan->kodepos:'':'')}}" class="form-control" type="text">
										<span class="help-block" id="kodepos_a"></span>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-3">Latitude</label>
									<div class="col-md-7">
									<input name="latitude" id="latitude" value="{{($kelurahan->exists?!empty($kelurahan->latitude)?$kelurahan->latitude:'':'')}}" class="form-control" type="text">
									
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-3">Longitude</label>
									<div class="col-md-7">
									<input name="longitude" id="longitude" value="{{($kelurahan->exists?!empty($kelurahan->longitude)?$kelurahan->longitude:'':'')}}" class="form-control" type="text">
									
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-3">Kode BPS</label>
									<div class="col-md-7">
									<input name="kode_bps" id="kode_bps" value="{{($kelurahan->exists?!empty($kelurahan->kode_bps)?$kelurahan->kode_bps:'':'')}}" class="form-control" type="text">
									
									</div>
								</div>
																				        {{-- {!! App\Console\Commands\Generator\Form::input('kode','text')->model($kelurahan)->show(['label'=>'Kode Kelurahan']) !!} --}}
																        {{-- {!! App\Console\Commands\Generator\Form::input('kelurahan','text')->model($kelurahan)->show(['label'=>'Kelurahan']) !!} --}}
																        
{{-- {!! App\Console\Commands\Generator\Form::autocomplete('kecamatan',array('value'=>$kelurahan->exists?(isset($kelurahan->kecamatan)?$kelurahan->kecamatan->kecamatan:null):null))->model(null)->show() !!} --}}
																		{{-- {!! App\Console\Commands\Generator\Form::input('kodepos','text')->model($kelurahan)->show(['label'=>'Kodepos']) !!} --}}
																		{{-- {!! App\Console\Commands\Generator\Form::checkbox('flag_aktif','Flag Aktif',array('label'=>'','value'=>'Y'),$kelurahan->exists?$kelurahan->flag_aktif:'N') !!} --}}
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
// 	$('#kelurahan-form').formValidation({
// 	  framework: "bootstrap4",
// 	  button: {
// 	    selector: "#simpan",
// 	    disabled: "disabled"
// 	  },
// 	  icon: null,
// 	  fields: {
// 	kode : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kode Kelurahan tidak boleh kosong'
// 							}
// 						}
// 					},
// 					kelurahan : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kelurahan tidak boleh kosong'
// 							}
// 						}
// 					},
// 					// kecamatan : { validators: {
// 				    //     notEmpty: {
// 				    //       message: 'Kolom Kecamatan tidak boleh kosong'
// 					// 		}
// 					// 	}
// 					// },
// 					kodepos : { validators: {
// 				        notEmpty: {
// 				          message: 'Kolom Kodepos tidak boleh kosong'
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
	$("#kelurahan-form").find("#kode").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kode_a').fadeIn();
			$("#kode_a").html("* Kode Kelurahan Wajib Diisi");
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
  $("#kelurahan-form").find("#kelurahan").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kelurahan_a').fadeIn();
			$("#kelurahan_a").html("* Kelurahan Wajib Diisi");
			$("#kelurahan_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$(this).css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid",
					 });
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kelurahan_a').fadeOut();
			$(this).css("border",'');
		}  
  	// $("input").css("background-color", "pink");
  });

  $("#kelurahan-form").find("#kecamatan").keyup(function(){
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
  $("#kelurahan-form").find("#kodepos").keyup(function(){
		if (!$.trim($(this).val())) {
			console.log('alert1');
			// alert('Country Name is required!');
			$('#kodepos_a').fadeIn();
			$("#kodepos_a").html("* Kode Pos Wajib Diisi");
			$("#kodepos_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$(this).css({"border-color": "red", 
             "border-width":"1px", 
             "border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			if($.isNumeric($(this).val()))
			{
				if($(this).val().length >=1 && $(this).val().length<=5)
				{
					$('#kodepos_a').fadeOut();
					$(this).css("border",'');
				}
				else{
					$('#kodepos_a').fadeIn();
					$("#kodepos_a").html("* Kode Pos Minimal 1 Maksimal 5 Karakter");
					$("#kodepos_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
					$(this).css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
				}
				
			}
			else{
				$('#kodepos_a').fadeIn();
				$("#kodepos_a").html("* Format Kode Pos Harus Angka");
				$("#kodepos_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
				$(this).css({"border-color": "red", 
             	"border-width":"1px", 
             	"border-style":"solid"});
			}
			
		} 
  	// $("input").css("background-color", "pink");
  });
}

function submit()
{
	$('#kelurahan-form').submit('#simpan',function(e){
		if($("#kelurahan-form").find('#kode').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kode_a').fadeIn();
			$("#kode_a").html("* Kode Kelurahan Wajib Diisi");
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
		if($("#kelurahan-form").find('#kelurahan').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kelurahan_a').fadeIn();
			$("#kelurahan_a").html("* Kelurahan Wajib Diisi");
			$("#kelurahan_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$('#kelurahan').css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kelurahan_a').fadeOut();
			$('#kelurahan').css("border",'');
		} 
		if($("#kelurahan-form").find('#kecamatan').val()==''){
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
		if($("#kelurahan-form").find('#kodepos').val()==''){
			e.preventDefault();
			console.log('alert');
			$('#kodepos_a').fadeIn();
			$("#kodepos_a").html("* Kode Pos Wajib Diisi");
			$("#kodepos_a").css({"color":"red",
			"font-size":"11px",
			"font-family":"arial"});
			$('#kodepos').css({"border-color": "red", 
             		"border-width":"1px", 
             		"border-style":"solid"});
			// $('#simpan').attr('disabled',true);
			// return false;
		}else{
			$('#kodepos_a').fadeOut();
			$('#kodepos').css("border",'');
		} 
	});
	
}
	
					var kecamatanEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/kecamatan") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#kecamatan").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: kecamatanEngine.ttAdapter(),
									name: "kecamatan",
									displayKey: "kecamatan",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{kecamatan}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>kecamatan tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_kecamatan").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					

});
</script>
