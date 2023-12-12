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
    <h1 class="page-title">Summary Profit Center</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <form action="{{ url('summary-profit-center/pencarian') }}" method="POST" id="laporan">{{ @csrf_field() }}

                <div class="form-group row">
		            <label class="col-md-3">Bulan</label>
			            <div class="col-md-7">
			                <select name="bulan" class="form-control" required>
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
			                <select name="tahun" id="tahun" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="">Pilih Semua Tahun</option>
                            @for($i=2020; $i<2050; $i++)
                            <option value="{{ $i }}">{{ $i}} </option>
                            @endfor
                        </select>
		            </div>
	            </div>

            <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-search"></i>Cari</button><br/><br/>

            <h3 align="center">Summary Profit Center<br/>{{optional($setting)->nama}}<br/>
            periode {{ isset($tanggal_awal) ? $tanggal_awal : '' }} s/d {{ isset($tanggal_abis) ? $tanggal_abis : '' }}</h3>


            <div class="container">
                <div class="row">

                    <div style="overflow-x:auto;">
                        <table class="table table-hover" id="summary">
                            <tr height="50">
                                <td rowspan="2"><b>Deskripsi</b></td>
                                <td colspan="3"><b>Rajal</b></td>
                                <td colspan="3"><b>Ranap</b></td>
                                <td colspan="3"><b>Penunjang</b></td>
                                <td colspan="3"><b>Farmasi</b></td>
                                <td colspan="3"><b>Total</b></td>
                            </tr>

                            <tr height="50">
                                <!-- Rajal -->
                                <td>Target</td>
                                <td>Aktual</td>
                                <td>%Var</td>

                                <!-- Ranap -->
                                <td>Target</td>
                                <td>Aktual</td>
                                <td>%Var</td>

                                <!-- penunjang -->
                                <td>Target</td>
                                <td>Aktual</td>
                                <td>%Var</td>

                                <!-- Farmasi -->
                                <td>Target</td>
                                <td>Aktual</td>
                                <td>%Var</td>

                                <!-- total -->
                                <td>Target</td>
                                <td>Aktual</td>
                                <td>%Var</td>
                            </tr>

                            <tr>
                                <td>Pendapatan</td>
                                <!-- Rajal -->
                                <td>Rp. {{ number_format($pendapatanRajal->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($pendapatanRajal->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($pendapatanRajal == null) ? 0 : substr($pendapatanRajal->PERSEN_VARIANCE,0,5) }}%</td>

                                <!-- Ranap -->
                                <td>Rp. {{ number_format($pendapatanRanap->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($pendapatanRanap->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($pendapatanRanap == null) ? 0 : substr($pendapatanRanap->PERSEN_VARIANCE,0,5) }}%</td>

                                <!-- penunjang -->
                                <td>Rp. {{ number_format($pendapatanPenunjang->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($pendapatanPenunjang->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($pendapatanPenunjang == null) ? 0 :substr($pendapatanPenunjang->PERSEN_VARIANCE,0,5) }} %</td>

                                <!-- Farmasi -->
                                <td>Rp. {{ number_format($pendapatanFarmasi->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($pendapatanFarmasi->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($pendapatanFarmasi == null) ? 0 : substr($pendapatanFarmasi->PERSEN_VARIANCE,0,5) }} %</td>

                                <!-- total -->
                                <td>Rp. {{ number_format($total_target = $rajal_target + $ranap_target + $penunjang_target + $farmasi_target,2,",", ".") }}</td>
                                <td>Rp. {{ number_format($total_aktual = $rajal_aktual + $ranap_aktual + $penunjang_aktual + $farmasi_aktual,2, ",", ".") }}</td>
                                <?php $asatu = ($total_aktual-$total_target)/$total_target; ?>
                                <td>{{ substr($asatu,0,5) }} %</td>
                            </tr>

                            <tr>
                                <td>Beban Pokok Pelayanan (BPP)</td>
                                <!-- Rajal -->
                                <td>Rp. {{ number_format($bebanRajal->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($bebanRajal->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($bebanRajal == null) ? 0 : substr($bebanRajal->PERSEN_VARIANCE,0,5) }} %</td>

                                <!-- Ranap -->
                                <td>Rp. {{ number_format($bebanRanap->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($bebanRanap->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($bebanRanap == null) ? 0 : substr($bebanRanap->PERSEN_VARIANCE,0,5) }} %</td>

                                <!-- penunjang -->
                                <td>Rp. {{ number_format($bebanPenunjang->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($bebanPenunjang->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($bebanPenunjang == null) ? 0 : substr($bebanPenunjang->PERSEN_VARIANCE,0,5) }} %</td>

                                <!-- Farmasi -->
                                <td>Rp. {{ number_format($bebanFarmasi->TARGET,2, ",", ".") }}</b></td>
                                <td>Rp. {{ number_format($bebanFarmasi->AKTUAL,2, ",", ".")}}</td>
                                <td>{{ ($bebanFarmasi == null) ? 0 : substr($bebanFarmasi->PERSEN_VARIANCE,0,5) }} %</td>

                                <!-- total -->
                                <td>Rp. {{ number_format($total_beban_target = $beban_rajal_target + $beban_ranap_target + $beban_penunjang_target + $beban_farmasi_target,2, ",", ".") }}</td>
                                <td>Rp. {{ number_format($total_beban_aktual = $beban_rajal_aktual + $beban_ranap_aktual + $beban_penunjang_aktual + $beban_farmasi_aktual,2,",",".") }}</td>
                                <?php $adua =($total_beban_aktual-$total_beban_target)/$total_beban_target; ?>
                                <td>{{substr($adua,0,4) }} %</td>
                            </tr>

                            <tr>
                                <!--Rajal-->
                                @php ($marginRajalAnggaran = $pendapatanRajalAnggaran - $bebanRajalAnggaran)
                                <?php $marginRajalYtd = ($marginRajalAnggaran /12) *$bulan; ?>
                                @php ($marginRajalAktual = $pendapatanRajalAktual - $bebanRajalAktual)
                                @php ($marginRajalVariance = $marginRajalAktual - $marginRajalYtd )

                                <td>Margin Bruto/Surplus Kotor</td>
                                <td>Rp. {{ number_format($rajalTarget = $rajal_target - $beban_rajal_target,2,",",".")}}</td>
                                <td>Rp. {{ number_format($total_rajal_beban_aktual,2,",",".") }} %</td>
                                <td>{{ substr(isset($marginRajalYtd) && isset($marginRajalVariance) ? intval($marginRajalVariance/$marginRajalYtd) : '-100',0,5) }} %</td>

                                <!--Ranap-->
                                @if(isset($pendapatanRanapAnggaran))
                                @php ($marginRanapAnggaran = $pendapatanRanapAnggaran->anggaran - $bebanRanapAnggaran->anggaran)
                                <?php $marginRanapYtd = ($marginRajalAnggaran /12) * $bulan; ?>
                                @php ($marginRanapAktual = $pendapatanRanapAktual - $bebanRanapAktual)
                                @php ($marginRanapVariance = $marginRanapAktual - $marginRanapYtd )
                                @endif

                                <td>Rp. {{ number_format($ranapTarget = $ranap_target - $beban_ranap_target,2,",",".")}}</td>
                                <td>Rp. {{ number_format($ranapAktual = $ranap_aktual - $beban_ranap_aktual,2,",","." )}}</td>
                                <td>{{ substr(isset($marginRanapYtd) && isset($marginRanapVariance) ? intval($marginRanapVariance/$marginRanapYtd) : '-100',0,5) }} %</td>

                                <!-- penunjang -->
                                @if(isset($pendapatanPenunjangAnggaran))
                                @php ($marginPenunjangAnggaran = $pendapatanPenunjangAnggaran->anggaran - $bebanPenunjangAnggaran->anggaran)
                                <?php $marginPenunjangYtd = ($marginPenunjangAnggaran /12) * $bulan; ?>
                                @php ($marginPenunjangAktual = isset($pendapatanPenunjangAktual) && isset($bebanPenunjangAktual) ? $pendapatanPenunjangAktual  - $bebanPenunjangAktual : '0')
                                @php ($marginPenunjangVariance = $marginPenunjangAktual - $marginPenunjangYtd)
                                @endif

                                <td>Rp. {{ number_format($penunjangTarget = $penunjang_target - $beban_penunjang_target,2,",",".")}}</td>
                                <td>Rp. {{ number_format($penunjangAktual = $penunjang_aktual - $beban_penunjang_aktual,2,",","." )}}</td>
                                <td>{{ substr(isset($marginPenunjangYtd) && isset($marginPenunjangVariance) ? intval($marginPenunjangVariance/$marginPenunjangYtd) : '-100',0,5) }} %</td>

                                <!-- Farmasi -->
                                @if(isset($pendapatanFarmasiAnggaran))
                                @php ($marginFarmasiAnggaran = $pendapatanFarmasiAnggaran->anggaran - $bebanFarmasiAnggaran->anggaran)
                                <?php $marginFarmasiYtd = ($marginFarmasiAnggaran /12) * $bulan; ?>
                                @php ($marginFarmasiAktual = isset($pendapatanPenunjangAktual) && isset($bebanPenunjangAktual) ? $pendapatanPenunjangAktual  - $bebanPenunjangAktual : '0')
                                @php ($marginFarmasiVariance = $marginFarmasiAktual - $marginFarmasiYtd )
                                @endif

                                <td>Rp. {{ number_format($farmasiTarget = $farmasi_target - $beban_farmasi_target,2,",",".")}}</td>
                                <td>Rp. {{ number_format($farmasiAktual = $farmasi_aktual - $beban_farmasi_aktual,2,",","." )}}</td>
                                <td>{{ substr(isset($marginFarmasiYtd) && isset($marginFarmasiVariance) ? intval($marginFarmasiVariance/$marginFarmasiYtd) : '-100',0,5) }} %</td>

                                <!-- total -->
                                <td>Rp. {{ number_format($target = $total_target - $total_beban_target,2,",",".") }}</td>
                                <td>Rp. {{ number_format($aktual = $total_aktual - $total_beban_aktual,2,",",".") }}</td>
                                <td>{{ substr(($aktual - $target)/$target,0,5) }}%</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
