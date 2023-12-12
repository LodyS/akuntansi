@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Laporan Profit & Loss</h1>
    @include('layouts.inc.breadcrumb')
</div>
<div class="page-content">
    <!-- Panel Table Tools -->
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
                </div>
            </div>
        </header>

        <div class="panel-body">
            <form action="{{ url('laporan-surplus-defisit/laporan') }}" method="POST">
                @csrf
                <br>
                <div class="form-group row">
                    <label class="col-md-3">Tanggal Awal</label>
                    <div class="col-md-7">
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Tanggal Akhir</label>
                    <div class="col-md-7">
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>
                </div>

                <button type="submit" align="right" class="btn btn-primary" id="cari">Tampilkan</button>

            </form>
        </div>
    </div>
</div>
@endsection
