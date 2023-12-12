@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Sinkronasi Data Payroll</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
    </div>
</div>

<div class="page-content">
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
                    <div>
                    </div>
                </div>
        </header>

        <div class="panel-body">
            @include('flash-message')
            <br /><br />

            <div class="form-group row">
                <div class="col-auto">
                    <input type="text" name="tanggal" id="tanggal" class="form-control text-center" autocomplete="off" readonly value="{{ date('d-m-Y') }}">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" id="btnSinkron">
                        <i class="icon glyphicon glyphicon-refresh" aria-hidden="true"></i>Sinkronasi Data
                    </button>
                </div>
            </div>
            <table class="table table-hover dataTable table-striped w-full" id="tbl_transaksi_history"></table>
            {{-- {{ $data->appends(request()->toArray())->links() }} --}}


            <br /><br />
            <button type="button" class="btn btn-success" id="btnSimpan">Simpan</button>
        </div>
    </div>
</div>

<div id="modal_koreksi" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Payroll</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover dataTable table-striped w-full">
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="bodyKomponen"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-primary btn-radius">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')
    <script type="text/javascript">
        // event click tombol detail
        function btnDetail(params) {
            const url = `{{ url('sinkronasi-data-payroll/get-data-payroll-detail') }}`;
            $.get(url, params,
                function (data, textStatus, jqXHR) {
                    // console.log(data);
                    const response = data.data;
                    let html = '';
                    if (response) {
                        html += `
                            <tr>
                                <td>Pajak</td>
                                <td>${new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(response.Pajak)}</td>
                            </tr>
                        `;
                        response.Detail.forEach(element => {
                            html += `
                                <tr>
                                    <td>${element.Komponen}</td>
                                    <td>${new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(element.Nominal)}</td>
                                </tr>
                            `;
                        });
                    }
                    $('#bodyKomponen').html(html);
                    $('#modal_koreksi').modal('show');
                },
                "json"
            );
        }

        $(document).ready(function () {
            $('#tanggal').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            var table = $('#tbl_transaksi_history').DataTable({
                scrollX: true,
                info: false,
                ajax: {
                    url: "{{ url('/sinkronasi-data-payroll/get-data-payroll') }}",
                    data : function (d) {
                        d.tanggal_transaksi = $('#tanggal').val();
                    }
                },
                // rowsGroup: [0,1,2,3,4,5,6,7,8,9],
                columns: [
                    {title: 'No', data: null,className:'text-center','sortable': false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    {title: 'Unit', data: 'Nama Unit'},
                    {title: 'Tanggal', data: 'Tanggal_Transaksi'},
                    {title: 'Keterangan', data: 'Keterangan'},
                    {title: 'No. Rekening', data: 'Nomor_Rekening'},
                    {title: 'Pemilik Rekening', data: 'Pemilik_Rekening'},
                    {title: 'Total Tagihan', data: 'Total_Tagihan', render: $.fn.dataTable.render.number( '.', ',', 2 )},
                    {title: 'Biaya Adm Bank', data: 'Biaya_Adm_Bank', render: $.fn.dataTable.render.number( '.', ',', 2 )},
                    // {title: 'Pajak', data: 'Pajak', render: $.fn.dataTable.render.number( '.', ',', 2 )},
                    {title: 'Total Uang Diterima', data: 'Total_Uang_Diterima', render: $.fn.dataTable.render.number( '.', ',', 2 )},
                    // {title: 'Komponen', data: 'Komponen'},
                    // {title: 'Nominal', data: 'Nominal', render: $.fn.dataTable.render.number( '.', ',', 2 )},
                    {title: 'Aksi', data:null, className: 'text-center','sortable': false, render: function (data) {
                        const params = JSON.stringify(data,['Kode_Referal','Tanggal_Transaksi']);
                        return `<button class='btn btn-sm btn-primary btn-circle' onclick='btnDetail(${params})'>Detail</button>`;
                    } },
                ]
            });

            $('#btnSinkron').click(function (e) {
                e.preventDefault();
                const tgl = $('#tanggal').val();
                table.ajax.reload();
            });

            $('#btnSimpan').click(function (e) {
                e.preventDefault();
                const btn = $(this);
                btn.html('Loading..').prop('disabled',true);

                const tableData = table.rows().data().toArray();
                $.post("/sinkronasi-data-payroll/sinkron-payroll", {data: tableData},
                    function (data, textStatus, jqXHR) {
                        notification(data.message, data.type);
                        btn.html('Simpan').prop('disabled',false);
                    },
                    "json"
                );
            });
        });
    </script>
@endpush
