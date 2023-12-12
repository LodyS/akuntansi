<div class="modal-dialog modal-simple">

    {{ Form::model($golongan_radiologi,array('route' => array('radiologi.store_golongan_radiologi',$golongan_radiologi->pk()),
	        'class'=>'modal-content','id'=>'golongan_radiologi-form','method'=>(!$golongan_radiologi->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">Tambah Golongan Radiologi</h4>
    </div>
    <div class="modal-body">
        {!! App\Console\Commands\Generator\Form::input('nama', 'text')->model($golongan_radiologi)->show() !!}
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
        $('#golongan_radiologi-form').formValidation({
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
                            message: 'Kolom nama tidak boleh kosong'
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
