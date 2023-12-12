@extends('layouts.app')

@section('content')
<style>
    .lebar {
        min-width: 200px;
        width: 200px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Penyusutan Aktiva Tetap</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <form action="{{ url('/simpan-penyusutan-aktiva-tetap')}}" method="post">{{ @csrf_field() }}

                <div class="form-group row d-none">
                    <label class="col-auto lebar">Urutan Penyusutan Selanjutnya : </label>
                    <div class="col">
                        @if ($urutan_penyusutan == null)
                        <input type="text" name="urutan_penyusutan" value="1" class="form-control" readonly>
                        @else
                        <input type="text" name="urutan_penyusutan" value="{{$urutan_penyusutan->urutan_penyusutan }}"
                            class="form-control" readonly>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Kelompok Aktiva Tetap</label>
                    <label class="col"> : {{ $aktiva->kelompok_aktiva}} </label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Aktiva Tetap</label>
                    <label class="col"> : {{ $aktiva->aktiva_tetap}} </label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Tanggal Pembelian</label>
                    <label class="col"> : {{date('d-m-Y', strtotime($aktiva->tanggal_pembelian))}}</label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Umur Ekonomis</label>
                    <label class="col"> : {{ $aktiva->umur_ekonomis}} Tahun</label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Harga Perolehan</label>
                    <label class="col"> : Rp. {{ number_format($aktiva->harga_perolehan) }}</label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Nilai Residu</label>
                    <label class="col"> : Rp. {{ number_format($aktiva->nilai_residu) }}</label>
                </div>

                <div class="row">
                    <div class="col">
                        <h3>Penyusutan</h3>
                    </div>
                </div>
                <div class="form-group row text-center">
                    <div class="col">
                        <label>{{ number_format($aktiva->harga_perolehan) }} -
                            {{ number_format($aktiva->nilai_residu) }}
                            <hr class="lebar">
                            <label>12</label>
                            <br>
                            <h4>{{ number_format($aktiva->tarif_penyusutan)}}</h4>
                    </div>
                </div>

                <div class="form-group row">
                    <input type="hidden" name="nilai_buku" value="{{ $nilai_buku }}">
                    <input type="hidden" name="tarif" value="{{ $aktiva->tarif_penyusutan }}">
                    <input type="hidden" name="id_aktiva_tetap" value="{{ $urutan_penyusutan->id_aktiva_tetap }}">
                    <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" align="right" class="btn btn-primary">Simpan Penyusutan</button>
            </form>
            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Penyusutan</th>
                    <th>Nilai Buku</th>
                </tr>
                <tbody>
                    @if (isset($laporan))
                    @php ($i=1)
                    @foreach ($laporan as $rekap)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $rekap->tahun }}</td>
                        <td>{{ $rekap->bulan }}</td>
                        <td>Rp. {{ number_format($rekap->penyusutan) }}</td>
                        <td>Rp. {{ number_format($rekap->nilai_buku) }}</td>
                    </tr>
                    @php($i++)
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Nilai Buku Selanjutnya</th>
                        <th>Rp. {{ number_format($nilai_buku) }}</th>
                    </tr>
                </tfoot>
            </table>


        </div>
    </div>
</div>
<div class=" modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>
@endsection
