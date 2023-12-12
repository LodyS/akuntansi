<div class="modal-dialog modal-simple">

    {{ Form::model($radiologi,array('route' => array((!$radiologi->exists) ? 'radiologi.store':'radiologi.update',$radiologi->pk()),
	    'class'=>'modal-content','id'=>'radiologi-form','method'=>(!$radiologi->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($radiologi->exists?'Edit':'Tambah').' Radiologi' }}</h4>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="id_layanan" class="col-form-label col-md-3">Layanan</label>
            <div class="col-md-7">
                <select name="id_layanan" class="form-control" id="id_layanan">
                    <option>-Pilih-</option>
                    @foreach ($layanan as $l)
                    <option value="{{ $l->id}}">{{ $l->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="id_jenis_radiologi" class="col-form-label col-md-3">Jenis Radiologi</label>
            <div class="col-md-7">
                <select name="id_jenis_radiologi" class="form-control" id="id_jenis_radiologi">
                    <option>-Pilih-</option>
                    @foreach ($jenis_radiologi as $j)
                    <option value="{{ $j->id}}">{{ $j->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="id_golongan_radiologi" class="col-form-label col-md-3">Golongan Radiologi</label>
            <div class="col-md-7">
                <select name="id_golongan_radiologi" class="form-control" id="id_golongan_radiologi">
                    <option>-Pilih-</option>
                    @foreach ($golongan_radiologi as $g)
                    <option value="{{ $g->id}}">{{ $g->nama }}</option>
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
        $('#radiologi-form').formValidation({
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
                id_jenis_radiologi: {
                    validators: {
                        greaterThan: {
                            value: 1,
                            message: 'Kolom jenis radiologi tidak boleh kosong',
                        }
                    }
                },
                id_golongan_radiologi: {
                    validators: {
                        greaterThan: {
                            value: 1,
                            message: 'Kolom golongan radiologi tidak boleh kosong',
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
