<div class="modal-dialog modal-simple">

	{{ Form::model($surplusDefisitDetail,array('route' => array((!$surplusDefisitDetail->exists) ? 'surplus-defisit-detail.store':'surplus-defisit-detail.update',$surplusDefisitDetail->pk()),
	    'class'=>'modal-content','id'=>'surplus-defisit-detail-form','method'=>(!$surplusDefisitDetail->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="formModalLabel">{{ ($surplusDefisitDetail->exists?'Edit':'Tambah').' Surplus Defisit Detail' }}</h4>
    </div>

    <div class="modal-body">

        <div class="form-group row">
		    <label class="col-md-3">Surplus Defisit</label>
				<div class="col-md-7">
				    <select name="id_surplus_defisit" id="id_surplus_defisit" class="form-control select">
					<option value="">Pilih</option>
                    @foreach ($surplusDefisit as $p)
					<option value="{{ $p->id}}" {{ ($surplusDefisitDetail->id_surplus_defisit==$p->id)?'selected':''}}>{{ $p->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>

			{!! App\Console\Commands\Generator\Form::input('nama','text')->model($surplusDefisitDetail)->show() !!}
			<div class="form-group row">
                <label class="col-md-3">Jenis</label>
                    <div class="col-md-7">
                        <select name="type" class="form-control" required>
                        <option value="">Pilih Jenis</option>
						<option value="1" {{($surplusDefisitDetail->type == '1')?'selected':''}}>Penambah</option>
                        <option value="-1" {{($surplusDefisitDetail->type == '-1')?'selected':''}}>Pengurang</option>
                    </select>
                </div>
            </div>

			{!! App\Console\Commands\Generator\Form::input('urutan','text')->model($surplusDefisitDetail)->show() !!}

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

$('#nama').change(function () {
    var nama = $(this).val();
    var url = '{{ route("cekNamaSurplusDefisitDetail", ":nama") }}';
    url = url.replace(':nama', nama);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {

        if (response.status == 'Ada') {
            swal('Warning','Surplus Defisit Detail sudah ada','warning')
			//alert('Kode bank sudah ada')
        }}
    });
});

$('#surplus-defisit-detail-form').formValidation({
    framework: "bootstrap4",
	button: {
	    selector: "#simpan",
	    disabled: "disabled"
	},

        icon: null,
	        fields: {
	            list_code_rekening : {
                    validators: {
				        notEmpty: {
				          message: 'Kolom list_code_rekening tidak boleh kosong'
							}
						}
					},
                    list_code_unit : { validators: {
				        notEmpty: {
				          message: 'Kolom list_code_unit tidak boleh kosong'
							}
						}
					},
                    type : { validators: {
				        notEmpty: {
				          message: 'Kolom type tidak boleh kosong'
							}
						}
					},
                    urutan : { validators: {
				        notEmpty: {
				        message: 'Kolom urutan tidak boleh kosong'
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
