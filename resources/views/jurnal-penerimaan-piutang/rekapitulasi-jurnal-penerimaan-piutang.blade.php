@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Rekapitulasi Jurnal Penerimaan Piutang</h1>
</div>
@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
         
            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Kode BKM</th>
                    <th>No Kunjungan</th>
                    <th>Pasien</th>
                    <th>Total tagihan</th>
                    <th>Diskon</th>
                    <th>Pembayaran</th>
                    <th>Detail</th>
                </tr>
              
    <form action="{{ url('jurnal-penerimaan-piutang/jurnal-umum') }}" method="post">{{ @csrf_field() }} 

                @foreach ($rekapitulasi as $key =>$rekap)
                <input type="hidden" name="id_pembayaran[]" value="{{ $rekap->id_pembayaran}}">
                <tr>
                    <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                    <th>{{ $rekap->kode_bkm }}</th>
                    <th>{{ $rekap->no_kunjungan }}</th>
                    <th>{{ $rekap->nama }}</th>
                    <th>Rp. {{ number_format($rekap->total_tagihan) }}</th>
                    <th>{{ $rekap->diskon }}</th>
                    <th>Rp. {{ number_format($rekap->jumlah_bayar) }}</th>
                    <input type="hidden" name="id_pembayaran[]" value="{{$rekap->id_pembayaran}}">
                    <th><a href="detail/{{$rekap->id_pelanggan}}/{{$tanggal}}" class="btn btn-success">Detail</a></th>
                </tr>
                @endforeach
                   
                    <input type="hidden" name="id_bank" value="{{$id_bank}}">
                    <input type="hidden" name="tanggal" value="{{$tanggal}}">
                    <input type="hidden" name="tipe_pasien" value="{{$tipe_pasien}}">
                    <button type="submit" align="right" class="btn btn-primary">Buat Jurnal</button>
                </form>
                {{ $rekapitulasi->appends(request()->toArray())->links() }}
            </table>
        </div>
    </div>
</div>
@endsection