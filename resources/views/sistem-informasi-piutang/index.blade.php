@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Sistem Informasi Piutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

				<table class="table table-hover">

                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Keterangan</th>
                        <th>Saldo Piutang</th>
                        <th>Rekening Kontrol</th>
                        <th>Status Aktif</th>
                        <th>Mutasi</th>
                        <th>Detail</th>
                        <th>Saldo Awal</th>
                    </tr>
                    @php ($i=1)
                    @foreach ($pelanggan as $pasien)
                    <tr>
                        <td>{{ $i}}</td>
                        <td>{{ $pasien->pelanggan }}</td>
                        <td>{{ $pasien->keterangan }}</td>
                        <td>Rp. {{ number_format($pasien->saldo_piutang,2, ",",".") }}</td>
                        <td>{{ $pasien->rekening_kontrol }}</td>
                        <td> @if ( $pasien->flag_aktif == 'Y')
                        <a class="btn btn-success btn-xs btn-round" style="color:white; font-family:Arial"
                        data-toggle="tooltip" data-original-title="Aktif">
                        <i class="icon glyphicon glyphicon-ok" aria-hidden="true"></i>Aktif</a>
                        @else
                        <a class="btn btn-danger btn-xs btn-round" style="color:white; font-family:Arial"
                        data-toggle="tooltip" data-original-title="Tidak Aktif">
                        <i class="icon glyphicon glyphicon-ok" aria-hidden="true"></i>Tidak Aktif</a>
                        @endif
                        </td>
                        <td><a class="btn btn-primary btn-xs btn-round"  data-toggle="tooltip" data-original-title="Mutasi"
                        href="sistem-informasi-piutang/mutasi-piutang/{{ $pasien->id }}">
                            <i class="icon glyphicon glyphicon-transfer" aria-hidden="true"></i>Mutasi</a></td>
                        <td><a class="btn btn-success btn-xs btn-round" href="sistem-informasi-piutang/detail-piutang/{{ $pasien->id }}"
                        data-toggle="tooltip" data-original-title="Detail"><i class="icon glyphicon glyphicon-list" aria-hidden="true"></i></a></td>
                        <td><a class="btn btn-danger btn-xs btn-round" href="sistem-informasi-piutang/tambah-saldo/{{ $pasien->id }}"
                        data-toggle="tooltip" data-original-title="Tambah Saldo">
                        <i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i></a></td>
                    </tr>
                    @php($i++)
                    @endforeach
                </table>
                {{ $pelanggan->links() }}
        </div>
    </div>
</div>
<div class=" modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>
@endsection
