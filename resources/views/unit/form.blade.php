<div class="modal-dialog modal-simple">

{{ Form::model($unit,array('route' => array((!$unit->exists) ? 'unit.store':'unit.update', $unit->pk()),
'class'=>'modal-content','id'=>'unit-form','method'=>(!$unit->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="formModalLabel">{{ ($unit->exists?'Edit':'Tambah').' Departemen' }}</h4>
</div>

	<div class="modal-body">
	{{-- <div class="form-body"> --}}
				
		<div class="form-group row">
			<label class="col-md-3">Kode Departemen</label>
				<div class="col-md-7">
				<input name="kode" id="kode" value="{{ isset($unit) ? $unit->kode :'' }}" class="form-control" type="text">		
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Nama Departemen</label>
				<div class="col-md-7">
				<input name="nama" id="nama" value="{{ isset($unit) ? $unit->nama :'' }}" class="form-control" type="text">			
			</div>
		</div>
			
		<div class="form-group row">
			<label class="col-md-3">Keterangan</label>
				<div class="col-md-7">
				<input name="keterangan" id="keterangan" value="{{ isset($unit) ? $unit->keterangan :''}}" class="form-control" type="text">
			</div>
		</div>
		
		<div class="col-md-12 float-right">
			<div class="text-right">
				<button class="btn btn-primary" id="simpan">Simpan</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	$('#kode').change(function () {
    var kode = $(this).val();
    var url = '{{ route("cekKodeDepartemen", ":kode") }}';
    url = url.replace(':kode', kode);

	$.ajax({
    	url: url,
    	type: 'get',
    	dataType: 'json',
    	async: false,
    	success: function (response) {
                
        	if (response.status == 'Ada') {
            	swal('Warning','Kode Departemen sudah ada','warning')
        	}}
    	});
	});

	$('#unit-form').formValidation({
	  	framework: "bootstrap4",
	  	button: {
	    selector: "#simpan",
	    disabled: "disabled"
	},
	  	icon: null,
	  	fields: {
			nama : { 
					validators: {
				    notEmpty: {
				    message: 'Kolom nama tidak boleh kosong'
					}
				}
			},

			keterangan : { 
					validators: {
				    notEmpty: {
				    message: 'Kolom keterangan tidak boleh kosong'
					}
				}
			},

			kode : { 
					validators: {
				    notEmpty: {
				    message: 'Kolom kode tidak boleh kosong'
					}
				}
			}
		},

	err: {
		clazz: 'invalid-feedback'
	},
	control: {
		valid: 'is-valid',
		invalid: 'is-invalid'
	},
	row: {
		invalid: 'has-danger'
		}
	});
});
</script>	