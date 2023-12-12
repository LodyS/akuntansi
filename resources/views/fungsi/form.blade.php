<div class="modal-dialog modal-simple">

		{{ Form::model($fungsi,array('route' => array((!$fungsi->exists) ? 'fungsi.store':'fungsi.update',$fungsi->pk()),
	        'class'=>'modal-content','id'=>'fungsi-form','method'=>(!$fungsi->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($fungsi->exists?'Edit':'Tambah').' Fungsi' }}</h4>
    </div>
    <div class="modal-body">
						{!! App\Console\Commands\Generator\Form::input('nama_fungsi','text')->model($fungsi)->show() !!}
										
<input type="hidden" name="status_aktif" id="status_aktif" value="{{($fungsi->exists?!empty($fungsi->status_aktif)?$fungsi->status_aktif:'Y':'Y')}}" class="form-control" type="text">
						
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

	$('#nama_fungsi').change(function () {
    var nama_fungsi = $(this).val();
    var url = '{{ route("cekNamaFungsi", ":nama_fungsi") }}';
    url = url.replace(':nama_fungsi', nama_fungsi);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    async: false,
    success: function (response) {
                
        if (response.status == 'Ada') {
            swal('Warning','Nama Fungsi ada','warning')
			//alert('Fungsi sudah ada')
        }}
    });
});

	$('#fungsi-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	nama_fungsi : { validators: {
				        notEmpty: {
				          message: 'Kolom nama_fungsi tidak boleh kosong'
							}
						}
					}, /*status_aktif : { validators: {
				        notEmpty: {
				          message: 'Kolom status_aktif tidak boleh kosong'
							}
						}
					} */
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