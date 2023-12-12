<div class="modal-dialog modal-simple modal-lg">

    {{ Form::model($tarif,array('route' => array((!$tarif->exists) ? 'tarif.store':'tarif.update',$tarif->pk()),
        'class'=>'modal-content','id'=>'tarif-form','method'=>(!$tarif->exists) ? 'POST' : 'PUT')) }}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="formModalLabel">{{ ($tarif->exists?'Edit':'Tambah').' Tarif Pajak' }}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="id" class="col-form-label col-md-3">Id</label>
                    <div class="col-md-9">
                        <input name="id" id="id" class="form-control" type="text" value="{{ $id }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="total_utama" class="col-form-label col-md-3">Total Utama</label>
                    <div class="col-md-9">
                        <input name="total_utama" id="total_utama" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="id_kelas" class="col-form-label col-md-3">Kelas</label>
                    <div class="col-md-9">
                        <select name="id_kelas" class="form-control" id="id_kelas">
                            <option>-Pilih-</option>
                            @foreach ($kelas as $k)
                            <option value="{{ $k->id}}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="persen_nakes_utama" class="col-form-label col-md-3">% Nakes
                        Utama</label>
                    <div class="col-md-9">
                        <input name="persen_nakes_utama" id="persen_nakes_utama" class="form-control number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="id_layanan" class="col-form-label col-md-3">Layanan</label>
                    <div class="col-md-9">
                        <select name="id_layanan" class="form-control" id="id_layanan">
                            <option>-Pilih-</option>
                            @foreach ($layanan as $l)
                            <option value="{{ $l->id}}">{{ $l->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="persen_rs_utama" class="col-form-label col-md-3">% RS
                        Utama</label>
                    <div class="col-md-9">
                        <input name="persen_rs_utama" id="persen_rs_utama" class="form-control number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="jasa_sarana" class="col-form-label col-md-3">Jasa Sarana</label>
                    <div class="col-md-9">
                        <input name="jasa_sarana" id="jasa_sarana" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="total_pendukung" class="col-form-label col-md-3">Total Pendukung</label>
                    <div class="col-md-9">
                        <input name="total_pendukung" id="total_pendukung" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="bhp" class="col-form-label col-md-3">BHP</label>
                    <div class="col-md-9">
                        <input name="bhp" id="bhp" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="persen_nakes_pendukung" class="col-form-label col-md-3">% Nakes
                        Pendukung</label>
                    <div class="col-md-9">
                        <input name="persen_nakes_pendukung" id="persen_nakes_pendukung number-only" class="form-control"
                            type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="alkes" class="col-form-label col-md-3">Alkes</label>
                    <div class="col-md-9">
                        <input name="alkes" id="alkes" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="persen_rs_pendukung" class="col-form-label col-md-3">% RS
                        Pendukung</label>
                    <div class="col-md-9">
                        <input name="persen_rs_pendukung" id="persen_rs_pendukung" class="form-control number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="kr" class="col-form-label col-md-3">KR</label>
                    <div class="col-md-9">
                        <input name="kr" id="kr" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="total_pendamping" class="col-form-label col-md-3">Total Pendamping</label>
                    <div class="col-md-9">
                        <input name="total_pendamping" id="total_pendamping" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="ulup" class="col-form-label col-md-3">Ulup</label>
                    <div class="col-md-9">
                        <input name="ulup" id="ulup" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="persen_nakes_pendamping" class="col-form-label col-md-3">% Nakes
                        Pendamping</label>
                    <div class="col-md-9">
                        <input name="persen_nakes_pendamping" id="persen_nakes_pendamping" class="form-control number-only"
                            type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="adm" class="col-form-label col-md-3">Adm</label>
                    <div class="col-md-9">
                        <input name="adm" id="adm" class="form-control qty number-only" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="persen_rs_pendamping" class="col-form-label col-md-3">% RS
                        Pendamping</label>
                    <div class="col-md-9">
                        <input name="persen_rs_pendamping" id="persen_rs_pendamping" class="form-control number-only" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="total" class="col-form-label col-md-3">Total Tarif</label>
                    <div class="col-md-9">
                        <input name="total" id="total" class="form-control" type="text" readonly>
                    </div>
                </div>
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
        $('#tarif-form .qty').on("change", function() {
            var sum = 0;

            $(".qty").each(function(){
                sum += +$(this).val();
            });

            $("#tarif-form input[name=total]").val(sum);
        });

        $('#tarif-form .number-only').keyup(function(e) {
            if (/\D/g.test(this.value))
            {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
        });

        $('#tarif-form input[name=persen_nakes_utama]').on("keyup", function() {
            let viceVersa = 0;

            if (this.value < 0) {
                this.value = 0;
                viceVersa = 100;
            }
            if (this.value > 100) {
                this.value = 100;
                viceVersa = 0;
            }

            viceVersa = 100 - this.value;

            $("#tarif-form input[name=persen_rs_utama]").val(viceVersa);
        });

        $('#tarif-form input[name=persen_rs_utama]').on("keyup", function() {
            let viceVersa = 0;

            if (this.value < 0) {
                this.value = 0;
                viceVersa = 100;
            }
            if (this.value > 100) {
                this.value = 100;
                viceVersa = 0;
            }

            viceVersa = 100 - this.value;

            $("#tarif-form input[name=persen_nakes_utama]").val(viceVersa);
        });

        $('#tarif-form input[name=persen_nakes_pendukung]').on("keyup", function() {
            let viceVersa = 0;

            if (this.value < 0) {
                this.value = 0;
                viceVersa = 100;
            }
            if (this.value > 100) {
                this.value = 100;
                viceVersa = 0;
            }

            viceVersa = 100 - this.value;

            $("#tarif-form input[name=persen_rs_pendukung]").val(viceVersa);
        });

        $('#tarif-form input[name=persen_rs_pendukung]').on("keyup", function() {
            let viceVersa = 0;

            if (this.value < 0) {
                this.value = 0;
                viceVersa = 100;
            }
            if (this.value > 100) {
                this.value = 100;
                viceVersa = 0;
            }

            viceVersa = 100 - this.value;

            $("#tarif-form input[name=persen_nakes_pendukung]").val(viceVersa);
        });

        $('#tarif-form input[name=persen_nakes_pendamping]').on("keyup", function() {
            let viceVersa = 0;

            if (this.value < 0) {
                this.value = 0;
                viceVersa = 100;
            }
            if (this.value > 100) {
                this.value = 100;
                viceVersa = 0;
            }

            viceVersa = 100 - this.value;

            $("#tarif-form input[name=persen_rs_pendamping]").val(viceVersa);
        });

        $('#tarif-form input[name=persen_rs_pendamping]').on("keyup", function() {
            let viceVersa = 0;

            if (this.value < 0) {
                this.value = 0;
                viceVersa = 100;
            }
            if (this.value > 100) {
                this.value = 100;
                viceVersa = 0;
            }

            viceVersa = 100 - this.value;

            $("#tarif-form input[name=persen_nakes_pendamping]").val(viceVersa);
        });

        $('#tarif-form').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                total_utama : {
                    validators: {
                        notEmpty: {
                            message: 'Kolom total_utama tidak boleh kosong'
                        }
                    }
                },
                id_kelas: {
                    validators: {
                        greaterThan: {
                            value: 1,
                            message: 'Kolom kelas tidak boleh kosong',
                        }
                    }
                },
                persen_nakes_utama : {
                    validators: {
                        notEmpty: {
                            message: 'Kolom persen_nakes_utama tidak boleh kosong'
                        }
                    }
                },
                id_layanan: {
                    validators : {
                        greaterThan: {
                            value: 1,
                            message: 'Kolom layanan tidak boleh kosong',
                        }
                    }
                },
                persen_rs_utama : {
                    validators: {
                        notEmpty: {
                            message: 'Kolom persen_rs_utama tidak boleh kosong'
                        }
                    }
                },
                jasa_sarana: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom jasa_sarana tidak boleh kosong'
                        }
                    }
                },
                bhp: {
                    validators: {
                        notEmpty : {
                            message: 'Kolom bhp tidak boleh kosong'
                        }
                    }
                },
                persen_nakes_pendukung : {
                    validators: {
                        notEmpty: {
                            message: 'Kolom persen_nakes_pendukung tidak boleh kosong'
                        }
                    }
                },
                alkes: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom alkes tidak boleh kosong'
                        }
                    }
                },
                total_pendukung: {
                    validators : {
                        notEmpty : {
                            message: 'Kolom total_pendukung tidak boleh kosong'
                        }
                    }
                },
                persen_rs_pendukung: {
                    validators : {
                        notEmpty : {
                            message: 'Kolom persen_rs_pendukung tidak boleh kosong'
                        }
                    }
                },
                kr : {
                    validators: {
                        notEmpty: {
                            message: 'Kolom kr tidak boleh kosong'
                        }
                    }
                },
                ulup: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom ulup tidak boleh kosong'
                        }
                    }
                },
                total_pendamping: {
                    validators : {
                        notEmpty : {
                            message: 'Kolom total_pendamping tidak boleh kosong'
                        }
                    }
                },
                persen_nakes_pendamping: {
                    validators : {
                        notEmpty : {
                            message: 'Kolom persen_nakes_pendamping tidak boleh kosong'
                        }
                    }
                },
                adm: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom adm tidak boleh kosong'
                        }
                    }
                },
                persen_rs_pendamping: {
                    validators : {
                        notEmpty : {
                            message: 'Kolom persen_rs_pendamping tidak boleh kosong'
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
