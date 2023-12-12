@extends('layouts.app')

@section('content')
<style>
    .lebar{
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
                    <label class="col-auto lebar">Urutan Penyusutan Selanjutnya</label>
                    <div class="col">
                        <input type="text" name="urutan_penyusutan" value="1" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Kelompok Aktiva Tetap</label>
                    <label class="col"> : {{ optional($aktiva)->kelompok_aktiva}} </label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Aktiva Tetap</label>
                    <label class="col"> : {{ optional($aktiva)->aktiva_tetap}} </label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Tanggal Pembelian</label>
                    <label class="col"> : {{date('d-m-Y', strtotime(optional($aktiva)->tanggal_pembelian))}}</label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Umur Ekonomis</label>
                    <label class="col"> : {{ optional($aktiva)->umur_ekonomis}} Tahun</label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Harga Perolehan</label>
                    <label class="col"> : Rp. {{ number_format($harga_perolehan,2, ",", ".") }}</label>
                </div>

                <div class="form-group row">
                    <label class="col-auto lebar">Nilai Residu</label>
                    <label class="col"> :  Rp. {{ number_format(optional($aktiva)->nilai_residu,2, ",", ".") }}</label>
                </div>

                <div class="row">
                    <div class="col">
                        <h3 align="center">Penyusutan</h3>
                    </div>
                </div>
                <div class="form-group row text-center">
                    <div class="col">
                        <label>{{ number_format(optional($aktiva)->harga_perolehan,2, ",", ".") }} - {{ number_format(optional($aktiva)->nilai_residu,2,",", ".") }}
                        <hr class="lebar">
                        <label>12</label>
                        <br>
                        <h4>{{ number_format($tarif_penyusutan,2, ",", ".")}}</h4>
                    </div>
                </div>

                <input type="hidden" name="tarif" value="{{ $tarif_penyusutan }}">
                <input type="hidden" name="nilai_buku" value="{{ $nilai_buku }}">
                <input type="hidden" name="id_aktiva_tetap" value="{{ $id }}">
                <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <input type="hidden" name="bulan" value="{{ $bulan }}">

                <button type="submit" align="right" class="btn btn-primary">Simpan Penyusutan</button>

                <table class="table table-hover">
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Penyusutan</th>
                            <th>Nilai Buku</th>
                        </tr>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ (isset($aktiva)) ? date('Y', strtotime($aktiva->tanggal_pembelian)) : date(strtotime('Y-m-d')) }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{number_format($harga_perolehan,2, ",", ".")}}</td>
                        </tr>

                        @foreach ($penyusutan as $key => $item)
                            <tr>
                                <td>{{ $key + 2 }}</td>
                                <td>{{ date('Y', strtotime($item->tanggal_penyusutan)) }}</td>
                                <td>{{ $item->urutan_penyusutan }}</td>
                                <td>{{ number_format($item->nominal,2, ",", ".") }}</td>
                                <td>{{ number_format($item->nilai_buku,2,",", ".") }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Nilai Buku Selanjutnya</th>
                            <th>{{ number_format($nilai_buku,2, ",", ".") }}</th>
                        </tr>
                    </tfoot>
                </table>
            </form>

        </div>
    </div>
</div>
@endsection
