<div class="modal-dialog modal-simple">
	{{ Form::model($surplusDefisit,array('route' => array((!$surplusDefisit->exists) ? 'surplus-defisit.store':'surplus-defisit.update',$surplusDefisit->pk()),
	'class'=>'modal-content','id'=>'surplus-defisit-form','method'=>(!$surplusDefisit->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      		<h4 class="modal-title" id="formModalLabel">{{ ($surplusDefisit->exists?'Edit':'Tambah').' Surplus Defisit' }}</h4>
    	</div>

			<div class="modal-body">
				{!! App\Console\Commands\Generator\Form::input('nama','text')->model($surplusDefisit)->show() !!}
        		<div class="form-group row">
		    		<label class="col-md-3">Urutan</label>
			    		<div class="col-md-7">
			    		<input name="urutan" id="urutan" value="{{ ($aksi == 'update') ? $surplusDefisit->urutan : '' }}" class="form-control">
		    		</div>
	    		</div>

				{!! App\Console\Commands\Generator\Form::input('urutan_romawi','text')->model($surplusDefisit)->show() !!}
        		<div class="form-group row">
					<label class="col-md-3">Penyusutan</label>
						<div class="col-md-7">
						<input type="radio" required name="aktif"  value="1" {{ ($surplusDefisit->aktif=="1")?'checked':''}}><label>Ya</label>
						<input type="radio" name="aktif" value="0" {{ ($surplusDefisit->aktif=="0")?'checked':''}}><label>Tidak</label>
					</div>
				</div>

				<div class="col-md-12 float-right">
					<div class="text-right"><button class="btn btn-primary" id="simpan">Simpan</button>
				</div>

			</div>
		</div>
	{{ Form::close() }}
</div>


<script type="text/javascript">
$(document).ready(function(){

$('#nama').change(function () {
    var nama = $(this).val();
    var url = '{{ route("cekNamaSurplusDefisit", ":nama") }}';
    url = url.replace(':nama', nama);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {

        if (response.status == 'Ada') {
            swal('Warning','Surplus Defisit sudah ada','warning')
			//alert('Kode bank sudah ada')
        }}
    });
});

$('#surplus-defisit-form').formValidation({
	framework: "bootstrap4",
	button: {
	    selector: "#simpan",
	    disabled: "disabled"
	},

	    icon: null,
	  		fields: {
				urutan : {
					validators: {
				        notEmpty: {
				        	message: 'Kolom urutan tidak boleh kosong'
						}
					}
				},
            	urutan_romawi : {
					validators: {
				    	notEmpty: {
				    		message: 'Kolom urutan tidak boleh kosong'
						}
					}
				},
                aktif : {
					validators: {
				    notEmpty: {
				    message: 'Kolom aktif tidak boleh kosong'
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
