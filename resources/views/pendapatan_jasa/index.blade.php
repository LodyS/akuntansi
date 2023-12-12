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
    <h1 class="page-title">Pendapatan Jasa </h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions"></div>
</div>
<div class="page-content">
    <div class="panel">
        <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">

                </div>
            </div>
        </header>

        <div class="panel-body">

            <form action="{{ url('simpan-pendapatan-jasa')}}" method="post" id="pendapatan_jasa">{{ @csrf_field() }}

                <div class="form-group row">
                    <label class="col-md-3">No Transaksi</label>
                    <div class="col-md-7">
                        <input name="no_bukti_transaksi" id="no_bukti_transaksi"
                            value="{{ isset($bukti_transaksi) ? $bukti_transaksi->bukti_transaksi : 'PJ-1' }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Kode Mutasi Kas</label>
                    <div class="col-md-7">
                        <input name="kode_mutasi_kas" value="{{ isset($MutasiKas) ? $MutasiKas->kode : 'BKM-1' }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">No Kunjungan</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="no_kunjungan" id="no_kunjungan" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Pasien</label>
                    <div class="col-md-7">
                        <input type="text" name="nama_pasien" id="nama" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">ID Pasien</label>
                    <div class="col-md-7">
                        <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" readonly >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Tanggal</label>
                    <div class="col-md-7">
                        <input type="date" name="tanggal" value="{{ date('Y-m-d')}}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Tipe Pembayaran</label>
                    <div class="col-md-7">
                        <select name="tipe_pembayaran" class="form-control" id="tipe_pembayaran" required>
                            <option value="">Pilih Tipe Bayar</option>
                            <option value="Kredit">Kredit</option>
                            <option value="Tunai">Tunai</option>
                        </select>
                    </div>
                </div>

                @if ($periodeKeuangan == null)
                <div class="form-group row">
                    <label class="col-md-3">Periode Keuangan</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" value="Periode Keuangan Kosong" readonly>
                    </div>
                </div>
                @else
                <div class="form-group row">
                    <label class="col-md-3">Periode Keuangan</label>
                    <div class="col-md-7">
                        <input type="hidden" name="id_periode" value="{{ $periodeKeuangan->id }}">
                        <input type="text" class="form-control"
                            value="{{date('d-m-Y', strtotime($periodeKeuangan->tanggal_awal))}} s/d {{date('d-m-Y', strtotime($periodeKeuangan->tanggal_akhir))}}"
                            readonly>
                    </div>
                </div>
                @endif

                <div class="form-group row">
                    <label class="col-md-3">Cara Bayar</label>
                    <div class="col-md-7">
                        <select name="id_bank" id="id_bank" class="form-control">
                            <option value="">Pilih Bank</option>
                            @foreach ($bank as $b)
                            <option value="{{ $b->id}}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Biaya Charge</label>
                        <div class="col-md-7">
                        <input type="text" name="biaya_charge" id="get_biaya_charge" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Biaya Adm</label>
                        <div class="col-md-7">
                        <input type="text" name="biaya_adm" id="get_biaya_adm" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Biaya Materai</label>
                        <div class="col-md-7">
                        <input type="text" name="biaya_materai" id="get_biaya_materai" class="form-control">
                    </div>
                </div>

                {{-- <div class="form-group row">
                    <label class="col-md-3">Biaya Kirim</label>
                    <div class="col-md-7">
                        <input type="text" name="biaya_kirim" id="get_biaya_kirim" class="form-control">
                    </div>
                </div> --}}

                <div class="form-group row">
                    <label class="col-md-3">Total</label>
                        <div class="col-md-7">
                        <input type="text" id="get_total_rupiah" class="form-control" readonly>
                        <input type="hidden" name="total" id="get_total" class="form-control" readonly required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Dibayar</label>
                        <div class="col-md-7">
                        <input type="text" id="bayar" name="dibayar" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Pembayaran Menggunakan Deposit</label>
                        <div class="col-md-7">
                        <input type="text" id="bayar_deposit_rupiah" class="form-control">
                        <input type="hidden" name="bayar_deposit" id="bayar_deposit" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Sisa Bayar</label>
                        <div class="col-md-7">
                        <input type="text" class="form-control" id="sisa_bayar_rupiah" readonly>
                        <input type="hidden" class="form-control" name="sisa_bayar" id="sisa_bayar" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Jenis Pasien</label>
                        <div class="col-md-7">
                            <select name="jenis" class="form-control" id="jenis" required>
                            <option value="">Pilih Jenis Pasien</option>
                            <option value="RJ">Rawat Jalan</option>
                            <option value="RI">Rawat Inap</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Asuransi</label>
                        <div class="col-md-7">
                        <input type="radio" name="asuransi" id="Ya" value="1" onClick="javascript:showForm()"><label>Ya</label>
                        <input type="radio" name="asuransi" id="penyusutan" id="Tidak" value="0" onClick="javascript:showForm()"><label>Tidak</label>
                    </div>
                </div>

                <div id="tampil" style="display:none" class="none">
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Asuransi</label>
                        <div class="col-md-7">
                            <select name="id_asuransi" id="id_asuransi" class="form-control">
                            <option value="">Pilih Asuransi</option>
                            @foreach ($asuransi as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                    <div class="form-group row">
                        <label class="col-md-3">Perusahaan</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control" name="perusahaan">
                        </div>
                    </div>

                </div>
                <button class="btn btn-dark" type="button" id="add">
                <i class="icon glyphicon glyphicon-plus" aria-hidden="true"></i>Tambah</button></button>

                <div style="overflow-x:auto;">
                    <table class="table table-hover" style="display:block" class="block" id="tambah_form" width="100%">
                        <tr>
                            <th>Layanan</th>
                            <th>Unit</th>
                            <th>Nakes 1</th>
                            <th>Nakes 2</th>
                            <th>Nakes 3</th>
                            <th>Tarif</th>
                        </tr>

                        <tr>
                        </tr>
                    </table>
                </div>

                <button type="submit" id="submit" align="right" class="btn btn-primary">
                <i class="icon glyphicon glyphicon-save" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $('#id_bank').select2({
        width: '100%'
    });

    $('#id_asuransi').select2({
        width: '100%'
    });

function formatRupiah(number) {
  return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('#get_biaya_charge').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#get_biaya_adm').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#get_biaya_materai').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#bayar').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$(document).on('change keyup', "#bayar, #bayar_deposit", "#get_total", function () {
        var val1 = $("#get_total").val()
        var val2 = $("#bayar").val()
        var val3 = $("#bayar_deposit").val()

        var total =  Number(val1.replace(/[^0-9.-]+/g,""));
        var bayar =  Number(val2.replace(/[^0-9,-]+/g,""));
        var deposit = Number(val3.replace(/[^0-9,-]+/g,""));
        console.log(bayar);
        var result = total - bayar - deposit
        $("#sisa_bayar_rupiah").val(formatRupiah(result))
        $("#sisa_bayar").val(result)
    }); // menghitung sisa tagihan

    $(document).ready(function () {

        function hitungGrandtotal() {
                    var grandTotal = 0;
                    const charge = $('#get_biaya_charge').val();
                    const adm = $('#get_biaya_adm').val();
                    const materai = $('#get_biaya_materai').val();
                    // const kirim = $('#get_biaya_kirim').val();

                    const charge_rupiah = Number(charge.replace(/[^0-9,-]+/g,""));
                    const adm_rupiah = Number(adm.replace(/[^0-9,-]+/g,""));
                    const materai_rupiah = Number(materai.replace(/[^0-9,-]+/g,""));

                    grandTotal += parseFloat(charge_rupiah)
                    grandTotal += parseFloat(adm_rupiah)
                    grandTotal += parseFloat(materai_rupiah)
                    // grandTotal += parseFloat(kirim)

                    $('.tarif').each(function () {
                        var stval = $(this).val();
                        var tariff = Number(stval.replace(/[^0-9,-]+/g,""));
                        grandTotal += tariff;
                    });
                    $('#get_total_rupiah').val(formatRupiah(grandTotal)).change();
                    $('#get_total').val(grandTotal).change(); // untuk menghitung penjumlahan agregat tarif

                    $('#bayar').change();
                }

        $("#get_biaya_charge, #get_biaya_adm, #get_biaya_materai").on('change keyup',function () {
            hitungGrandtotal();
        });

        var i = 0


        $('#add').click(function () {
            i++;
            $('#tambah_form').append('<tr id="row' + i + '"><td><select class="form-control select" name="id_layanan[]" id="id_layanan-' + i + '">\n\
                        <option value="">Pilih Layanan</option>\n\
                        @foreach ($tarif as $l)<option value="{{ $l->id }}">{{ $l->layanan }} | {{$l->kelas }}</option>@endforeach\n\
                    </select>\n\
                    </td>\n\
                    <td width="15%"><select class="form-control select" name="id_unit[]" id="unit-' + i + '">\n\
                        <option value="">Pilih Unit</option>\n\
                        @foreach ($unit as $un)<option value="{{ $un->id }}">{{ $un->nama }}</option> @endforeach\n\
                        </select>\n\
                    </td>\n\
                    <td><select class="form-control select" name="id_nakes_1[]" id="nakes_1-' + i + '">\n\
                        <option value="">Pilih Nakes 1</option>\n\
                            @foreach ($nakes as $nak)<option value="{{ $nak->id }}">{{ $nak->nama }}</option> @endforeach\n\
                        </select>\n\
                    </td>\n\
                    <td><select class="form-control select" name="id_nakes_2[]" id="nakes_2-' + i + '"><option value="">Pilih Nakes 2</option>\n\
                            @foreach ($nakes as $nak)<option value="{{ $nak->id }}">{{ $nak->nama }}</option> @endforeach\n\
                    <td><select class="form-control select" name="id_nakes_3[]" id="nakes_3-' + i + '"><option value="">Pilih Nakes 3</option>\n\
                            @foreach ($nakes as $nak)<option value="{{ $nak->id }}">{{ $nak->nama }}</option> @endforeach\n\
                    </select></td>\n\
                    <td><input type="text" id="tarif-' + i + '" name="tarif[]" class="form-control tarif" readonly></td>\n\
                    <input type="hidden" id="jasa_sarana-' + i + '" name="jasa_sarana[]" class="form-control">\n\
                    <input type="hidden" id="bhp-' + i + '" name="bhp[]" class="form-control">\n\
                    <input type="hidden" id="jasa_medis-' + i + '" name="jasa_medis[]" class="form-control">\n\
                    <input type="hidden" id="jasa_rs-' + i + '" name="jasa_rs[]" class="form-control">\n\
                    <input type="hidden" id="alkes-' + i + '" name="alkes[]" class="form-control">\n\
                    <input type="hidden" id="kr-' + i + '" name="kr[]" class="form-control">\n\
                    <input type="hidden" id="ulup-' + i + '" name="ulup[]" class="form-control">\n\
                    <input type="hidden" id="adm-' + i + '" name="adm[]" class="form-control">\n\
                    <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove btn-round">\n\
                    <i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i></button></button></td></tr>');

$('#tarif-'+i).on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
}));

            // $(function () {

                $('#id_layanan-' + i, '#tarif-' + i, '#get_total').prop('readonly', true);
                var $tblrows = $("#tambah_form tbody tr");

                $tblrows.each(function (index) {
                    var $tblrow = $(this);
                    $tblrow.find('#id_layanan-' + i).on('change keyup', function () {
                        hitungGrandtotal();
                    });
                });
            // });

            $('#id_layanan-' + i).select2({
                width: '100%'
            });

            $('#unit-' + i).select2({
                width: '100%'
            });

            $('#nakes_1-' + i).select2({
                width: '100%'
            });

            $('#nakes_2-' + i).select2({
                width: '100%'
            });

            $('#nakes_3-' + i).select2({
                width: '100%'
            });

            $('#id_layanan-' + i).change(function () {
                var id_layanan = $(this).val();
                var url = '{{ route("isiTarif", ":id_layanan") }}';
                url = url.replace(':id_layanan', id_layanan); // untuk mengirim request ajax ke controller untuk mendapat tarif

                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        if (response != null) {
                            $('#tarif-' + i).val(formatRupiah(response.tarif));
                            $('#jasa_sarana-' + i).val(response.jasa_sarana);
                            $('#bhp-' + i).val(response.bhp);
                            $('#jasa_medis-' + i).val(response.jasa_medis);
                            $('#jasa_rs-' + i).val(response.jasa_rs);
                            $('#alkes-' + i).val(response.alkes);
                            $('#kr-' + i).val(response.kr);
                            $('#ulup-' + i).val(response.ulup);
                            $('#adm-' + i).val(response.adm);
                            hitungGrandtotal();
                        }
                    }
                });
            });
        }); // untuk mendapat tarif sesuai dengan input dropdown layanan + kelas

        $(document).on('click', '.btn_remove', function () {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
            hitungGrandtotal();
        }); // untuh hapus form dinamis


        $('#pendapatan_jasa').submit(function (e){
            const tipePembayaran    = $('#tipe_pembayaran');
            const totalTagihan      = $("#get_total");
            const deposit           = $('#bayar_deposit');
            const dibayar           = $("#bayar");
            const sisaBayar         = $("#sisa_bayar");

            if (tipePembayaran.val() == 'Tunai' && totalTagihan.val() > deposit.val() && dibayar.val() < (totalTagihan.val() - deposit.val())){
                notification("Maaf deposit anda tidak cukup, Silahkan menambah nilai pembayaran. Terimakasih.","error");
                dibayar.focus();
                return false;
            }

            if (tipePembayaran.val() == 'Tunai' && totalTagihan.val() < deposit.val()){
                notification("Maaf pembayaran anda melebihi tagihan, anda dapat mengurangi nilai pembayaran dan deposit. Terimakasih.","error");
                deposit.focus();
                return false;
            }

            if (tipePembayaran.val() == 'Tunai' && sisaBayar.val() != 0) {
                notification("Maaf pembayaran harus sesuai dengan total tagihan. Terimakasih.","error");
                dibayar.focus();
                return false;
            }

            if (tipePembayaran.val() == 'Kredit' && parseFloat(sisaBayar.val()) != totalTagihan.val()) {
                notification("Maaf sisa bayar harus sesuai dengan total tagihan. Terimakasih.","error");
                dibayar.focus();
                return false;
            }
        });


        $('#tipe_pembayaran').change(function (e) {
            const dibayar = $('#bayar');
            const deposit = $('#bayar_deposit');
            if (e.currentTarget.value == 'Kredit'){
                dibayar.val(0).prop('readOnly',true);
                deposit.val(0).prop('readOnly',true);
                dibayar.change();
            } else if (e.currentTarget.value == 'Tunai') {
                dibayar.prop('readOnly',false);
                deposit.prop('readOnly',false);
                const kunjungan = $('#no_kunjungan');
                kunjungan.change();
                dibayar.change();
            }
        });



    });

    function showForm() {
        if (document.getElementById('Ya').checked) {
            document.getElementById('tampil').style.display = 'block';
        } else {
            document.getElementById('tampil').style.display = 'none';
        }
    } //untuk menampilkan dan menyembunyikan kolom asuransi

    $('#no_kunjungan').change(function () {
        var no_kunjungan = $(this).val();
        var url = '{{ route("isiPasien", ":no_kunjungan") }}';
        url = url.replace(':no_kunjungan', no_kunjungan);

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            async: false,
            success: function (response) {
                if (response != null) {
                    if (response.flag_discharge == 'Y') {
                        swal('Warning','Maaf transaksi tidak bisa di lakukan karena pasien sudah pulang rawat. Terimakasih','warning')
                    } else {
                        $('#nama').val(response.nama);
                        $('#id_pelanggan').val(response.id_pelanggan);
                        $('#bayar_deposit').val(response.deposit);
                        $('#bayar_deposit_rupiah').val(formatRupiah(response.deposit));
                    }
                }
            }
        });
    });

</script>
@endpush
