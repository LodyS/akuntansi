@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Arus Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">Parameter Pencarian</h3>
        </div>
        <div class="panel-body">

            <form action="{{ url('laporan-arus-kas/pencarian') }}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-3">Tanggal Mulai</label>
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-calendar" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="tanggal_mulai" id="tgl_mulai" class="form-control" data-plugin="datepicker" value="{{ isset($tanggal_mulai) ? $tanggal_mulai : '' }}" data-date-format="dd/mm/yyyy">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Tanggal Selesai</label>
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-calendar" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="tanggal_selesai" id="tgl_selesai" class="form-control" data-plugin="datepicker" value="{{ isset($tanggal_selesai) ? $tanggal_selesai : '' }}" data-date-format="dd/mm/yyyy">
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-primary">Cari</button>
                </div>
            </form>

        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <!--<div class="text-center">-->
                <h4 align="center">
                    LAPORAN ARUS KAS<br/>
                    Tanggal {{ isset($tanggal_mulai) ? $tanggal_mulai : '' }} s/d {{ isset($tanggal_selesai) ? $tanggal_selesai : '' }}
                </h4>

                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Saldo</th>
                        <th>Total</th>
                    </tr>
                    @php ($i=1)
                    @php ($total_saldo=0)
                    @php ($total=0)
                    @foreach ($data as $rekap)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $rekap->nama}}</td>
                        <td>Rp.{{ number_format($rekap->saldo) }}</td>
                        <td>Rp. {{ number_format($rekap->total) }}</td>
                    </tr>
                    @php($i++)
                    @php ($total_saldo += $rekap->saldo)
                    @php ($total += $rekap->total)
                    @endforeach

                    <tr>
                        <td><b>Total : </b></td>
                        <td></td>
                        <td>Rp.{{ number_format($total_saldo) }}</td>
                        <td>Rp. {{ number_format($total) }}</td>
                    </tr>
                </table>
            <!--</div>-->
        </div>
    </div>
</div>

@endsection
