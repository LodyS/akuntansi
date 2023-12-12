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
    <h1 class="page-title">Laporan Jurnal Umum</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <form action="{{ url('laporan-jurnal-umum/laporan')}}" method="POST" id="laporan">{{ @csrf_field() }}

        <div class="form-group row">
            <label class="col-md-3">Jenis Jurnal</label>
                <div class="col-md-7">
                    <select name="tipe_jurnal" id="tipe_jurnal" class="form-control select btn-round">
                    <option value="">Pilih</option>
                    @foreach($tipeJurnal as $jurnal)
                    <option value="{{ $jurnal->id}}">{{ $jurnal->tipe_jurnal }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Status</label>
                <div class="col-md-7">
                    <select name="status" id="status" class="form-control select btn-round">
                    <option value="">Pilih</option>
                    <option value="1">Pending</option>
                    <option value="2">Terverifikasi</option>
                    <option value="3">Batal</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Tanggal Mulai</label>
                <div class="col-md-7">
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ date('Y-m-d')}}" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3">Tanggal Selesai</label>
                <div class="col-md-7">
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ date('Y-m-d')}}" class="form-control">
            </div>
        </div>

        <p>Bisa memilih tanggal dari 1 desember 2021 untuk parameter tanggal pada menu laporan</p>

        <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button><br/>

        <h3 align="center">LAPORAN JURNAL UMUM<br/>
        {{ optional($setting)->nama }}<br/>
        tanggal {{ isset($tanggal_mulai) ? date('d-m-Y', strtotime($tanggal_mulai)) : '' }} s/d {{ isset($tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_selesai)) : ''}}</h4>
        <br/>
        <div style="overflow-x:auto;">
            <table class="table table-hover" id="laporan-jurnal-umum">
                <thead>
                    <tr>
                        <th>Tanggal Posting</th>
                        <th>Keterangan</th>
                        <th>Kode Jurnal</th>
                        <th>Kode Rekening</th>
                        <th>Keterangan</th>
                        <th>Code Cost Centre</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Status</th>
                        <th>Cetak</th>

                        <th>Edit</th>
                        <th>Detail</th>

                    </tr>
                    @if(isset($rekapitulasi))

                    @php ($jurnal_kode = null)

                    @foreach ($rekapitulasi as $rekap)
                        @if ($loop->index > 0 && $jurnal_kode != $rekap->kode_jurnal)
                            @include ('laporan-jurnal-umum/balance', compact('rekapitulasi', 'jurnal_kode'))
                        @endif

                    <tr>
                    @php ($jurnal_kode = $rekap->kode_jurnal)


                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? date('d-m-Y', strtotime($rekap->tanggal_posting)) : '' }}</b></td>
                        @elseif ($rekap->layer ==1 && $rekap->urutan==1 )
                            <td><b>{{ date('d-m-Y', strtotime($rekap->tanggal_posting))   }}</b></td>
                        @else
                        <td></td>
                        @endif


                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? $rekap->keterangan : '' }}</b></td>
                        @elseif ($rekap->layer ==1 &&  $rekap->urutan==1 )
                            <td><b>{{ $rekap->keterangan  }}</b></td>
                        @else
                        <td></td>
                        @endif

                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? $rekap->kode_jurnal : '' }}</b></td>
                        @elseif ($rekap->layer !== null && $rekap->layer ==1 && $rekap->urutan !== null && $rekap->urutan==1 )
                            <td><b>{{ $rekap->kode_jurnal  }}</b></td>
                        @else
                        <td></td>
                        @endif


                        <td>{{ $rekap->kode_rekening }}</td>
                        <td>{{ $rekap->nama }} - {{ $rekap->unit }}</td>
                        <td>{{ $rekap->code_cost_centre }}</td>
                        <td>Rp. {{ number_format($rekap->debet,2, ",", ".") }}</td>
                        <td>Rp. {{ number_format($rekap->kredit,2, ",", ".") }}</td>


                        <!-- Verifikasi -->
                        @if($rekap->layer == null && $rekap->urutan== null && $rekap->status==1 && $rekap->urutin ==0)
                        <td><button type="button" class="btn btn-warning btn-round">Pending</button></td>
                        @elseif($rekap->layer !== null && $rekap->urutan !== null && $rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==1)
                        <td><button type="button" class="btn btn-warning btn-round">Pending</button></td>
                        @elseif($rekap->layer == null && $rekap->urutan== null && $rekap->status==2 && $rekap->urutin ==0)
                        <td><button type="button" class="btn bg-green-400 btn-round">Terverifikasi</button></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==2)
                        <td><button type="button" class="btn bg-green-400 btn-round">Terverifikasi</button></td>
                        @elseif($rekap->layer == null && $rekap->urutan== null  && $rekap->status===3 && $rekap->urutin ==0)
                        <td><button type="button" class="btn btn-danger btn-round">Batal</button></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==3)
                        <td><button type="button" class="btn btn-danger btn-round">Batal</button></td>
                        @elseif ($rekap->layer == null && $rekap->urutan== null  &&  $rekap->urutin ==0)
                        <td><button type="button" class="btn btn-dark btn-round">Tidak diketahui</button></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==null)
                        <td><button type="button" class="btn btn-danger btn-round">Batal</button></td>
                        @else
                        <td></td>
                        @endif

                        <!-- cetak -->

                        @if($rekap->layer == null && $rekap->urutan== null && $rekap->status==1 && $rekap->urutin ==0)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer !== null && $rekap->urutan !== null && $rekap->layer==1 && $rekap->urutan==1 )
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>

                        @elseif($rekap->layer == null && $rekap->urutan== null && $rekap->status==2 && $rekap->urutin ==0)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==2)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer == null && $rekap->urutan== null  && $rekap->status===3 && $rekap->urutin ==0)

                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==3)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>

                        @elseif ($rekap->layer == null && $rekap->urutan== null  &&  $rekap->urutin ==0)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==null)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @else
                        <td></td>
                        @endif

                        <!-- Edit -->
                        @if($rekap->urutan == '1' && $rekap->layer == 1 && $rekap->status !==2)
                        <td><a href="edit/{{$rekap->id}}" class="btn btn-danger btn-round"><i class="icon glyphicon glyphicon-edit" aria-hidden="true"></i>Edit</a></td>
                        @elseif($rekap->status !==2 && $rekap->urutan == null && $rekap->layer == null && $rekap->urutin ==0)
                        <td><a href="edit/{{$rekap->id}}" class="btn btn-danger btn-round"><i class="icon glyphicon glyphicon-edit" aria-hidden="true"></i>Edit</a></td>
                        @else
                        <td></td>
                        @endif


                        <!-- Detail -->
                        <td>
                        @if($rekap->urutan == '1' && $rekap->layer == 1)
                        <a href="{{ url('laporan-jurnal-umum/detail', $rekap->id) }}" class="btn btn-info btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Detail</a>
                        @elseif($rekap->urutan == null && $rekap->layer == null && $rekap->urutin ==0)
                        <a href="{{ url('laporan-jurnal-umum/detail', $rekap->id) }}" class="btn btn-info btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Detail</a>
                        @endif


                        @if($rekap->id_tipe_jurnal === 3 && $rekap->urutan == '1' && $rekap->layer == 1)
                        <a href="{{ url('mutasi-penerimaan-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @elseif($rekap->id_tipe_jurnal === 3 && $rekap->urutan == null && $rekap->layer == null && $rekap->urutin ==0)
                        <a href="{{ url('mutasi-penerimaan-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @endif

                        @if($rekap->id_tipe_jurnal === 4 && $rekap->urutan == '1' && $rekap->layer == 1)
                        <a href="{{ url('mutasi-pengeluaran-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @elseif($rekap->id_tipe_jurnal === 4 && $rekap->urutan == null && $rekap->layer == null && $rekap->urutin==0)
                        <a href="{{ url('mutasi-pengeluaran-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @else
                        <td></td>
                        @endif
                    </tr>
                    @if ($loop->last)
                        @include ('laporan-jurnal-umum/balance', compact('rekapitulasi', 'jurnal_kode'))
                    @endif

                    @endforeach

                    <tr>
                        <td><b>Total</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($totalDebet,2, ",", ".") }}</b></td>
                        <td><b>Rp. {{ number_format($totalKredit,2, ",", ".") }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><b>Balance</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($balance,2, ",", ".") }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif

                </table>
                <button type="button" align="right" class="btn btn-dark" id="excel"><i class="icon glyphicon glyphicon-file"></i>Excel</button>
                <button class="btn btn-danger print-link no-print" id="print"><i class="icon glyphicon glyphicon-print"></i>Cetak</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"
integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous"
referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>

<script type="text/javascript">
$(".select").select2({
    dropdownParent : $("#laporan"),
    theme: 'bootstrap4',
    width : '100%'
});

$("#excel").click(function () {
    $("#laporan-jurnal-umum").table2excel({
        filename: "laporan-jurnal-umum.xls"
    });
});

        $(document).ready(function () {
            $('#print').click(function (e) {
                e.preventDefault();
                $('.page-print').print({
                    noPrintSelector: ".no-print",
                    prepend : generateHeader()
                })
            });
        });

        function generateHeader () {
            return `

            <h3 align="center">LAPORAN JURNAL UMUM<br/>
        {{ optional($setting)->nama }}<br/>
        tanggal {{ isset($tanggal_mulai) ? date('d-m-Y', strtotime($tanggal_mulai)) : '' }} s/d {{ isset($tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_selesai)) : ''}}</h4>
        <br/>
        <div style="overflow-x:auto;">
            <table class="table table-hover" id="laporan-jurnal-umum">
                <thead>
                    <tr>
                        <th>Tanggal Posting</th>
                        <th>Keterangan</th>
                        <th>Kode Jurnal</th>
                        <th>Kode Rekening</th>
                        <th>Keterangan</th>
                        <th>Code Cost Centre</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Status</th>
                        <th>Cetak</th>

                        <th>Edit</th>
                        <th>Detail</th>

                    </tr>
                    @if(isset($rekapitulasi))

                    @php ($jurnal_kode = null)

                    @foreach ($rekapitulasi as $rekap)
                        @if ($loop->index > 0 && $jurnal_kode != $rekap->kode_jurnal)
                            @include ('laporan-jurnal-umum/balance', compact('rekapitulasi', 'jurnal_kode'))
                        @endif

                    <tr>
                    @php ($jurnal_kode = $rekap->kode_jurnal)


                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? date('d-m-Y', strtotime($rekap->tanggal_posting)) : '' }}</b></td>
                        @elseif ($rekap->layer ==1 && $rekap->urutan==1 )
                            <td><b>{{ date('d-m-Y', strtotime($rekap->tanggal_posting))   }}</b></td>
                        @else
                        <td></td>
                        @endif


                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? $rekap->keterangan : '' }}</b></td>
                        @elseif ($rekap->layer ==1 &&  $rekap->urutan==1 )
                            <td><b>{{ $rekap->keterangan  }}</b></td>
                        @else
                        <td></td>
                        @endif

                        @if ($rekap->layer == null && $rekap->urutan == null)
                            <td><b>{{ ($rekap->urutin ==0) ? $rekap->kode_jurnal : '' }}</b></td>
                        @elseif ($rekap->layer !== null && $rekap->layer ==1 && $rekap->urutan !== null && $rekap->urutan==1 )
                            <td><b>{{ $rekap->kode_jurnal  }}</b></td>
                        @else
                        <td></td>
                        @endif


                        <td>{{ $rekap->kode_rekening }}</td>
                        <td>{{ $rekap->nama }} - {{ $rekap->unit }}</td>
                        <td>{{ $rekap->code_cost_centre }}</td>
                        <td>Rp. {{ number_format($rekap->debet,2, ",", ".") }}</td>
                        <td>Rp. {{ number_format($rekap->kredit,2, ",", ".") }}</td>


                        <!-- Verifikasi -->
                        @if($rekap->layer == null && $rekap->urutan== null && $rekap->status==1 && $rekap->urutin ==0)
                        <td><button type="button" class="btn btn-warning btn-round">Pending</button></td>
                        @elseif($rekap->layer !== null && $rekap->urutan !== null && $rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==1)
                        <td><button type="button" class="btn btn-warning btn-round">Pending</button></td>
                        @elseif($rekap->layer == null && $rekap->urutan== null && $rekap->status==2 && $rekap->urutin ==0)
                        <td><button type="button" class="btn bg-green-400 btn-round">Terverifikasi</button></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==2)
                        <td><button type="button" class="btn bg-green-400 btn-round">Terverifikasi</button></td>
                        @elseif($rekap->layer == null && $rekap->urutan== null  && $rekap->status===3 && $rekap->urutin ==0)
                        <td><button type="button" class="btn btn-danger btn-round">Batal</button></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==3)
                        <td><button type="button" class="btn btn-danger btn-round">Batal</button></td>
                        @elseif ($rekap->layer == null && $rekap->urutan== null  &&  $rekap->urutin ==0)
                        <td><button type="button" class="btn btn-dark btn-round">Tidak diketahui</button></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==null)
                        <td><button type="button" class="btn btn-danger btn-round">Batal</button></td>
                        @else
                        <td></td>
                        @endif

                        <!-- cetak -->

                        @if($rekap->layer == null && $rekap->urutan== null && $rekap->status==1 && $rekap->urutin ==0)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer !== null && $rekap->urutan !== null && $rekap->layer==1 && $rekap->urutan==1 )
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>

                        @elseif($rekap->layer == null && $rekap->urutan== null && $rekap->status==2 && $rekap->urutin ==0)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==2)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer == null && $rekap->urutan== null  && $rekap->status===3 && $rekap->urutin ==0)

                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==3)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>

                        @elseif ($rekap->layer == null && $rekap->urutan== null  &&  $rekap->urutin ==0)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @elseif($rekap->layer==1 && $rekap->urutan==1 && $rekap->status ==null)
                        <td><a href="cetak/{{$rekap->kode_jurnal}}/{{$tanggal_mulai}}/{{$tanggal_selesai}}" class="btn btn-green-400 btn-round"><i class="icon glyphicon glyphicon-print" aria-hidden="true"></i>Print</a></td>
                        @else
                        <td></td>
                        @endif

                        <!-- Edit -->
                        @if($rekap->urutan == '1' && $rekap->layer == 1 && $rekap->status !==2)
                        <td><a href="edit/{{$rekap->id}}" class="btn btn-danger btn-round"><i class="icon glyphicon glyphicon-edit" aria-hidden="true"></i>Edit</a></td>
                        @elseif($rekap->status !==2 && $rekap->urutan == null && $rekap->layer == null && $rekap->urutin ==0)
                        <td><a href="edit/{{$rekap->id}}" class="btn btn-danger btn-round"><i class="icon glyphicon glyphicon-edit" aria-hidden="true"></i>Edit</a></td>
                        @else
                        <td></td>
                        @endif


                        <!-- Detail -->
                        <td>
                        @if($rekap->urutan == '1' && $rekap->layer == 1)
                        <a href="{{ url('laporan-jurnal-umum/detail', $rekap->id) }}" class="btn btn-info btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Detail</a>
                        @elseif($rekap->urutan == null && $rekap->layer == null && $rekap->urutin ==0)
                        <a href="{{ url('laporan-jurnal-umum/detail', $rekap->id) }}" class="btn btn-info btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Detail</a>
                        @endif


                        @if($rekap->id_tipe_jurnal === 3 && $rekap->urutan == '1' && $rekap->layer == 1)
                        <a href="{{ url('mutasi-penerimaan-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @elseif($rekap->id_tipe_jurnal === 3 && $rekap->urutan == null && $rekap->layer == null && $rekap->urutin ==0)
                        <a href="{{ url('mutasi-penerimaan-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @endif

                        @if($rekap->id_tipe_jurnal === 4 && $rekap->urutan == '1' && $rekap->layer == 1)
                        <a href="{{ url('mutasi-pengeluaran-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @elseif($rekap->id_tipe_jurnal === 4 && $rekap->urutan == null && $rekap->layer == null && $rekap->urutin==0)
                        <a href="{{ url('mutasi-pengeluaran-kas/bukti-transaksi', $rekap->id) }}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>Lihat Voucher</a>
                        @else
                        <td></td>
                        @endif
                    </tr>
                    @if ($loop->last)
                        @include ('laporan-jurnal-umum/balance', compact('rekapitulasi', 'jurnal_kode'))
                    @endif

                    @endforeach

                    <tr>
                        <td><b>Total</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($totalDebet,2, ",", ".") }}</b></td>
                        <td><b>Rp. {{ number_format($totalKredit,2, ",", ".") }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><b>Balance</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Rp. {{ number_format($balance,2, ",", ".") }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif


               `;
        }


</script>
@endpush

