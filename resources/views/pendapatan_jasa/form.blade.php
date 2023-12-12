<div class="modal-dialog modal-simple">

		{{ Form::model($pendapatanJasa,array('route' => array((!$pendapatanJasa->exists) ? 'pendapatan-jasa.store':'pendapatan-jasa.update',$pendapatanJasa->pk()),
	        'class'=>'modal-content','id'=>'pendapatan-jasa-form','method'=>(!$pendapatanJasa->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($pendapatanJasa->exists?'Edit':'Tambah').' Pendapatan Jasa' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('no_bukti_transaksi','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('no_kunjungan','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('tanggal','date')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('id_pelanggan','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('jenis','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('type_bayar','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('type_pasien','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('id_user','hidden')->model($pendapatanJasa)->showHidden() !!}
{!! App\Console\Commands\Generator\Form::autocomplete('user',array('value'=>$pendapatanJasa->exists?(isset($pendapatanJasa->user)?$pendapatanJasa->user->user:null):null))->model(null)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('total_tagihan','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('id_bank','hidden')->model($pendapatanJasa)->showHidden() !!}
{!! App\Console\Commands\Generator\Form::autocomplete('bank',array('value'=>$pendapatanJasa->exists?(isset($pendapatanJasa->bank)?$pendapatanJasa->bank->bank:null):null))->model(null)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('discharge','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('waktu_pulang','date')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('ref_discharge','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('no_jurnal','text')->model($pendapatanJasa)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('user_update','text')->model($pendapatanJasa)->show() !!}
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
	$('#pendapatan-jasa-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	no_bukti_transaksi : { validators: {
				        notEmpty: {
				          message: 'Kolom no_bukti_transaksi tidak boleh kosong'
							}
						}
					},no_kunjungan : { validators: {
				        notEmpty: {
				          message: 'Kolom no_kunjungan tidak boleh kosong'
							}
						}
					},tanggal : { validators: {
				        notEmpty: {
				          message: 'Kolom tanggal tidak boleh kosong'
							}
						}
					},id_pelanggan : { validators: {
				        notEmpty: {
				          message: 'Kolom id_pelanggan tidak boleh kosong'
							}
						}
					},jenis : { validators: {
				        notEmpty: {
				          message: 'Kolom jenis tidak boleh kosong'
							}
						}
					},type_bayar : { validators: {
				        notEmpty: {
				          message: 'Kolom type_bayar tidak boleh kosong'
							}
						}
					},type_pasien : { validators: {
				        notEmpty: {
				          message: 'Kolom type_pasien tidak boleh kosong'
							}
						}
					},id_user : { validators: {
				        notEmpty: {
				          message: 'Kolom id_user tidak boleh kosong'
							}
						}
					},total_tagihan : { validators: {
				        notEmpty: {
				          message: 'Kolom total_tagihan tidak boleh kosong'
							}
						}
					},id_bank : { validators: {
				        notEmpty: {
				          message: 'Kolom id_bank tidak boleh kosong'
							}
						}
					},discharge : { validators: {
				        notEmpty: {
				          message: 'Kolom discharge tidak boleh kosong'
							}
						}
					},ref_discharge : { validators: {
				        notEmpty: {
				          message: 'Kolom ref_discharge tidak boleh kosong'
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
	
					var userEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/user") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#user").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: userEngine.ttAdapter(),
									name: "user",
									displayKey: "user",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{user}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>user tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_user").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					
					var bankEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/bank") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#bank").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: bankEngine.ttAdapter(),
									name: "bank",
									displayKey: "bank",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{bank}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>bank tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_bank").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});
					

});
</script>
