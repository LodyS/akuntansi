<div class="modal-dialog modal-simple">

		{{ Form::model($pembayaranInvoice,array('route' => array((!$pembayaranInvoice->exists) ? 'pembayaran-invoice.store':'pembayaran-invoice.update',$pembayaranInvoice->pk()),
	        'class'=>'modal-content','id'=>'pembayaran-invoice-form','method'=>(!$pembayaranInvoice->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($pembayaranInvoice->exists?'Edit':'Tambah').' Pembayaran Invoice' }}</h4>
    </div>
    <div class="modal-body">
																				        {!! App\Console\Commands\Generator\Form::input('tanggal','date')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('id_pelanggan','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('id_invoice','hidden')->model($pembayaranInvoice)->showHidden() !!}
{!! App\Console\Commands\Generator\Form::autocomplete('invoice',array('value'=>$pembayaranInvoice->exists?(isset($pembayaranInvoice->invoice)?$pembayaranInvoice->invoice->invoice:null):null))->model(null)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('sub_total','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('ppn','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('total','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('pph_23','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('jumlah_bayar','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('id_bank','hidden')->model($pembayaranInvoice)->showHidden() !!}
{!! App\Console\Commands\Generator\Form::autocomplete('bank',array('value'=>$pembayaranInvoice->exists?(isset($pembayaranInvoice->bank)?$pembayaranInvoice->bank->bank:null):null))->model(null)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('kurang_bayar','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('flag_jurnal','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('no_jurnal','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('user_input','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('user_update','text')->model($pembayaranInvoice)->show() !!}
																        {!! App\Console\Commands\Generator\Form::input('user_delete','text')->model($pembayaranInvoice)->show() !!}
																																        {!! App\Console\Commands\Generator\Form::input('delete_at','text')->model($pembayaranInvoice)->show() !!}
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
	$('#pembayaran-invoice-form').formValidation({
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
	
					var invoiceEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/invoice") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#invoice").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: invoiceEngine.ttAdapter(),
									name: "invoice",
									displayKey: "invoice",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{invoice}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>invoice tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_invoice").val(datum.id);
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
