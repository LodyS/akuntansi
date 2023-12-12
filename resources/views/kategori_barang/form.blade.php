<div class="modal-dialog modal-simple">

	{{ Form::model($kategoriBarang,array('route' => array((!$kategoriBarang->exists) ? 'kategori-barang.store':'kategori-barang.update',$kategoriBarang->pk()),
	'class'=>'modal-content','id'=>'kategori-barang-form','method'=>(!$kategoriBarang->exists) ? 'POST' : 'PUT')) }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">×</span>
    </button>    
	<h4 class="modal-title" id="formModalLabel">{{ ($kategoriBarang->exists?'Edit':'Tambah').' Kategori Barang' }}</h4>
</div>

    <div class="modal-body">
		{!! App\Console\Commands\Generator\Form::input('nama','text')->model($kategoriBarang)->show() !!}

		<div class="form-group row">
			<label class="col-md-3">COA Persediaan</label>
				<div class="col-md-7">
				<select name="id_perkiraan" class="form-control select" id="id_perkiraan">
					<option value="--">Pilih Perkiraan</option>
                    @foreach ($perkiraan as $coa)
                    <option value="{{ $coa->id}}" {{($coa->id == $kategoriBarang->id_perkiraan)?'selected':''}}>{{ $coa->nama}}</option>
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

$(".select").select2({
    dropdownParent: $("#kategori-barang-form"),
	width: '100%'
});

$(document).ready(function(){
	$('#kategori-barang-form').formValidation({
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

		id_perkiraan : { 
		validators: {
		notEmpty: {
			message: 'Kolom COA Persediaan tidak boleh kosong'
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
