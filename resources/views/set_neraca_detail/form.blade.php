<div class="modal-dialog modal-simple">

	{{ Form::model($setNeracaDetail,array('route' => array((!$setNeracaDetail->exists) ? 'set-neraca-detail.store':'set-neraca-detail.update',$setNeracaDetail->pk()),
	'class'=>'modal-content','id'=>'set-neraca-detail-form','method'=>(!$setNeracaDetail->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    	<span aria-hidden="true">×</span></button>
    	<h4 class="modal-title" id="formModalLabel">{{ ($setNeracaDetail->exists?'Edit':'Tambah').' Set Neraca Detail' }}</h4>
	</div>

    	<div class="modal-body">
			<div class="form-group row">
                <label class="col-md-3">Kode</label>
                    <div class="col-md-7">
                       <input type="text" name="kode" class="form-control" value="{{ isset($setNeracaDetail) ? $setNeracaDetail->kode : '' }}" readonly>
                    </select>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Jenis Neraca</label>
                    <div class="col-md-7">
                       <input type="text" name="jenis_neraca" class="form-control" value="{{ isset($setNeracaDetail) ? $setNeracaDetail->jenis_neraca : '' }}" readonly>
                    </select>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Induk</label>
                    <div class="col-md-7">
                       <input type="text" name="induk" class="form-control" value="{{ isset($setNeracaDetail) ? $setNeracaDetail->induk : '' }}" readonly>
                    </select>
                </div>
            </div>

			<div class="form-group row">
            	<label class="col-md-3">Rekening</label>
                	<div class="col-md-7">
                		<select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                        <option value="">Pilih Induk</option>
                        @foreach ($perkiraan as $rekening)
                        <option value="{{ $rekening->id }}" {{ ($setNeracaDetail->id_perkiraan == $rekening->id)?'selected':''}}>{{ $rekening->nama }}</option>
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

$("#id_perkiraan").select2({
	width: '100%'
});

$('#set-neraca-detail-form').formValidation({
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
	
});
</script>
