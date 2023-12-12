<div class="modal-dialog modal-simple">

    {{ Form::model($infoPembayaranInvoice,array('route' => array((!$infoPembayaranInvoice->exists) ? 'info-pembayaran-invoice.store':'info-pembayaran-invoice.update',$infoPembayaranInvoice->pk()),
	        'class'=>'modal-content','id'=>'info-pembayaran-invoice-form','method'=>(!$infoPembayaranInvoice->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($infoPembayaranInvoice->exists?'Edit':'Tambah').' Info Pembayaran Invoice' }}</h4>
    </div>
    <div class="modal-body">
        {!! App\Console\Commands\Generator\Form::input('id_bank','hidden')->model($infoPembayaranInvoice)->showHidden() !!}
        {!! App\Console\Commands\Generator\Form::autocomplete('bank',array('value'=>$infoPembayaranInvoice->exists?(isset($infoPembayaranInvoice->bank)?$infoPembayaranInvoice->bank->bank:null):null))->model(null)->show() !!}
        {!! App\Console\Commands\Generator\Form::input('user_input','text')->model($infoPembayaranInvoice)->show() !!}
        {!! App\Console\Commands\Generator\Form::input('user_update','text')->model($infoPembayaranInvoice)->show() !!}
        <div class="col-md-12 float-right">
            <div class="text-right">
                <button class="btn btn-primary" id="simpan">Simpan</button>
            </div>
        </div>
    </div>

    {{ Form::close() }}
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#info-pembayaran-invoice-form').formValidation({
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

        var bankEngine = new Bloodhound({
            datumTokenizer: function (d) {
                return d.tokens;
            },
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
        }, {
            source: bankEngine.ttAdapter(),
            name: "bank",
            displayKey: "bank",
            templates: {
                suggestion: function (data) {
                    return Handlebars.compile([
                        "<div class=\"tt-dataset\">",
                        "<div>@{{ bank }}</div>",
                        "</div>",
                    ].join(""))(data);
                },
                empty: [
                    "<div>bank tidak ditemukan</div>"
                ]
            }
        }).bind("typeahead:selected", function (obj, datum, name) {
            $("#id_bank").val(datum.id);
        }).bind("typeahead:change", function (obj, datum, name) {

        });


    });
</script>
