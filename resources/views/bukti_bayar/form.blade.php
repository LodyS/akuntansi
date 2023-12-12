<div class="modal-dialog modal-simple">

    {{ Form::model($buktiBayar,array('route' => array((!$buktiBayar->exists) ? 'bukti-bayar.store':'bukti-bayar.update',$buktiBayar->pk()),
	        'class'=>'modal-content','id'=>'bukti-bayar-form','method'=>(!$buktiBayar->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($buktiBayar->exists?'Edit':'Tambah').' Bukti Pembayaran' }}</h4>
    </div>
    <div class="modal-body">
        {!! App\Console\Commands\Generator\Form::input('atas_nama','text')->model($buktiBayar)->show() !!}
        {!! App\Console\Commands\Generator\Form::input('telp1','text')->model($buktiBayar)->show() !!}
        {!! App\Console\Commands\Generator\Form::input('telp2','text')->model($buktiBayar)->show() !!}
        {!! App\Console\Commands\Generator\Form::input('email','text')->model($buktiBayar)->show() !!}
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
        $('#bukti-bayar-form').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                atas_nama: {
                    validators: {
                        notEmpty: {
                            message: 'Nama wajib diisi'
                        }
                    }
                },
                telp1: {
                    validators: {
                        notEmpty: {
                            message: 'Telp 1 wajib diisi'
                        },
                        numeric: {
                            message: 'Telp 1 wajib angka'
                        },
                    }
                },
                telp2: {
                    validators: {
                        numeric: {
                            message: 'Telp 2 wajib angka'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Email wajib diisi'
                        },
                        emailAddress: {
                            message: 'Email tidak valid'
                        },
                    }
                },
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
