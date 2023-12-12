@extends('layouts.app')

@section('content')
<style>
	.kv-avatar .krajee-default.file-preview-frame,.kv-avatar .krajee-default.file-preview-frame:hover {
		margin: 0;
		padding: 0;
		border: none;
		box-shadow: none;
		text-align: center;
	}
	.kv-avatar {
		display: inline-block;
	}
	.kv-avatar .file-input {
		display: table-cell;
		width: 213px;
	}
	.kv-reqd {
		color: red;
		font-family: monospace;
		font-weight: normal;
	}
	.kv-file-remove {
		display:none;
	}
	.ajax-loader{
		position:fixed;
		top:0px;
		right:0px;
		width:100%;
		height:auto;
		background-color:#A9A9A9;
		background-repeat:no-repeat;
		background-position:center;
		z-index:10000000;
		opacity: 0.4;
		filter: alpha(opacity=40); /* For IE8 and earlier */
	}

	#progress_upload{
		position:fixed;
		top:0px;
		right:0px;
		width:100%;
		height:auto;
		background-color:#757575;
		background-repeat:no-repeat;
		background-position:center;
		z-index:10000000;
		opacity: 0.4;
		filter: alpha(opacity=40); /* For IE8 and earlier */
	}
</style>
<div class="page-content">
	<div class="panel">
		<div id="progress_upload" class="text-center" style="display:none">
			<div class="progress progress-lg">
				<div class="progress-bar progress-bar-primary" style="width: 0%;" role="progressbar"><span id="percent">0%</span></div>
			</div>
			<div id="status" style="font-size:8pt;font-family: sans-serif;color: white"></div>
		</div>

		<div class="ajax-loader text-center" style="display:none">
			<div class="progress">
				<div class="progress-bar progress-bar-striped active" aria-valuenow="100" aria-valuemin="1000"
				aria-valuemax="100" style="width: 100%;" id="loader" role="progressbar">
			</div>
		</div>
		<div id="" style="font-size:8pt;font-family: sans-serif;color: white">Loading...Please Wait</div>
	</div>
		<div class="panel-body container-fluid">
			{{ Form::model(null,array('route' => 'config-id.store',
			'class'=>'','id'=>'form','method'=>'POST','files'=>true)) }} 
			  <input type="text" style="display: none" id="address" value="">
			  <input type="text" class="form-control" style="display: none" value="" name="mode" id="mode"> 
			<div class="row row-lg">
				<div class="col-lg-12">
					<!-- Example Tabs Left -->
					<div class="example-wrap">
						<div class="nav-tabs-vertical" data-plugin="tabs">
							<ul class="nav nav-tabs mr-25" style="font-weight: 500" role="tablist">
								@if(\Auth::user()->can('read-config-id'))
								<li class="nav-item" role="presentation">
									<a class="nav-link" data-toggle="tab" href="#config"
									aria-controls="config" role="tab">Config IDS</a>
								</li>
								@endif
								@if(\Auth::user()->can('read-setting-menu'))
								<li class="nav-item" role="presentation">
									<a class="nav-link" data-toggle="tab" href="#perusahaan"
									aria-controls="perusahaan" role="tab">Setting Aplikasi</a>
								</li>
								@endif
								@if(\Auth::user()->can('read-user-settings-menu'))
								<li class="nav-item" role="presentation">
									<a class="nav-link" data-toggle="tab" href="#user_settings"
									aria-controls="user_settings" role="tab">Pengaturan Pengguna</a>
								</li>
								@endif
						{{-- 		<li class="nav-item" role="presentation">
									<a class="nav-link" data-toggle="tab" href="#exampleTabsLeftThree"
									aria-controls="exampleTabsLeftThree" role="tab">Css</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" data-toggle="tab" href="#exampleTabsLeftFour"
									aria-controls="exampleTabsLeftFour" role="tab">Javascript</a>
								</li> --}}
							</ul>
							<div class="tab-content py-15">
								<div class="tab-pane" id="config" role="tabpanel">
									@include('config_id.config')
								</div>
								<div class="tab-pane" id="perusahaan" role="tabpanel">
									@include('config_id.perusahaan')
								</div>
								<div class="tab-pane" id="user_settings" role="tabpanel">
									@include('config_id.user_settings')
								</div>
								{{-- <div class="tab-pane" id="exampleTabsLeftThree" role="tabpanel">
									Chrysippe rebus institutionem utrisque dixisset manus quippiam ignorare defatigatio
									doctiores, essent doctus ipsam tamquam complexiones corporisque,
									ars umbram sentiri venandi. Ipsam. Reprehenderit tantum
									debent sicine assumenda comprobavit, assumenda primos fuerit
									atomos amicorum inducitur quaedam miserum, potitur numquid
									effluere haeret ipsos consuetudine, munere putet fugiendis
									orationis quantumcumque. Perferendis attento saluti liberatione
									contra, constituam efficeret quaeso accusamus quieti petat
									rem nisi amicum.
								</div>
								<div class="tab-pane" id="exampleTabsLeftFour" role="tabpanel">
									Laudabilis. At artes audiebamus firmam discordiae potione albam ferantur, epicureum
									loquerer videretur formidinum utrisque simulent postremo,
									praesidia variari fecerit ferantur. Hominibus doctissimos
									multi, ferentur, certissimam medicorum bonum iucundius
									depravare facile. Degendae istius perfunctio quisquis ordinem
									ornatum, praeda atomi degendae animus. Mens eximiae placuit
									terrore, sollicitant efficeret audeam tantalo, vulgo laudantium
									evertitur spe meminerunt timentis populo, senserit inprobitas
									facilius referri consiliisque.
								</div> --}}
							</div>
						</div>
					</div>
					<!-- End Example Tabs Left -->
				</div>
			</div>
			<?php if(Auth::user()->roles[0]->name=='superadministrator')
			{
				$start='#config';
			}
			elseif(Auth::user()->roles[0]->name=='admin' || Auth::user()->roles[0]->name=='leader' || Auth::user()->roles[0]->name=='hrd')
			{
				$start='#perusahaan';
			}
			else
			{
				$start='#user_settings';
			}

			?>
			<div class="text-right">
				<button class="btn btn-primary" id="simpan" style="display: none">Simpan</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection
@push('js')
<script type="text/javascript">
	var nama='{{$start}}';
	$(document).ready(function(){
		// let nama=$('.nav-tabs-vertical li a.nav-link.active').attr('href');
		console.log(nama);
       $('#address').val(nama);
       $('[href="'+nama+'"]').click();

       if(nama=='#perusahaan')
       {
       	$('#mode').val('perusahaan');
       	$('#simpan').fadeIn();
       	perusahaan();
       }

        if(nama=='#user_settings')
       {
       	$('#mode').val('user_settings');
       	$('#simpan').fadeIn();
       	user_settings();
       }

       $('.icon').hover(function () {
       	$('.password').attr('type', 'text');
       	$('.icon').removeClass('md-eye-off');
       	$('.icon').addClass('md-eye');
       }, function () {
       	$('.password').attr('type', 'password');
       	$('.icon').removeClass('md-eye');
       	$('.icon').addClass('md-eye-off');
       });

        $('.nav-tabs-vertical li a.nav-link').click(function(){
            var a=$(this).attr('href');
            $('#address').val(a);
            if(a=='#config')
            {
            	$('#simpan').fadeOut();
            }
            else if(a=='#perusahaan')
            {
            	perusahaan();
            }

            else if(a=='#user_settings')
            {
            	user_settings();
            }
         })

var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
	'onclick="alert(\'Call your custom code here.\')">' +
	'<i class="glyphicon glyphicon-tag"></i>' +
	'</button>'; 

	$("#avatar-2").fileinput({
		overwriteInitial: true,
		maxFileSize: 1500,
		showClose: false,
		showCaption: false,
		showBrowse: false,
		browseOnZoneClick: true,
		autoOrientImage:false,
		removeLabel: '',
		removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
		removeTitle: 'Cancel or reset changes',
		elErrorContainer: '#kv-avatar-errors-2',
		msgErrorClass: 'alert alert-block alert-danger',
		defaultPreviewContent: '<img src="{{ asset('images/') }}/no_photos.png" alt="Your Avatar"><h6 class="text-muted">Click to select</h6>',
		layoutTemplates: {main2: '{preview} ' + ' {remove} {browse}'},
		allowedFileExtensions: ["jpg", "png"],
	});
	

		var foto='{{isset($foto_pegawai)?$foto_pegawai:''}}';
		console.log(foto);

		switch(foto)
		{
			case '':
			break;
			case null:
			break;
			default:
			$('.upload').fadeOut();
			$('.preview').fadeIn();
			preview(foto);
		}

		$('#edit_button').click(function(){
			$('.upload').fadeIn();
			$('.preview').fadeOut();
		})

		$('#hapus_button').click(function(){
			// $('.upload').fadeIn();
         	// $('.preview').fadeOut();
         	var con=confirm('Apakah anda yakin akan menghapus file ini?');
         	if(con==true)
         	{
         		$('.ajax-loader').fadeIn();
         		$('#loader').css('width','100%');
         		$.getJSON("{{url('config-id/delete-foto')}}", function(result){
         			// $.each(result, function(i, data){
         				console.log(result);
         				if(result.status==true)
         				{
         					toastr.success(result.msg,'');
         					$('#avatar-2').fileinput('reset');
         					// $('#avatar-3').fileinput('getPreview');
         					$('.file-preview-image').attr('src', '');
         					$('.upload').fadeIn();
         					$('.preview').fadeOut();
         					$('.ajax-loader').fadeOut();
         					preview('');	

         				}
         			// });
         		});	
         	}
         	else
         	{
         		return false;
         	}

         })

		$('#avatar-2').change(function() {
			var formData = new FormData();
			formData.append('select_file', this.files[0]);
			formData.append('_token', '{{csrf_token()}}');
			console.log(formData);
			$('.ajax-loader').fadeIn();
         	$('#loader').css('width','100%');
			$.ajax({
				url: '{{url('config-id/upload-foto')}}',
				type: 'POST',
				data: formData,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				contentType: false,
				processData: false,
				// xhr: function () {
				// 	var xhr = new window.XMLHttpRequest();
				// 	xhr.upload.addEventListener("progress",
				// 		uploadProgressHandler,
				// 		false
				// 		);
				// 	xhr.addEventListener("load", loadHandler, false);
				// 	xhr.addEventListener("error", errorHandler, false);
				// 	xhr.addEventListener("abort", abortHandler, false);

				// 	return xhr;
				// },
				success:function(data){
					console.log(data.fail);
					if(data.fail==false)
					{
						toastr.success('File Berhasil Diupload','');
						$('#avatar-2').fileinput('reset');
						$('.file-preview-image').attr('src', '{{ asset('images/') }}/profil/'+data.filename);
						$('.upload').fadeOut();
						$('.preview').fadeIn();
						preview(data.filename);
						$('.ajax-loader').fadeOut();	
					}
					else
					{
						toastr.error(data.errors,'');
					}

				},
				error:function (xhr, status, error){
					alert(xhr.responseText);
				},
			});
		});

	})


function preview(file)
{
	console.log(file);
	$("#avatar-3").fileinput({
		overwriteInitial: true,
		initialPreview: [
        // IMAGE DATA
        "{{ asset('images/') }}/profil/"+file+"",
        ],
    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    purifyHtml: true, // this by default purifies HTML data for preview
    showClose: false,
    showCaption: false,
    showBrowse: false,
    showRemove: false,
    browseOnZoneClick: false,
    fileActionSettings: {
    	showDrag: false,
    	showZoom: true,
    	showUpload: false,
    },
    pluginOptions:{
    	showRemove : false,
    }
});	
}


function hapus_logo()
{
	var con=confirm('Apakah anda yakin akan menghapus file ini?');
	if(con==true)
	{
		$.getJSON("{{url('/delete-logo')}}", function(result){
         			// $.each(result, function(i, data){
         				console.log(result);
         				if(result.status==true)
         				{
         					toastr.success(result.msg,'');
         					location.reload();
							return false;
         				}
         			// });
         		});	
	}
	else
	{
		return false;
	}
}

var validExt = ".jpg, .png, .jpeg";
function fileExtValidate(fdata) {
   var filePath = fdata.value;
   var getFileExt = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
   var pos = validExt.indexOf(getFileExt);
   if(pos < 0) {
      toastr.warning('Silahkan upload file ekstensi .png, .jpeg, dan .jpg','')
      // alert("This file is not allowed, please upload valid file.");
      fdata.value='';
      return false;
  } else {
      return true;
  }
}
//function for validate file size 
var maxSize = '1024';
function fileSizeValidate(fdata) 
{
   if (fdata.files && fdata.files[0]) 
   {
      var fsize = fdata.files[0].size/1024;
      if(fsize > maxSize) 
      {
         toastr.warning('Ukuran file maksimum melebihi 1024 KB. Ukuran file saat ini sebesar: '+fsize+' KB');
         fdata.value='';
         return false;
     } 
     else 
     {
        return true;
    }
  }
}

function uploadProgressHandler(event) {
	$("#loaded_n_total").html("Uploaded " + event.loaded + " bytes of " + event.total);
	var percent = (event.loaded / event.total) * 100;
	var progress = Math.round(percent);
	$("#percent").html(progress + "%");
	$(".progress-bar").css("width", progress + "%");
	$("#status").html(progress + "% uploaded... please wait");
}

function loadHandler(event) {
	$("#status").html('Upload Completed');
	setTimeout(function(){
		$('#progress_upload').fadeOut()
		$("#percent").html("0%");
		$(".progress-bar").css("width", "0%");
	}, 500);
}

function errorHandler(event) {
	$("#status").html("Upload Failed");
}

function abortHandler(event) {
	$("#status").html("Upload Aborted");
}

function perusahaan()
{
	$('#mode').val('perusahaan');
            	$('#simpan').fadeIn();

            	$("#logo").change(function () {
		      if(fileExtValidate(this)) { // file extension validation function
		         if(fileSizeValidate(this)) { // file size validation function
		         }   
		     }    
		 });

     $('#form').formValidation({
			framework: "bootstrap4",
			button: {
				selector: "#simpan",
				disabled: "disabled"
			},
			icon: null,
			fields: {
				nama_perusahaan : { validators: {
				        notEmpty: {
				          message: 'Kolom Nama Perusahaan tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Nama Perusahaan minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					alamat : { validators: {
				        notEmpty: {
				          message: 'Kolom Alamat tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Alamat minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					website1 : { validators: {
				        notEmpty: {
				          message: 'Kolom Website tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Website minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					email : { validators: {
				        notEmpty: {
				          message: 'Kolom Email tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Email minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					email1 : { validators: {
				        notEmpty: {
				          message: 'Kolom Prefiks tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Prefiks minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					toleransi_keterlambatan : { validators: {
				        notEmpty: {
				          message: 'Kolom Toleransi Keterlambatan tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Toleransi Keterlambatan minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					jumlah_pengurangan_gaji : { validators: {
				        notEmpty: {
				          message: 'Kolom Jumlah Pengurangan Gaji tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Jumlah Pengurangan Gaji minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},

					jumlah_pengurangan_jam : { validators: {
				        notEmpty: {
				          message: 'Kolom Jumlah Pengurangan Jam tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Jumlah Pengurangan Jam minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},
					status_shift : { validators: {
				        notEmpty: {
				          message: 'Kolom Kebijakan Shift Perusahaan tidak boleh kosong'
							},
						stringLength: {
                        	min: 1,
                        	max: 255,
                        	message: 'Kolom Kebijakan Shift Perusahaan minimal 1 dan maksimal 255 karakter',
                    		},
						}
					},
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
}

function user_settings()
{
	$('#mode').val('user_settings');
    $('#simpan').fadeIn();

    $('.notifikasi_email').hide();
    $('.notifikasi_telegram').hide();
    $('.notifikasi_whatsapp').hide();
    $('#show_notifikasi_email').click(function() {
    	$(this).data('clicked', true);
    	$('.notifikasi_email').toggle('slow');
    	$('.notifikasi_telegram').fadeOut('slow');
    	$('.notifikasi_whatsapp').fadeOut('slow');

    });
    $('#show_notifikasi_telegram').click(function() {
    	$(this).data('clicked', true);
    	$('.notifikasi_telegram').toggle('slow');
    	$('.notifikasi_email').fadeOut('slow');
    	$('.notifikasi_whatsapp').fadeOut('slow');

    });
    $('#show_notifikasi_whatsapp').click(function() {
    	$(this).data('clicked', true);
    	$('.notifikasi_whatsapp').toggle('slow');
    	$('.notifikasi_telegram').fadeOut('slow');
    	$('.notifikasi_email').fadeOut('slow');

    });

	$.validator.addMethod("noSpace", function(value, element) { 
		return value.indexOf(" ") < 0 && value != ""; 
	}, "Silahkan isi username tanpa menggunakan spasi");

	$('#mode').val('user_settings');
	// $('#form').validate({
	// 	rules : {
	// 		new_password : {
	// 			minlength : 5
	// 		},
	// 		re_password : {
	// 			minlength : 5,
	// 			equalTo : "#password"
	// 		}
	// 	}
	// })

	$('#form').validate({
            ignore: [],
             button: {
            selector: "#simpan",
            disabled: "disabled"
        },
       		debug: false,
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: (error, e) => {
                jQuery(e).parents('.row > .form-group').append(error);
            },
            highlight: e => {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                document.getElementById("simpan").style.opacity = "0.5";
            },
            success: e => {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
                document.getElementById("simpan").style.opacity = "1";
            },
            rules: {
            	'nama': {
                    required: true,
                    minlength: 1,
                    maxlength: 100,
                },
                'username': {
                	required: true,
                	noSpace: true,     
                	remote: {
                		url: "config-id/check-username",
                		type: "post",
                		data: {
                			"_token": "{{ csrf_token() }}",
                			username: function()
                          {
                              return $('#form :input[name="username"]').val();
                          }
                		},
                		beforeSend: function () {
                			$('.ajax-loader').fadeIn();
         					$('#loader').css('width','100%');
                		},
                		dataFilter: function (data) {
                			// console.log(data);
                			var json = JSON.parse(data);
                			$('.ajax-loader').fadeOut();
                			if (json.msg == "true") {
                				toastr.warning('Username sudah digunakan user lain!','Peringatan')
                				return "\"" + "Username sudah digunakan" + "\"";
                			} else {
                				toastr.success('Username tersedia','Pesan');
                				return 'true';
                			}
                		}
                	}
                }, 
                 'email_username': {
                	required: true,     
                	email:true,
                	remote: {
                		url: "config-id/check-email",
                		type: "post",
                		data: {
                			"_token": "{{ csrf_token() }}",
                			email_username: function()
                          {
                              return $('#form :input[name="email_username"]').val();
                          }
                		},
                		beforeSend: function () {
                			$('.ajax-loader').fadeIn();
         					$('#loader').css('width','100%');
                		},
                		dataFilter: function (data) {
                			// console.log(data);
                			var json = JSON.parse(data);
                			$('.ajax-loader').fadeOut();
                			if (json.msg == "true") {
                				toastr.warning('Email sudah digunakan user lain!','Peringatan')
                				return "\"" + "Email sudah digunakan" + "\"";
                			} else {
                				toastr.success('Email tersedia','Pesan');
                				return 'true';
                			}
                		}
                	}
                },
                'old_password': {
                	required:false,
                    // minlength: 8,
                    remote: {
                		url: "config-id/check-password",
                		type: "post",
                		data: {
                			"_token": "{{ csrf_token() }}",
                			password: function()
                          {
                              return $('#form :input[name="old_password"]').val();
                          }
                		},
                		beforeSend: function () {
                			$('.ajax-loader').fadeIn();
         					$('#loader').css('width','100%');
                		},
                		dataFilter: function (data) {
                			// console.log(data);
                			var json = JSON.parse(data);
                			$('.ajax-loader').fadeOut();
                			if (json.msg == "true") {
                				toastr.warning('Password lama yang anda isikan salah!','Peringatan')
                				return "\"" + "Password lama yang anda isikan salah" + "\"";
                			} else {
                				toastr.success('Password tersedia','Pesan');
                				return 'true';
                			}
                		}
                	}
                }, 
                'new_password': {
                	required:false,
                    minlength: 8,
                },
                're_password' : {
                required:false,
				minlength : 5,
				equalTo : "#new_password"
			}
            },
            messages: {
            	'nama': {
                    required: 'Silahkan isi form',
                    minlength: 'Isian minimal 1 karakter',
                    maxlength: 'Isian maksimal 100 karakter',
                },
                'username': {
                    required: 'Silahkan isi form',
                    remote: $.validator.format("{0} is already taken.")
                },
                'email_username': {
                    required: 'Silahkan isi form',
                    remote: $.validator.format("{0} is already taken."),
                    email:"Silahkan masukkan format email, contoh john.doe@mail.com"
                },
                'old_password': {
                    remote: 'Password lama yang anda isikan salah',
                },
                'new_password': {
                    minlength: 'Isian minimal 8 karakter',
                },
                're_password': {
                	minlength: 'Isian minimal 8 karakter',
                    equalTo:'Password yang anda inputkan salah!'
                },
            }
        });

	$('input').on('focus focusout keyup', function () {
		$(this).valid();
	});

	// $('#form').on('submit','#simpan',function(e){
	// 	if($(e.currentTarget).valid()==true)
	// 	{
	// 		$('#simpan').html('Simpan');
	// 	}
	// })
}
</script>
@endpush