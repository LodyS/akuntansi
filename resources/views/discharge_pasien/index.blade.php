@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Discharge Pasien</h1>
</div>
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ url('discharge_pasien/rekapitulasi' )}}" method="POST" id="discharge-pasien">
            {{ @csrf_field() }}
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3">Tanggal Mulai</label>
                            <div class="col-md-9">
                                <input name="tanggal_mulai" id="start_date" class="form-control" type="date">
                           
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3">Tanggal Selesai</label>
                            <div class="col-md-9">
                                <input name="tanggal_selesai" id="start_date" class="form-control" type="date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3">Pasien</label>
                            <div class="col-md-9">
                                <input type="text" name="pasien" id="pasien" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3"></label>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-primary float-left" style="margin-top: 15px;" id="cari">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection