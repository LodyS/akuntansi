<div class="modal-dialog modal-simple">

    {{ Form::model($visit,array('route' => array((!$visit->exists) ? 'visit.store':'visit.update',$visit->pk()),
	        'class'=>'modal-content','id'=>'visit-form','method'=>(!$visit->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($visit->exists?'Edit':'Tambah').' Kunjungan' }}</h4>
    </div>
    <div class="modal-body">

        {!! App\Console\Commands\Generator\Form::input('waktu','date')->model($visit)->show() !!}

        <div class="form-group row">
            <label class="col-md-3">Pelanggan</label>
            <div class="col-md-7">
                <div class="input-search clearfix">
                    <input id="pelanggan"
                        value="{{($pelanggan->exists?!empty($pelanggan->nama)?$pelanggan->nama:'':'')}}"
                        class="form-control" type="text">
                    <button type="submit" class="input-search-btn">
                        <i class="icon md-search" aria-hidden="true"></i>
                    </button>
                </div>
                {!! App\Console\Commands\Generator\Form::input('id_pelanggan','hidden')->model($visit)->showHidden() !!}
                <span class="help-block" id="pelanggan_a"></span>
            </div>
        </div>

        {{-- {!! App\Console\Commands\Generator\Form::input('id_pelanggan','text')->model($visit)->show() !!} --}}
        {{-- {!! App\Console\Commands\Generator\Form::input('status','text')->model($visit)->show() !!} --}}

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

        $('#visit-form').formValidation({
        framework: "bootstrap4",
        button: {
            selector: "#simpan",
            disabled: "disabled"
        },
        icon: null,
        fields: {
            id_pelanggan : {
                validators: {
                    notEmpty: {
                    message: 'Kolom id_pelanggan tidak boleh kosong'
                        }
                    }
                },
            status : { validators: {
                notEmpty: {
                message: 'Kolom status tidak boleh kosong'
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


        var pelangganEngine = new Bloodhound({
                datumTokenizer: function(d) { return d.tokens; },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                cache: false,
                remote: {
                    url: '{{ url("autocomplete/pelanggan") }}?q=%QUERY',
                    wildcard: "%QUERY"
                }
            });

            $("#pelanggan").typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                },
                {
                        source: pelangganEngine.ttAdapter(),
                        name: "nama",
                        displayKey: "nama",
                        templates: {
                            suggestion: function(data){
                                return Handlebars.compile([
                                                    "<div class=\"tt-dataset\">",
                                                            "<div>@{{kode}} - @{{nama}}</div>",
                                                    "</div>",
                                            ].join(""))(data);
                            },
                                empty: [
                                        "<div>Pelanggan tidak ditemukan</div>"
                                ]
                        }
                }).bind("typeahead:selected", function(obj, datum, name) {
                    $("#id_pelanggan").val(datum.id);
                }).bind("typeahead:change", function(obj, datum, name) {

                });

    });
</script>
