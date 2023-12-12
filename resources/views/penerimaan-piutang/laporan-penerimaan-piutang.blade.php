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


<div class="page-content">
    <div class="page-header">
        <h1 class="page-title">Laporan Penerimaan Piutang</h1>
        @include('layouts.inc.breadcrumb')
        <div class="page-header-actions">

        </div>
    </div>

     <!-- Panel Table Tools -->
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
                </div>
            </div>
        </header>

        <div class="panel-body">

            <form action="{{ url('simpan-penerimaan-piutang')}}" method="post">{{ @csrf_field() }}

                <div class="form-group row">
                    <label class="col-md-3">Cara Bayar</label>
                        <div class="col-md-7">
                            <select name="id_bank" class="form-control" id="id_bank" required>
                            <option value="">Pilih Bank</option>
                            @foreach ($KasBank as $bank)
                            <option value="{{$bank->id}}">{{$bank->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Tanggal</label>
                        <div class="col-md-7">
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                </div>

                <input type="hidden" name="kode_bkm_mutasi_kas" value="{{ isset($kode_mutasi_kas) ? $kode_mutasi_kas->kode : 'BKM-1' }}">
                <input type="hidden" class="form-control" name="tipe_pasien" value="{{ $tipe_pasien }}" readonly>

                <div style="overflow-x:auto;">
                    <table class="table table-hover table-striped w-full" id="hitung_total_setelah_diskon">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Kode BKM</td> 
                                <td>Tanggal</td>
                                <td>Pasien</td>
                                <td>Jasa</td>
                                <td>Penjualan</td>
                                <td>Total</td>
                                <td>Diskon</td>
                                <td>Total Setelah Diskon</td>
                                <td>Pembayaran</td>
                                <td>Pembayaran Jasa</td>
                                <td>Pembayaran Obat</td>
                                <td>Total Pembayaran</td>
                                <td>Klaim BPJS</td>
                                <td>Bayar Sendiri</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($i=1)
                            @php ($bkm = $MutasiKas->kode)
                            @foreach ($jasa as $laporan)
                            <tr>
                                <input type="hidden" class="form-control" name="id_pelanggan[]" value="{{ $laporan->id_pelanggan }}" readonly>
                                <input type="hidden" class="form-control" name="id_perkiraan[]" value="{{ $laporan->id_perkiraan }}" readonly>
                                <input type="hidden" class="form-control" name="id_tagihan[]" value="{{ $laporan->id_tagihan }}" readonly>
                                <input type="hidden" name="total[]" value="{{ $laporan->total}}" id="total" class="form-control" readonly>
                                <input type="hidden" class="form-control" name="no_kunjungan[]" value="{{ $laporan->no_kunjungan }}" readonly>
                                <input type="hidden" class="form-control" name="jenis" value="{{ $jenis }}" readonly>
                                <input type="hidden" class="form-control" name="kode_bkm[]" value="{{ 'BKM-'.$bkm }}" readonly>
                                <input type="hidden" class="form-control" name="tipe_pasien" value="{{ $tipe_pasien }}" readonly>

                                <td>{{ $i}}</td>
                                <td>{{ 'BKM-'.$bkm }}</td>
                                <td>{{ date('d-m-Y', strtotime($laporan->tanggal)) }}</td>
                                <td>{{ $laporan->pasien }}</td>
                                <td>Rp. {{ number_format($laporan->jasa) }}</td>
                                <td>Rp. {{ number_format($laporan->penjualan) }}</td>
                                <td>Rp. {{ number_format($laporan->total)}}</td>
                                <td><input type="number" id="diskon" name="diskon[]" class="form-control" width="25"></td>
                                <td><input type="text" id="total_setelah_diskon" name="total_setelah_diskon[]" class="form-control" readonly></td>
                                <td>Rp. {{ number_format($laporan->total_bayar) }}</td>
                                <td><input type="number" name="pembayaran_jasa[]" id="pembayaran_jasa" class="form-control"></td>
                                <td><input type="number" name="pembayaran_obat[]" id="pembayaran_obat" class="form-control"></td>
                                <td><input type="number" name="total_pembayaran[]" id="total_pembayaran" class="form-control" readonly></td>
                                <td><input type="number" name="klaim_bpjs[]" id="klaim_bpjs" width="25%" class="form-control"></td>
                                <td><input type="number" name="bayar_sendiri[]" id="bayar_sendiri" class="form-control"></td>
                            </tr>
                            @php($i++)
                            @php($bkm++)
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" id="submit" align="right" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('js')
<script type="text/javascript">

$("#id_bank").select2({
    width : '100%'
});

$(function () {
    $('#total', '#diskon, #total_setelah_diskon').prop('readonly', true);
    var $tblrows = $("#hitung_total_setelah_diskon tbody tr");

    $tblrows.each(function (index) {
    var $tblrow = $(this);
    $tblrow.find('#diskon').on('change', function () {

    var total = $tblrow.find("#total").val();
    var diskon = $tblrow.find("#diskon").val();
    var subTotal = parseInt(total, 10) - parseFloat(diskon);

        if (!isNaN(subTotal)) {
            $tblrow.find('#total_setelah_diskon').val(subTotal.toFixed(2));
        }});
    });
});

$(function () {
    $('#pembayaran_jasa', '#pembayaran_obat, #total_pembayaran').prop('readonly', true);
    var $tblrows = $("#hitung_total_setelah_diskon tbody tr");

    $tblrows.each(function (index) {
    var $tblrow = $(this);
    $tblrow.find('#pembayaran_obat').on('change', function () {

    var jasa = $tblrow.find("#pembayaran_jasa").val();
    var obat = $tblrow.find("#pembayaran_obat").val();
    var subTotal = parseInt(jasa) + parseInt(obat);

        if (!isNaN(subTotal)) {
            $tblrow.find('#total_pembayaran').val(subTotal.toFixed(2));
        }});
    });
});

$(function () {
    $('#klaim_bpjs', '#bayar_sendiri, #total_pembayaran').prop('readonly', true);
        var $tblrows = $("#hitung_total_setelah_diskon tbody tr");

    $tblrows.each(function (index) {
        var $tblrow = $(this);
        $tblrow.find('#bayar_sendiri').on('change', function () {

        var bpjs = $tblrow.find("#klaim_bpjs").val();
        var sendiri = $tblrow.find("#bayar_sendiri").val();
        var subTotal = parseInt(bpjs) + parseInt(sendiri);

        if (!isNaN(subTotal)) {
            $tblrow.find('#total_pembayaran').val(subTotal.toFixed(2));
        }});
    });
});
</script>
@endpush