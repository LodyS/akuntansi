@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Penyusutan Aktiva Tetap</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('jurnal-penyusutan-aktiva-tetap/rekapitulasi')}}" method="POST">{{ @csrf_field() }}

	<div class="form-group row">
		<label class="col-md-3">Aktiva Tetap</label>
			<div class="col-md-7">
			<select name="aktiva_tetap" id="aktiva_tetap" class="form-control select">
                <option value="">Pilih</option>
                <option value="">Pilih Semua Aktiva</option>
                @foreach($aktivaTetap as $aktiva)
                <option value="{{ $aktiva->id}}">{{ $aktiva->nama }}</option>
                @endforeach
            </select>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Bulan</label>
			<div class="col-md-7">
			<select name="bulan" id="bulan" class="form-control select">
                <option value="">Pilih</option>
                <option value="">Pilih Semua Bulan</option>
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
			<select name="tahun" id="tahun" class="form-control select">
                <option value="">Pilih</option>
                <option value="">Pilih Semua Tahun</option>
                @for($i=2020; $i<2050; $i++)
                <option value="{{ $i }}">{{ $i}} </option>
                @endfor
            </select>
		</div>
	</div>

        <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button>
        </form>
        </div>



        <table class="table table-hover" id="rekapitulasi-penyusutan">
            <tr>
                <th>No</th>
                <th>Aktiva</th>
                <th>Nominal</th>
                <th>Biaya Penyusutan</th>
                <th>Akumulasi Penyusutan</th>
            </tr>

            @foreach ($data as $key=> $rekap)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $rekap->aktiva }}</td>
                <td>Rp. {{ number_format($rekap->nominal,2, ",", ".") }}</td>
                <td>{{ $rekap->biaya_penyusutan }}</td>
                <td>{{ $rekap->akumulasi_penyusutan }}</td>
            </tr>

            @endforeach

        </table>
        <form action="{{ url('jurnal-penyusutan-aktiva-tetap/jurnal') }}" method="POST">{{ @csrf_field() }}
        <input type="hidden" name="bulan" value="{{ isset($bulan) ? $bulan : '' }}">
        <input type="hidden" name="tahun" value="{{ isset($tahun) ? $tahun : '' }}">
        <input type="hidden" name="aktiva_tetap" value="{{ isset($aktiva_tetap) ? $aktiva_tetap : '' }}">
        <button type="submit" align="right" class="btn btn-primary">Buat Jurnal</button>
        </form>


        </div>
    </div>
</div>

    </div>
</div>


@endsection
