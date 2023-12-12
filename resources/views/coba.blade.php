@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Coba Contoh</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
        {{-- <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#"
            onclick="show_modal('{{ route('pengeluaran-kas.create') }}')">Tambah</a> --}}
    </div>
</div>
<div class="page-content">

    {{-- panel form filter pencarian --}}
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-body">

                    <div class="form-group row">
                        <label class="col-md-3">Tanggal</label>
                        <div class="col-md">
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" name="start_date" id="start_date" value="{{ date('d/m/Y') }}">
                                <div class="input-group-addon">s/d</div>
                                <input type="text" class="form-control" name="end_date" id="end_date" value="{{ date('d/m/Y') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Select2</label>
                        <div class="col-md">
                            <select name="" id="" class="form-controll select2">
                                <option value="">Pilihan 1</option>
                                <option value="">Pilihan 2</option>
                                <option value="">Pilihan 3</option>
                                <option value="">Pilihan 4</option>
                                <option value="">Pilihan 5</option>
                                <option value="">Pilihan 6</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="panel-footer text-right">
                    <button type="button" class="btn btn-primary" id="cari">Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
    $(function () {

        $('.input-daterange input').each(function() {
            $(this).datepicker({
                format: "dd/mm/yyyy",
                autoclose: true
            });
        });

        $(".select2").select2({
            // dropdownParent: $("#pengeluaran-kas-form"),
            width: '100%'
        });


    });
</script>
@endpush
