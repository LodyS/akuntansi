@extends('layouts.app')

@section('content')   

<style>
table 
{
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td 
{
  text-align: left;
  padding: 8px;
}

tr:nth-child(even)
{
    background-color: #f2f2f2
}
</style>


<div class="page-header">
    <h1 class="page-title">Rekapitulasi Pembayaran Hutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

<div style="overflow-x:auto;">
    <table class="table table-hover">

        <tr>
            <th>No</th>
            <th>Tanggal Pembelian</th>
            <th>No Faktur</th>
            <th>Supplier</th>
            <th>Total Pembelian</th>
            <th>Materai</th>
            <th>Diskon</th>
            <th>PPN</th>
            <th>Total Tagihan</th>
            <th>Pembayaran</th>
            <th>Sisa Hutang</th>
            <th>Detail</th>
            <th>Aksi</th>
        </tr>

        @php ($i=1)
        @foreach ($rekapitulasi as $key => $rekap)
            <tr>
                <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                <td>{{date('d-m-Y', strtotime($rekap->tanggal_pembelian))}}</td>
                <td>{{ $rekap->no_faktur }}</td>
                <td>{{ $rekap->nama }}</td> 
                <td>Rp. {{ number_format($rekap->total_pembelian,2) }}</td>
                <td>Rp. {{ number_format($rekap->materai,2)}}</td>
                <td>Rp. {{ number_format($rekap->diskon,2)}}</td>
                <td>Rp. {{ number_format($rekap->ppn,2)}}</td>
                <td>Rp. {{ number_format($rekap->total_tagihan,2) }}</td>
                <td>Rp. {{ number_format($rekap->pembayaran,2) }}</td>
                <td>Rp.. {{number_format($rekap->sisa_hutang,2) }}</td>
                <td><a href="laporan-angsuran/{{$rekap->id }}" class="btn btn-success">Detail</a></td>
                <td><a href="pembayaran/{{$rekap->id }}" class="btn btn-success">Bayar</a></td>
            </tr>
        @endforeach
        
    </table>
    {{ $rekapitulasi->appends(request()->toArray())->links() }}
   </div>
        </div>
    </div>
</div>
@endsection