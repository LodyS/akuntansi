<div class="modal-dialog modal-simple">
	{{ Form::model($settingSurplusDefisit,array('route' => array((!$settingSurplusDefisit->exists) ? 'setting-surplus-defisit.store':'setting-surplus-defisit.update',$settingSurplusDefisit->pk()),
	'class'=>'modal-content','id'=>'setting-surplus-defisit-form','method'=>(!$settingSurplusDefisit->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    	<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="formModalLabel">{{ ($settingSurplusDefisit->exists?'Edit':'Tambah').' Master Surplus Surplus Deposit' }}</h4>
	</div>

		<div class="modal-body">

			<div class="form-group row">
                <label class="col-md-3">Tipe</label>
                    <div class="col-md-7">
                    <input type="radio" class="tipe" name="tipe" id="Induk" value="Induk" {{ ($settingSurplusDefisit->induk == null) ? "checked" : '' }} onClick="javascript:showForm()" required><label>Induk</label>
                    <input type="radio" class="tipe" name="tipe" id="Child" value="Child" {{ ($settingSurplusDefisit->induk != null) ? "checked" : '' }} onClick="javascript:showForm()"><label>Child</label>
                </div>
            </div>

			<div id="tampil" style="display:none" class="none">
				<div class="form-group row">
                    <label class="col-md-3">Induk</label>
                        <div class="col-md-7">
                        <select name="induk" id="induk" class="form-control select">
                        	<option value="">Pilih Induk</option>
                        	@foreach ($induk as $indok)
                        	<option value="{{ $indok->id }}" {{ ($settingSurplusDefisit->induk == $indok->id)?'selected':''}}>{{ $indok->nama }}</option>
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
						<option value="1" {{($settingSurplusDefisit->jenis == '1')?'selected':''}}>Penambah</option>
                        <option value="-1" {{($settingSurplusDefisit->jenis == '-1')?'selected':''}}>Pengurang</option>
                    </select>
                </div>
            </div>

			{!! App\Console\Commands\Generator\Form::input('nama','text')->model($settingSurplusDefisit)->show() !!}
			<div class="form-group row">
                <label class="col-md-3">Kode</label>
                    <div class="col-md-7">
                    <input type="text" name="kode" id="kode" class="form-control" value="{{ ($aksi == 'update') ? $settingSurplusDefisit->kode : $kodeS }}" readonly>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Level</label>
                    <div class="col-md-7">
                    <input type="text" name="level" id="level" class="form-control" value="{{ ($aksi == 'update') ? $settingSurplusDefisit->level : 0 }}" readonly>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Urutan</label>
                    <div class="col-md-7">
                    <input type="text" name="urutan" id="urutan" class="form-control" value="{{ ($aksi == 'update') ? $settingSurplusDefisit->urutan : 0 }}" readonly>
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

$(document).ready(function(){

	$(".select").select2({
    	dropdownParent: $("#setting-surplus-defisit-form"),
		width: '100%'
	});

	$('.tipe').click(function(){
  		var tipe = $(this).val();

		if(tipe == "Induk"){
			$("#kode").attr("readonly", false);
		} else {
			$("#kode").attr("readonly", true);
  		}
	});

	$('#induk').change(function(){
    	var induk = $(this).val();
    	var url = '{{ route("isiSurplusDefisit", ":induk") }}';
    	url = url.replace(':induk', induk);

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

	$('#setting-surplus-defisit-form').formValidation({
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
		valid: 'is-valid',
		invalid: 'is-invalid'
		},
		row: {
			invalid: 'has-danger'
		}
	});
});
</script>
