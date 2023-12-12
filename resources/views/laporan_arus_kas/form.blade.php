<div class="modal-dialog modal-simple">

		{{ Form::model($arusKa,array('route' => array((!$arusKa->exists) ? 'arus-ka.store':'arus-ka.update',$arusKa->pk()),
	        'class'=>'modal-content','id'=>'arus-ka-form','method'=>(!$arusKa->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($arusKa->exists?'Edit':'Tambah').' Arus Kas' }}</h4>
    </div>
    <div class="modal-body">

	<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
	<input type="hidden" name="user_update" value="{{ Auth::user()->id }}">
	<input type="hidden" name="user_delete" value="{{ Auth::user()->id }}">

    <div class="form-group row">
                <label class="col-md-3">Tipe</label>
                    <div class="col-md-7">
                    <input type="radio" class="tipe" name="tipe" id="Induk" value="Induk" {{ ($arusKa->id_induk == null) ? "checked" : '' }} onClick="javascript:showForm()" required><label>Induk</label>
                    <input type="radio" class="tipe" name="tipe" id="Child" value="Child" {{ ($arusKa->id_induk != null) ? "checked" : '' }} onClick="javascript:showForm()"><label>Child</label>
                </div>
            </div>

			<div id="tampil" style="display:none" class="none">
				<div class="form-group row">
                    <label class="col-md-3">Induk</label>
                        <div class="col-md-7">
                        <select name="id_induk" id="id_induk" class="form-control select">
                        	<option value="">Pilih Induk</option>
                        	@foreach ($induk as $indok)
                        	<option value="{{ $indok->id }}" {{ ($arusKa->induk == $indok->id)?'selected':''}}>{{ $indok->nama }}</option>
                        	@endforeach
                    	</select>
                	</div>
            	</div>
			</div>

			<div class="form-group row">
                <label class="col-md-3">Jenis</label>
                    <div class="col-md-7">
                        <select name="jenis" id="jenis" class="form-control" required>
                        <option value="">Pilih Jenis</option>
						<option value="1" {{($arusKa->jenis == '1')?'selected':''}}>Penambah</option>
                        <option value="-1" {{($arusKa->jenis == '-1')?'selected':''}}>Pengurang</option>
                    </select>
                </div>
            </div>

			{!! App\Console\Commands\Generator\Form::input('nama','text')->model($arusKa)->show() !!}
			<div class="form-group row">
                <label class="col-md-3">Kode</label>
                    <div class="col-md-7">
                    <input type="text" name="kode" id="kode" class="form-control" value="{{ ($aksi == 'update') ? $arusKa->kode : $kode }}" readonly>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Level</label>
                    <div class="col-md-7">
                    <input type="text" name="level" id="level" class="form-control" value="{{ ($aksi == 'update') ? $arusKa->level : 0 }}" readonly>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Urutan</label>
                    <div class="col-md-7">
                    <input type="text" name="urutan" id="urutan" class="form-control" value="{{ ($aksi == 'update') ? $arusKa->urutan : 0 }}" readonly>
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

function showForm() {
    if (document.getElementById('Child').checked) {
        document.getElementById('tampil').style.display = 'block';
    } else {
        document.getElementById('tampil').style.display = 'none';
    }
} //untuk menampilkan dan menyembunyikan kolom asuransi

$("#id_induk").select2({
		width: '100%'
	});

$(document).ready(function(){

    $('#id_induk').change(function(){
    	var id_induk = $(this).val();
    	var url = '{{ route("isiArusKas", ":id_induk") }}';
    	url = url.replace(':id_induk', id_induk);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        	if(response != null){
				$('#kode').val(response.kode);
            	$('#urutan').val(response.urutan);
				$('#level').val(response.level);
        	}}
    	});
	});

	$('#arus-ka-form').formValidation({
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
