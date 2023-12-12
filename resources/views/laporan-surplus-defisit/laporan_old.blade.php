@extends('layouts.app')

@section('content')

<style>
td {
  vertical-align: top;
}
td:nth-child(1) {
  min-width: 500px;
}

td:nth-child(2) {
  min-width: 200px;
}

td:nth-child(3) {
  min-width: 200px;
}

</style>

<div class="page-header">
    <h1 class="page-title" style="text-align:center">RS</h1>
    <h1 class="page-title" style="text-align:center">Laporan Surplus Defisit Periode :
    {{ date('d-m-Y', strtotime($tanggal_mulai)) }}/{{ date('d-m-Y', strtotime($tanggal_selesai)) }}</h1>
</div>
@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-hover" style="width:100%">
                            <tr>
                                <th>Rekening</th>
                                <th>Kode</th>
                                <th>Nominal</th>
                                <th>Nominal</th>
                            </tr>
                            @php ($pendapatanOperasionalDebet =0)
                            @foreach ($pendapatanOperasional as $po)
                            <tr>
                                <td>{{ $po->nama }}</td>
                                <td>{{ $po->kode }}</td>
                                <td>Rp. {{ number_format(abs($po->debet),2) }}</td>
                                <td>Rp. 0</td>
                            </tr>
                            @php ($pendapatanOperasionalDebet += $po->debet)
                            @endforeach

                            @foreach($anakPendapatanOperasional as $anakPo)
                            <tr>
                                <td>{{ $anakPo->nama }}</td>
                                <td>{{ $anakPo->kode }}</td>
                                <td>Rp. 0</td>
                                @if ($anakPo->debet == null)
                                <td>Rp. 0</td>
                                @else
                                <td>Rp. {{ number_format(abs($anakPo->debet),2) }}</td>
                                @endif
                            </tr>
                            @endforeach

                            <tr>
                                <td><b>II. {{ $bebanPokokPelayanan->nama }}</b></td>
                                <td>{{ $bebanPokokPelayanan->kode }}</td>
                                <td>Rp. {{ number_format(abs($bebanPokokPelayanan->debet),2) }}</td>
                                <td>Rp. 0</td>
                            </tr>

                            @foreach($anakBebanPokokPelayanan as $anakBpp)
                            <tr>
                                <td>{{ $anakBpp->nama }}</td>
                                <td>{{ $anakBpp->kode }}</td>
                                <td>Rp. 0</td>
                                <td>Rp. {{ number_format(abs($anakBpp->kredit),2) }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <td><b>Surplus/Defisit Bruto</b></td>
                                <td></td>
                                <?php
                                //dd($bebanPokokPelayanan);
                                   if (floatval($pendapatanOperasionalDebet) < floatval($bebanPokokPelayanan->debet))
                                    {
                                        $defisitBruto = $pendapatanOperasionalDebet - $bebanPokokPelayanan->debet;
                                    } else {
                                       $defisitBruto = $pendapatanOperasionalDebet + $bebanPokokPelayanan->debet;
                                    }
                                ?>
                                <td>Rp. {{ number_format($defisitBruto) }}</td>
                                <td>0</td>
                            </tr>

                            <tr>
                                <td><b>III. {{ $bebanAdministrasiUmum->nama }}</b></td>
                                <td>{{ $bebanAdministrasiUmum->kode }}</td>
                                <td>Rp. {{ number_format(abs($bebanAdministrasiUmum->debet),2) }}</td>
                                <td>Rp. 0</td>
                            </tr>

                            @foreach($anakBebanAdministrasiUmum as $anakBau)
                            <tr>
                                <td>{{ $anakBau->nama }}</td>
                                <td>{{ $anakBau->kode }}</td>
                                <td>Rp. 0</td>
                                <td>Rp. {{ number_format($anakBau->kredit) }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <td><b>Surplus/Defisit Operasional</b></td>
                                <td></td>
                                <?php
                                    if($defisitBruto < $bebanAdministrasiUmum->debet)
                                    {
                                        $defisitOperasional = floatval($defisitBruto) - floatval($bebanAdministrasiUmum->debet);
                                    } else {
                                        $defisitOperasional = floatval($defisitBruto) + floatval($bebanAdministrasiUmum->debet);
                                    }
                                ?>
                                <td>Rp. {{ number_format(abs($defisitOperasional),2) }}</td>
                                <td>Rp. 0</td>
                            </tr>

                            <tr>
                                <td><b>IV. {{ $pendapatanLainLain->nama }}</b></td>
                                <td>{{ $pendapatanLainLain->kode }}</td>
                                <td>Rp. {{ number_format(abs($pendapatanLainLain->debet),2) }}</td>
                                <td>Rp. 0</td>
                            </tr>

                            @foreach($anakPendapatanLainLain as $anakPl)
                            <tr>
                                <td>{{ $anakPl->nama }}</td>
                                <td>{{ $anakPl->kode }}</td>
                                <td>Rp. 0</td>
                                <td>Rp. {{ number_format($anakPl->kredit) }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <td><b>V. {{ $bebanLainLain->nama }}</b></td>
                                <td>{{ $bebanLainLain->kode }}</td>
                                <td>Rp. {{ number_format($bebanLainLain->debet) }}</td>
                                <td>Rp. 0</td>
                            </tr>

                            @foreach($anakBebanLainLain as $anakBbl)
                            <tr>
                                <td>{{ $anakBbl->nama }}</td>
                                <td>{{ $anakBbl->kode }}</td>
                                <td>Rp. 0</td>
                                <td>Rp. {{ number_format($anakBbl->kredit) }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <td><b>Selisih Pendapatan & Beban Lain-lain</b></td>
                                <td></td>
                                <?php
                                    if($pendapatanLainLain->debet < $bebanLainLain->debet)
                                    {
                                        $selisihPendapatanBebanLainLain = $pendapatanLainLain->debet + $bebanLainLain->debet;
                                    } else {
                                        $selisihPendapatanBebanLainLain =$pendapatanLainLain->debet + $bebanLainLain->debet;
                                    }
                                ?>
                                <td>Rp. {{ number_format($selisihPendapatanBebanLainLain,2)  }}</td>
                                <td>Rp. 0</td>
                            </tr>

                            <tr>
                                <td><b>Surplus/Defisit Sebelum Pajak</b></td>
                                <td></td>
                                <?php $defisitSebelumPajak = $defisitOperasional + $selisihPendapatanBebanLainLain; ?>
                                <td>Rp. {{ number_format($defisitSebelumPajak,2)  }}</td>
                                <td>Rp. 0</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
