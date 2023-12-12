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

            <form action="{{ url('laporan-arus-kas') }}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-3">Tanggal Mulai</label>
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-calendar" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="tgl_mulai" id="tgl_mulai" class="form-control" data-plugin="datepicker" value="{{ $tgl_mulai }}" data-date-format="dd/mm/yyyy">
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
                            <input type="text" name="tgl_selesai" id="tgl_selesai" class="form-control" data-plugin="datepicker" value="{{ $tgl_selesai }}" data-date-format="dd/mm/yyyy">
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
            <div class="text-center">
                <h4>
                    {{-- RUMAH SAKIT x <br> --}}
                    LAPORAN ARUS KAS <br>
                    Tanggal {{ $tgl_mulai }} s/d {{ $tgl_selesai }}
                </h4>

                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left">Kode Perkiraan</th>
                            <th class="text-right">Perkiraan</th>
                            <th class="text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- URUTAN 1 --}}
                        <tr class="bg-grey-100 font-weight-bold text-uppercase">
                            <td colspan="3" class="text-left">{{ $urutan1->judul->nama }}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-left">Unsur Penambah</td>
                        </tr>
                        @foreach ($urutan1->unsur_penambah as $data)
                        <tr>
                            <td class="text-left">{{ $data->nama }}</td>
                            <td class="text-right">Rp. {{ number_format($data->nominal,2) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-left">Unsur Pengurang</td>
                        </tr>
                        @foreach ($urutan1->unsur_pengurang as $data)
                        <tr>
                            <td class="text-left">{{ $data->nama }}</td>
                            <td class="text-right">Rp. {{ number_format($data->nominal,2) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-left font-weight-bold" style="padding-left: 30px">Kas Bersih dari Aktivitas Operasional</td>
                            <td></td>
                            <td class="text-right">Rp. {{ number_format($urutan1->kas_bersih,2) }}</td>
                        </tr>

                        {{-- URUTAN 2 --}}
                        <tr class="bg-grey-100 font-weight-bold text-uppercase">
                            <td colspan="3" class="text-left">{{ $urutan2->judul->nama }}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-left">Unsur Penambah</td>
                        </tr>
                        @foreach ($urutan2->unsur_penambah as $data)
                        <tr>
                            <td class="text-left">{{ $data->nama }}</td>
                            <td class="text-right">Rp. {{ number_format($data->nominal,2) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-left">Unsur Pengurang</td>
                        </tr>
                        @foreach ($urutan2->unsur_pengurang as $data)
                        <tr>
                            <td class="text-left">{{ $data->nama }}</td>
                            <td class="text-right">Rp. {{ number_format($data->nominal,2) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-left font-weight-bold" style="padding-left: 30px">Kas Bersih dari Aktivitas Investasi</td>
                            <td></td>
                            <td class="text-right">Rp. {{ number_format($urutan2->kas_bersih,2) }}</td>
                        </tr>

                        {{-- URUTAN 3 --}}
                        <tr class="bg-grey-100 font-weight-bold text-uppercase">
                            <td colspan="3" class="text-left">{{ $urutan3->judul->nama }}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-left">Unsur Penambah</td>
                        </tr>
                        @foreach ($urutan3->unsur_penambah as $data)
                        <tr>
                            <td class="text-left">{{ $data->nama }}</td>
                            <td class="text-right">Rp. {{ number_format($data->nominal,2) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-left">Unsur Pengurang</td>
                        </tr>
                        @foreach ($urutan3->unsur_pengurang as $data)
                        <tr>
                            <td class="text-left">{{ $data->nama }}</td>
                            <td class="text-right">Rp. {{ number_format($data->nominal,2) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-left font-weight-bold" style="padding-left: 30px">Kas Bersih dari Aktivitas Pendanaan</td>
                            <td></td>
                            <td class="text-right">Rp. {{ number_format($urutan3->kas_bersih,2) }}</td>
                        </tr>

                        {{-- Kenaikan / Penurunan Kas --}}
                        <tr>
                            <td class="text-left font-weight-bold">Kenaikan / Penurunan Kas</td>
                            <td></td>
                            <td class="text-right">Rp. {{ number_format($kenaikanAtauPenurunanKas,2) }}</td>
                        </tr>

                        {{-- Saldo Awal Kas --}}
                        <tr>
                            <td class="text-left font-weight-bold">Saldo Awal Kas</td>
                            <td></td>
                            <td class="text-right">Rp. {{ number_format($saldoAwalKas,2) }}</td>
                        </tr>

                        {{-- Saldo Akhir Kas --}}
                        <tr>
                            <td class="text-left font-weight-bold">Saldo Akhir Kas</td>
                            <td></td>
                            <td class="text-right">Rp. {{ number_format($saldoAkhirKas,2) }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
