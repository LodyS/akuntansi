<div class="modal-dialog modal-simple">
	{{ Form::model($setSurplusDefisitDetail,array('route' => array((!$setSurplusDefisitDetail->exists) ? 'set-surplus-defisit-detail.store':'set-surplus-defisit-detail.update',$setSurplusDefisitDetail->pk()),
	        'class'=>'modal-content','id'=>'set-surplus-defisit-detail-form','method'=>(!$setSurplusDefisitDetail->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="formModalLabel">{{ ($setSurplusDefisitDetail->exists?'Edit':'Tambah').' Set Surplus Defisit Detail' }}</h4>
</div>
    
	<div class="modal-body">

				<div class="form-group row">
					<label class="col-md-3">Komponen Surplus Defisit</label>
						<div class="col-md-7">
						@if ($aksi == 'create')
							<select name="id_set_surplus_defisit" id="id_set_surplus_defisit" class="form-control select" required>
        					<option value="">Pilih</option>
        					@foreach ($settingSurplusDefisit as $setting)
        					<option value="{{ $setting->id }}">{{ $setting->nama }}</option>
        					@endforeach
        				</select>
						@else
						<input class="form-control" value="{{ isset($setSurplusDefisitDetail) ? $setSurplusDefisitDetail->nama : ''}}">
						@endif
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Cost Centre</label>
						<div class="col-md-7">
							<select name="id_unit" id="id_unit" class="form-control select" required>
        					<option value="">Pilih</option>
        					@foreach ($unit as $yunit)
        					<option value="{{ $yunit->id }}" {{ ($setSurplusDefisitDetail->id_unit==$yunit->id)?'selected':''}}>{{ $yunit->nama }}</option>
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

$("#id_set_surplus_defisit").select2({
    width : '100%'
});

$("#id_unit").select2({
    width : '100%'
});

$(document).ready(function(){
	$('#set-surplus-defisit-detail-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
		id_set_surplus_defisit : { 
			validators: {
				notEmpty: {
					message: 'Kolom Komponen Surplus Defisit tidak boleh kosong'
				}
			}
		},
		id_unit : { 
			validators: {
				notEmpty: {
				    message: 'Kolom Unit tidak boleh kosong'
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

});
</script>
