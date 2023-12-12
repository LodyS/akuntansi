@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Detail Pembayaran</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
         Pasien : {{ $nama->pelanggan }}<br/><br/>
         Total Tagihan : Rp.{{number_format($pelanggan->total_tagihan)}}<br/> <br/>
         Total Pembayaran : Rp.{{number_format($pelanggan->total_pembayaran)}}<br/> <br/>

        <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No BKM</th>
                    <th>Pembayaran</th>
                    <th>Jenis</th>
                    <th>Cara Pembayaran</th>
                </tr>
                @php ($i=1)
                @foreach ($detail as $detil)
                <tr>
                    <th>{{$i}}</th>
                    <td>{{date('d-m-Y', strtotime($detil->waktu ))}}</td>
                    <td>{{$detil->kode_bkm}}</td>
                    <td>Rp. {{number_format($detil->total_bayar)}}</td>
                    <td>{{$detil->jenis}}</td>
                    <td>{{$detil->bank}}</td>
                </tr>
                @php($i++)
                @endforeach
            </table>
        </div>
    </div>
</div>
<div class=" modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>
@endsection