<div class="modal-dialog modal-simple">

    {{ Form::model($laboratorium,array('route' => array((!$laboratorium->exists) ? 'laboratorium.store':'laboratorium.update',$laboratorium->pk()),
	    'class'=>'modal-content','id'=>'laboratorium-form','method'=>(!$laboratorium->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($laboratorium->exists?'Edit':'Tambah').' Laboratorium' }}</h4>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="id_layanan" class="col-form-label col-md-3">Layanan</label>
            <div class="col-md-7">
                <select name="id_layanan" class="form-control" id="id_layanan">
                    <option>-Pilih-</option>
                    @foreach ($layanan as $s)
                    <option value="{{ $s->id}}">{{ $s->nama }}</option>
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
    $(document).ready(function(){
        $('#laboratorium-form').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                id_layanan: {
                    validators: {
                        greaterThan: {
                            value: 1,
                            message: 'Kolom layanan tidak boleh kosong',
                        }
                    }
                },
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
