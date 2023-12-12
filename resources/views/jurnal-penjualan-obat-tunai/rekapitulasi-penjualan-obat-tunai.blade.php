@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Rekapitulasi Penjualan Obat</h1>
    <h5>Tanggal : {{date('d-m-Y', strtotime($tanggal))}}</h5>
    <h5>Tipe Obat : {{ isset($tipe_obat) ? $tipe_obat : 'Tidak ada jenis obat' }}</h5>
    <h5>Jenis Pasien : {{ isset($jenis_pasien) ? $jenis_pasien : 'Tidak ada jenis pasien' }}</h5>
    <h5>Tipe Pasien : {{ isset($tipe_pasien) ? $tipe_pasien->tipe_pasien : 'Tidak ada tipe pasien'}}</h5>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
    <table class="table table-hover">

        <tr>
            <th>No</th>
            <th>Bukti Penjualan</th>
            <th>No Kunjungan</th>
            <th>Pasien</th>
            <th>Total Penjualan</th>
            <th>Diskon</th>
            <th>Pajak</th>
            <th>Cara Bayar</th>
            <th>Perkiraan</th>
            <th>Buat Jurnal</th>
        </tr>

        @foreach ($rekapitulasi as $key => $rekap)
            <tr>
                <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                <td>{{ $rekap->bukti_penjualan }}</td>
                <td>{{ $rekap->no_kunjungan }}</td>
                <td>{{ $rekap->pasien }}</td>
                <td>Rp. {{ number_format($rekap->total_penjualan,2) }}</td>
                <td>Rp. {{ number_format($rekap->diskon,2) }}</td>
                <td>Rp. {{ number_format($rekap->pajak,2) }}</td>
                <td>{{ $rekap->cara_bayar }}</td>
                <td>{{ $rekap->perkiraan }}</td>
                <td><a href="jurnal-umum/{{$rekap->id_penjualan}}/{{ $tipe_pasien ? $tipe_pasien->id : null }}" class="btn btn-outline-primary">Buat Jurnal</td>
            </tr>
        @endforeach
    </table>
{{ $rekapitulasi->appends(request()->toArray())->links() }}

            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){

    $(".select").select2({
        dropdownParent : $("#rekapitulasi"),
        width : '100%'
    });
});
</script>
@endpush
