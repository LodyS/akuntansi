<div class="modal-dialog modal-simple">
	{{ Form::model($setNeraca,array('route' => array((!$setNeraca->exists) ? 'set-neraca.store':'set-neraca.update',$setNeraca->pk()),
	'class'=>'modal-content','id'=>'set-neraca-form','method'=>(!$setNeraca->exists) ? 'POST' : 'PUT')) }}

	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    	<h4 class="modal-title" id="formModalLabel">{{ ($setNeraca->exists?'Edit':'Tambah').' Set Neraca' }}</h4>
	</div>

		<div class="modal-body">

			<div class="form-group row">
                <label class="col-md-3">Tipe</label>
                    <div class="col-md-7">
                    <input type="radio" class="tipe" id="Induk" value="Induk" name="tipe" onClick="javascript:showForm()" required {{ ($setNeraca->induk == null) ? "checked" : '' }} ><label>Induk</label>
                    <input type="radio" class="tipe" id="Child" value="Child" name="tipe" onClick="javascript:showForm()" {{ ($setNeraca->induk != null) ? "checked" : '' }}><label>Child</label>
                </div>
            </div>

			{!! App\Console\Commands\Generator\Form::input('nama','text')->model($setNeraca)->show() !!}

			<div id="tampil" style="display:none" class="none">
				<div class="form-group row">
                    <label class="col-md-3">Induk</label>
                        <div class="col-md-7">
                        <select name="induk" id="induk" class="form-control select">
                        	<option value="">Pilih Induk</option>
                        	@foreach ($induk as $indok)
                        	<option value="{{ $indok->id }}" {{ ($setNeraca->induk == $indok->id)?'selected':''}}>{{ $indok->nama }}</option>
                        	@endforeach
                    	</select>
                	</div>
            	</div>

				<div class="form-group row">
                	<label class="col-md-3">Urutan</label>
                    	<div class="col-md-7">
                       <input type="text" name="urutan" id="urutan" value="{{ isset($setNeraca) ? $setNeraca->urutan : $urutan }}" class="form-control" readonly>
                    	</select>
                	</div>
            	</div>
			</div>

            <div class="form-group row">
                <label class="col-md-3">Kode</label>
                    <div class="col-md-7">
                       <input type="text" name="kode" id="kode" class="form-control" value="{{ ($aksi == 'update') ? $setNeraca->kode : $kode_induk }}" readonly>
                    </select>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Level</label>
                    <div class="col-md-7">
                       <input type="text" name="level" id="level" class="form-control" value="{{ ($aksi == 'update') ? $setNeraca->level : '0' }}" readonly>
                    </select>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Jenis</label>
                    <div class="col-md-7">
                        <select name="jenis" id="jenis" class="form-control">
                        <option value="">Pilih Jenis</option>
						<option value="1" {{($setNeraca->jenis == '1')?'selected':''}}>Penambah</option>
                        <option value="-1" {{($setNeraca->jenis == '-1')?'selected':''}}>Pengurang</option>
                    </select>
                </div>
            </div>

			<div class="form-group row">
                <label class="col-md-3">Jenis Neraca</label>
                    <div class="col-md-7">
                        <select name="jenis_neraca" id="jenis_neraca" class="form-control">
                        <option value="">Pilih Jenis Neraca</option>
						<option value="Aktiva" {{($setNeraca->jenis_neraca == 'Aktiva')?'selected':''}}>Aktiva</option>
                        <option value="Passiva" {{($setNeraca->jenis_neraca == 'Passiva')?'selected':''}}>Passiva</option>
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

function showForm()
{
    if (document.getElementById('Child').checked) {
        document.getElementById('tampil').style.display = 'block';
    } else {
        document.getElementById('tampil').style.display = 'none';
    }
}

$('.tipe').click(function(){
	var status = $(this).val();
	if(status == "Induk"){
		$("#kode").attr("readonly", false);
		$('#level').val('0').change();
	} else {
		$("#kode").attr("readonly", true);
  	}
});

$(document).ready(function(){

	$(".select").select2({
    	dropdownParent: $("#set-neraca-form"),
		width: '100%'
	});

	$('#induk').change(function(){
    	var induk = $(this).val();
    	var url = '{{ route("isiSetNeraca", ":induk") }}';
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

	$('#set-neraca-form').formValidation({
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
				    message: 'Kolom Nama tidak boleh kosong'
				}
			}
		},

		kode : {
			validators: {
				notEmpty: {
				    message: 'Kolom Kode tidak boleh kosong'
				}
			}
		},

		jenis : {
			validators: {
				notEmpty: {
				    message: 'Kolom Jenis tidak boleh kosong'
				}
			}
		},

		jenis_neraca : {
			validators: {
				notEmpty: {
				    message: 'Kolom Jenis Neraca tidak boleh kosong'
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
