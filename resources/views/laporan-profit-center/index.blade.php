@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Laporan Profit Center</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

        <form action="{{ url('laporan-profit-center/pencarian') }}" method="POST">{{ @csrf_field() }}

        <div class="form-group row">
			<label class="col-md-3">Anggaran Profit</label>
				<div class="col-md-7">
					<select name="id" class="form-control">
					<option value="">Pilih Anggaran Profit</option>
					@foreach ($anggaranProfit as $anggaran)
					<option value="{{ $anggaran->id }}">{{ $anggaran->nama }}</option>
					@endforeach
                </select>
			</div>
		</div>

        <div class="form-group row">
            <label class="col-md-3">Bulan</label>
                <div class="col-md-7">
                    <select name="bulan" class="form-control select" required>
                    <option value="">Pilih</option>
                    <option value="1">Januari</option>
                    <option value="2">Febuari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Tahun</label>
                <div class="col-md-7">
                <select name="tahun" id="tahun" class="form-control select" required>
                <option value="">Pilih</option>
                <option value="">Pilih Semua Tahun</option>
                @for($i=2020; $i<2050; $i++)
                <option value="{{ $i }}">{{ $i}} </option>
                @endfor
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Cari</button>
        <h3 align="center">Profit Center<br/>{{ optional($anggaran)->nama }}<br/>{{optional($setting)->nama}}<br/>
        periode {{ isset($tanggal_awal) ? $tanggal_awal : '' }} s/d {{ isset($tanggal_abis) ? $tanggal_abis : '' }}</h3>

        <table class="table table-hover" id="laporan-perubahan-ekuitas">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Anggaran</th>
                    <th>Persentanse</th>
                    <th>Anggaran YTD</th>
                    <th>% to Pdpt</th>
                    <th>Aktual YTD</th>
                    <th>% to Pdpt</th>
                    <th>Variance</th>
                    <th>% Variance</th>
                </tr>

                @if(isset($totalPendapatan))
                <tr>
                    <td><b>1. Pendapatan Usaha</b></td>
                    <td><b>Rp. {{ number_format($pendapatanAnggaran->anggaran) }}</b></td>
                    <td><b>{{ $pendapatanAnggaran->anggaran/$sumAnggaran->sum_anggaran }}</b></td>
                    <td>Rp. {{ number_format($anggaranYdtPendapatan= ($pendapatanAnggaran->anggaran/12)*$bulan)}}</td>
                    <td>{{ (($anggaranYdtPendapatan/12)*$bulan)/$sumAnggaran->sum_anggaran }}%</td>
                    <td>Rp. {{ number_format($aktualPendapatan * $akrual) }}</td>
                    <td>{{ $aktualPendapatan *$akrual/ $sumAnggaran->sum_anggaran }}%</td>
                    <td>Rp. {{ number_format($aktualPendapatan *$akrual -$anggaranYdtPendapatan) }}</td>
                    <td>{{ $aktualPendapatan   *$akrual}}</td>
                </tr>

                @foreach($pendapatan as $lap)
                <tr>
                    <td>{{ $lap->KETERANGAN }}</td>
                    <td>Rp. {{ number_format($lap->anggaran) }}</td>
                    <td>{{ $lap->persen }}%</td>
                    <td>Rp. {{ number_format($lap->anggaran_ytd) }}</td>
                    <td>{{ $lap->persen_to_pendapatan }} %</td>
                    <td>Rp. {{ number_format($lap->AKTUAL_YDT_FIX)}}</td>
                    <td>{{ $lap->persen_to_pend  }}%</td>
                    <td>Rp. {{ number_format($lap->variance) }}</td>
                    <td>{{ intval($lap->variance_persen) }} %</td>
                </tr>
                @endforeach

                <tr>
                    <td><b>2. Beban Usaha</b></td>
                    <td><b>Rp. {{ number_format($bebanAnggaran->anggaran) }}</b></td>
                    <td><b>100 %</b></td>
                    <td>Rp. {{ number_format($anggaranYdtBeban= ($bebanAnggaran->anggaran/12)*$bulan)}}</td>
                    <?php $pdt = ($anggaranYdtBeban == 0) && ($bebanAnggaran->anggaran ==0) ? 0: intval(($anggaranYdtBeban/$bebanAnggaran->anggaran)*100); ?>
                    <td>{{ $pdt }}%</td>

                    <td>Rp. {{ number_format($totalBebanAktual) }}</td>
                    <?php $pdtt = ($totalBebanAktual ==0) && ($bebanAnggaran->anggaran ==0) ? 0 : intval(($totalBebanAktual/$bebanAnggaran->anggaran) *100)?>
                    <td>{{ $pdtt }}%</td>
                    <td>Rp. {{ number_format($totalBebanAktual - $anggaranYdtBeban) }}</td>
                    <td>100 %</td>
                </tr>

                @foreach($beban as $lap)
                <tr>
                    <td>{{ $lap->keterangan }}</td>
                    <td>Rp. {{ number_format($lap->anggaran) }}</td>
                    <td>{{ $lap->PERSEN }}%</td>
                    <td>Rp. {{ number_format($lap->anggaran_ytd) }}</td>
                    <td>{{ $lap->persen_to_pendapatan }} %</td>
                    <td>Rp. {{ number_format($lap->AKTUAL_YDT_FIX)}}</td>
                    <td>{{ $lap->persen_to_pend  }}%</td>
                    <td>Rp. {{ number_format($lap->variance) }}</td>
                    <td>{{ intval($lap->variance_persen) }} %</td>
                </tr>
                @endforeach

                <tr>
                    <td><b>3. Margin Bruto</b></td>
                    @php ($marginAnggaran = $pendapatanAnggaran->anggaran - $bebanAnggaran->anggaran)
                    <td><b>Rp.  {{ number_format($marginAnggaran) }}</b></td>
                    <?php $per = ($marginAnggaran ==0) &&($pendapatanAnggaran->anggaran ==0) ? 0: intval($marginAnggaran/$pendapatanAnggaran->anggaran)*100;?>
                    <td>{{ $per }} %</td>
                    <td>Rp. {{ number_format(($marginYtd = $marginAnggaran /12) *$bulan) }} </td>
                    <?php $pdt = ($marginAnggaran ==0) && ($pendapatanAnggaran->anggaran ==0) ? 0 :  intval($marginAnggaran/$pendapatanAnggaran->anggaran) *100?>
                    <td>{{ $pdt }} %</td>

                    <td>Rp.{{ number_format(($marginAktual = $totalPendapatanAktual - $totalBebanAktual))}} </td>

                    <?php $pdtt = ($marginAktual ==0) && ($pendapatanAnggaran->anggaran==0) ? 0: intval($marginAktual/$pendapatanAnggaran->anggaran) *100?>
                    <td>{{ $pdtt  }}%</td>
                    <td>Rp. {{ number_format(($marginVariance = $marginAktual - $marginYtd ))}} </td>
                    <?php $pdttt = ($marginVariance==0) && ($marginYtd==0) ?0 : intval($marginVariance/$marginYtd) ; ?>
                    <td>{{ isset($marginYtd) && isset($marginVariance) ? $pdttt : '-100' }} %</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

