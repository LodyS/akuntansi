@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Form Jurnal Invoice</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        {{-- <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('invoice.create') }}')">Tambah</a> --}}
    </div>
</div>
<div class="page-content">
    <form id="form-jurnal-invoice" action="{{ url('jurnal-invoice/store-jurnal') }}" method="post">
        @csrf
        <input type="hidden" name="id_invoice" value="{{ $id_invoice }}">

        <!-- Panel Table Tools -->
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5">

                        <div class="form-group row">
                            <label for="kodeJurnal" class="col-sm-4 col-form-label">Tanggal</label>
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipeJurnal" class="col-sm-4 col-form-label">Tipe jurnal</label>
                            <div class="col-sm">
                                <div class="form-group">
                                    <input readonly type="text" class="form-control" id="tipe_jurnal" name="tipe_jurnal" value="{{ $tipe_jurnal->tipe_jurnal }}">
                                    <input readonly type="hidden" class="form-control" id="id_tipe_jurnal" name="id_tipe_jurnal" value="{{ $tipe_jurnal->id }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kodeJurnal" class="col-sm-4 col-form-label">Kode Jurnal</label>
                            <div class="col-sm">
                                <div class="form-group">
                                    <input readonly type="text" class="form-control" id="kode_jurnal" name="kode_jurnal" value="{{ $kode->kode }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kodeJurnal" class="col-sm-4 col-form-label">No Dokumen</label>
                            <div class="col-sm">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" value="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                            <div class="col-sm">
                                <div class="form-group">
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
                <table class="table table-hover dataTable table-striped w-full" id="jurnal-invoice-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Perkiraan/COA</th>
                            <th>Perkiraan/COA</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Balance</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary" id="btnSimpan">Simpan</button>
            </div>
        </div>
        <!-- End Panel Table Tools -->

    </form>
</div>

@endsection
{{-- @dd($data) --}}

@push('js')
<script type="text/javascript">
    $(function () {

        var table = $('#jurnal-invoice-table').DataTable({
            paging: false,
            searching: false,
            info: false,
            data: <?= $data; ?>,
            columns: [
                { data: null, name:null, searchable: false, orderable: false },
                {
                    data: 'kode', name: 'kode', render: function(data, type, row, meta)
                    {
                        return row.kode + ` <input type="hidden" name="detail[${meta.row}][id_perkiraan]" value="${row.id}"> `;
                    },
                },
                { data: 'jenis', name: 'jenis' },
                { data: 'debit', name: 'debit', className: 'text-right', render: function(data, type, row, meta)
                    {
                        const formatRp = $.fn.dataTable.render.number( ".", ".", 0, 'Rp. ' ).display;
                        return formatRp(row.debit) +
                        ` <input type="hidden" name="detail[${meta.row}][debit]" value="${row.debit}"> `;
                    },
                },
                { data: 'kredit', name: 'kredit', className: 'text-right', render: function(data, type, row, meta)
                    {
                        const formatRp = $.fn.dataTable.render.number( ".", ".", 0, 'Rp. ' ).display;
                        return formatRp(row.kredit) +
                        ` <input type="hidden" name="detail[${meta.row}][kredit]" value="${row.kredit}"> `;
                    },
                },
            ],
            "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            },
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // converting to interger to find total
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // computing column Total of the complete result
                var debitTotal = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var kreditTotal = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                // Update footer by showing the total with the reference of the column index
                var formatRp = $.fn.dataTable.render.number( ".", ".", 0, 'Rp. ' ).display;

                $('tr:eq(0) th:eq(3)', api.table().footer()).html(formatRp(debitTotal));
                $('tr:eq(0) th:eq(4)', api.table().footer()).html(formatRp(kreditTotal));
                $('tr:eq(1) th:eq(4)', api.table().footer()).html(formatRp(debitTotal - kreditTotal));
            },
            language: {
                lengthMenu: '{{ "Menampilkan _MENU_ data" }}',
                zeroRecords: '{{ "Data tidak ditemukan" }}',
                info: '{{ "_PAGE_ dari _PAGES_ halaman" }}',
                infoEmpty: '{{ "Data tidak ditemukan" }}',
                infoFiltered: '{{ "(Penyaringan dari _MAX_ data)" }}',
                loadingRecords: '{{ "Memuat data dari server" }}',
                processing: '{{ "Memuat data data" }}',
                search: '{{ "Pencarian:" }}',
                paginate: {
                    first: '{{ "<" }}',
                    last: '{{ ">" }}',
                    next: '{{ ">>" }}',
                    previous: '{{ "<<" }}'
                }
            },
        });

        $('#form-jurnal-invoice').formValidation({
            framework: "bootstrap4",
            button: {
                selector: "#btnSimpan",
                disabled: "disabled"
            },
            icon: null,
            fields: {
                tanggal: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom kode tidak boleh kosong'
                        }
                    }
                },
                no_dokumen: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom kode tidak boleh kosong'
                        }
                    }
                },
                keterangan: {
                    validators: {
                        notEmpty: {
                            message: 'Kolom kode tidak boleh kosong'
                        }
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
@endpush
