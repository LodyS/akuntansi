<div class="modal-dialog modal-simple">

    {{ Form::model($nakes,array('route' => array((!$nakes->exists) ? 'nakes.store':'nakes.update',$nakes->pk()),
	    'class'=>'modal-content','id'=>'nakes-form','method'=>(!$nakes->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($nakes->exists?'Edit':'Tambah').' Nakes' }}</h4>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="kode" class="col-form-label col-md-3">Kode</label>
            <div class="col-md-7">
                @if (isset($code))
                <input name="kode" id="kode" value="{{ $code }}" class="form-control" readonly type="text">
                <span class="help-block" id="kode_a"></span>
                @else
                <input name="kode" id="kode" value="N-00001" class="form-control" readonly type="text">
                @endif
            </div>
        </div>
        {!! App\Console\Commands\Generator\Form::input('nama','text')->model($nakes)->show() !!}
        <div class="form-group row">
            <label for="id_spesialisasi" class="col-form-label col-md-3">Spesialisasi</label>
            <div class="col-md-7">
                <select name="id_spesialisasi" class="form-control" id="id_spesialisasi">
                    <option>-Pilih-</option>
                    @foreach ($spesialisasi as $s)
                    <option value="{{ $s->id}}" {{ $nakes->exists ? $s->id === $nakes->id_spesialisasi ? 'selected' : '' : '' }}>{{ $s->nama }}</option>
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
        $('#nakes-form').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                kode: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom kode tidak boleh kosong'
                        }
                    }
                },
                nama: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom nama tidak boleh kosong'
                        }
                    }
                },
                id_spesialisasi: {
                    validators: {
                        greaterThan: {
                            value: 1,
                            message: 'Kolom spesialisasi tidak boleh kosong',
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
