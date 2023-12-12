@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tutup Buku tanggal : {{ date('d-m-Y') }}</h1>
</div>

@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        @include('flash-message')
            <form action="{{ url('pindah-perkiraan/pencarian') }}" method="POST" id="laporan">{{ @csrf_field() }}

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
                <button type="submit" align="right" class="btn btn-dark"><i class="icon glyphicon glyphicon-search"></i>Cari</button>
            </form>


            <form action="{{ url('/proses-konversi')}}" method="post">{{ @csrf_field() }}
                <input type="hidden" name="status" value="{{ isset($status) ? 'Ada' : 'Tidak Ada' }}">
		            <table class="table table-hover">
                        <tr>
                            <th>No</th>
                            <th>Account</th>
                            <th>COST</th>
                            <th>Chart Of Account</th>
                            <th>Keterangan</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                        </tr>
                        @if (isset($data))

                        @foreach ($data as $key=>$rekap)
                        <tr>
                            <td>{{ ++$key}}</td>
                            <td>{{ $rekap->kode_rekening }}</td>
                            @if (substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 1 ||
                                substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 2 ||
                                substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 3)

                            <td>NER</td>
                            @else
                            <td>{{ $rekap->code_cost_centre }}</td>
                            @endif

                            @if(substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 1 ||
                            substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 2 ||
                            substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 3)
                            <td>{{ $rekap->kode_rekening }}</td>
                            @else
                            <td>{{ $rekap->code_cost_centre }} - {{ $rekap->kode_rekening }}</td>
                            @endif
                            <input type="hidden" name="id_perkiraan[]" value="{{ $rekap->id_perkiraan }}">
                            <td><input type="hidden" name="id_unit[]" value="{{ $rekap->id_unit }}">{{ $rekap->unit }} - {{ $rekap->perkiraan }}</td>
                            <td><input type="hidden" name="debet[]" value="{{ $rekap->debet }}">Rp. {{ number_format($rekap->debet,2, ",", ".") }}</td>
                            <td><input type="hidden" name="kredit[]" value="{{ $rekap->kredit }}">Rp. {{ number_format($rekap->kredit,2, ",", ".") }}</td>

                        </tr>
                        @endforeach

                        <tr>
                            <td><b>Total</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Rp. {{ number_format($total_debet,2, ",", ".") }}</b></td>
                            <td><b>Rp. {{ number_format($total_kredit,2, ",", ".") }}</b></td>
                        </tr>
                    @endif
                </table>
            <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-save"></i>Tutup Buku</button>
        </div>
    </div>
</div>
@endsection
