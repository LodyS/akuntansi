@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Bukti Kas / Bank Penerimaan</h1>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

    Kas Bank : {{ isset($bukti_bank) ? $bukti_bank->bank : 'Data Kosong'}}<br/>
    No Bukti : {{ isset($bukti_bank) ? $bukti_bank->kode_voucher : 'Data Kosong '}}<br/>
    Tanggal  : {{ isset($bukti_bank) ? date('d-m-Y', strtotime($bukti_bank->tanggal)) : 'Data Kosong' }}<br/>
    No Cek/Giro : {{ isset($bukti_bank) ? $bukti_bank->no_dokumen : 'Data Kosong' }}<br/><br/>
    
    <table class="table table-hover">
        <tr>
            <th><b>No</b></th>
            <th><b>Tanggal</b></th>
            <th><b>No Cek/Giro</b></th>
            <th><b>Keterangan</b></th>
            <th><b>Jumlah</b></th>
            <th><b>Perkiraan</b></th>
        </tr>
        @php ($i=1)
        @php ($total =0)
        @foreach ($biaya_materai as $data)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ date('d-m-Y', strtotime($data->tanggal))}}</td>
            <td>{{ $data->kode_voucher }}</td>
            <td>{{ $data->keterangan }}</td>
            <td>Rp. {{ number_format($data->jumlah,2) }}</td>
            <td>{{ $data->perkiraan }}</td>
            @php ($total += $data->jumlah)
        </tr>
   
        @php($i++)
        @endforeach
    </table>
        <?php
            function terbilang ($angka){
               
                $angka = abs($angka);
                $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                
                $terbilang = "";

                if ($angka < 12){
                    $terbilang= " " . $baca[$angka];
                }
                else if ($angka < 20){
                    $terbilang= terbilang($angka - 10) . " belas";
                }
                else if ($angka < 100){
                    $terbilang= terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
                }
                else if ($angka < 200){
                    $terbilang= " seratus" . terbilang($angka - 100);
                }
                else if ($angka < 1000){
                    $terbilang= terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
                }
                else if ($angka < 2000){
                    $terbilang= " seribu" . terbilang($angka - 1000);
                }
                else if ($angka < 1000000){
                    $terbilang= terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
                }
                else if ($angka < 1000000000){
                    $terbilang= terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
                }
                else if ($angka < 1000000000000){
                    $terbilang= terbilang($angka / 1000000000) . " milyar" . terbilang($angka % 1000000000);
                }
                else if ($angka < 1000000000000000){
                    $terbilang= terbilang($angka / 1000000000000) . "triliun" . terbilang($angka % 1000000000000);
                }
                   return $terbilang;   
                }
                 
            echo "<br/>";
            echo "Terbilang : ".terbilang($total). "rupiah";
        ?>
        <hr/>
            <table class="table table-hover">
            
            <tr>
                <th>Disetujui : </th>
                <th>Dibukukan oleh </th>
                <th>Diperiksa Oleh </th>
                <th>Disetor oleh</th>
            </tr>

            <tr>
                <td>(...........)</td>
                <td>(...........)</td>
                <td>(...........)</td>
                <td>(...........)</td>
            </tr>

            </table>
        </div>
    </div>
</div>
@endsection