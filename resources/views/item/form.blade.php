<div class="modal-dialog modal-simple">

    {{ Form::model($item,array('route' => array((!$item->exists) ? 'item.store':'item.update',$item->pk()),
	        'class'=>'modal-content','id'=>'item-form','method'=>(!$item->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($item->exists?'Edit':'Tambah').' Item' }}</h4>
    </div>
    <div class="modal-body">
        {!! App\Console\Commands\Generator\Form::input('nama','text',['required'=>'required'])->model($item)->show() !!}
        {!! App\Console\Commands\Generator\Form::input('harga','text',['required'=>'required'])->model($item)->show() !!}
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
        $('#harga').on('change click keyup input paste', (function (event) {
            $(this).val(function (index, value) {
                return replaceToCurrency(value);
            });
        }));

        $('#item-form').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                nama: {
                    validators: {
                        notEmpty: {
                            message: 'Nama wajib diisi'
                        }
                    }
                },
                harga: {
                    validators: {
                        notEmpty: {
                            message: 'Harga wajib diisi'
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
