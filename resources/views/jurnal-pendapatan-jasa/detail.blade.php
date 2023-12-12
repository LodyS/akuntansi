@extends('layouts.app')

@section('content')

<style>
.canter {
  margin-left: auto;
  margin-right: auto;
}
</style>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <h1 class="page-title">Detail Rekapitulasi Pendapatan Jasa</h1>

        <div class="container">
  <div class="row">

<div class="page-header">
    
    <h5>Pasien &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $detail->pelanggan }}</h5>
    <h5>Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ date('d-m-y', strtotime($detail->tanggal)) }}</h5>
    <h5>Tipe Pembayaran &nbsp;&nbsp;&nbsp;&nbsp;: {{ $detail->tipe_bayar }}</h5>
    <h5>Jenis Pasien &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $detail->jenis }}</h5>
    <h5>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. {{ number_format($detail->total_tagihan) }}</h5>
  
  
</div>

<div class="page-header">
<h5>Biaya Administrasi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. {{ number_format($detail->adm) }}</h5>
    <h5>Biaya Kirim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. {{ number_format($detail->biaya_kirim )}}</h5>
    <h5>Cara pembayaran &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $detail->bank }}</h5>
    <h5>Pembayaran &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. {{ number_format($detail->pembayaran) }}</h5>
    <h5>Pembayaran dengan deposit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. {{ number_format($detail->deposit) }}</h5>
</div>

</div>
    </div>
</div>

<div class="col-md-6 center">
        <table class="table table-hover">
                <th>No</th>
                <th>Layanan</th>
                <th>Tarif</th>
            </tr>
            @php ($i=1)
            @foreach($detail_pendapatan_jasa as $data)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $data->layanan }}</td>
                <td>Rp. {{ number_format($data->tarif) }}</td>
            </tr>  
            @php($i++) 
            @endforeach
    </table>
    <!--<a href="jurnal-umum/{{$detail->id_pendapatan_jasa}}/{{$detail->id}}/{{ $detail->tanggal}}/{{ $detail->tipe_pembayaran}}/{{$detail->jenis}}" 
            class="btn btn-success">Buat Jurnal</a>-->
</div>

</div>
</div>

@endsection