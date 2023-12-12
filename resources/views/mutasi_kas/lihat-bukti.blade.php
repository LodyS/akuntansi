@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Bukti Penerimaan Kas </h1>
        <div class="page-header-actions">
    </div>
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
        <p align="center"><b>BUKTI KAS MASUK</b></p>
            {{ $bukti->nama_badan_usaha }}<br/>
            {{ $bukti->alamat_perusahaan }}<br/>
            <br/>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Nomor Bukti  &emsp;&emsp;&ensp;&nbsp;&nbsp;&ensp;&ensp;&ensp;&nbsp;:
            {{ $bukti->kode }}<br/>
            @php $nominal_terbilang = $bukti->nominal; @endphp
            <?php
            function terbilang ($angka)
            {
                $angka = abs($angka);
                $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                
                $terbilang = "";

                if ($angka < 12)
                {
                    $terbilang= " " . $baca[$angka];
                }
                else if ($angka < 20)
                {
                    $terbilang= terbilang($angka - 10) . " belas";
                }
                else if ($angka < 100)
                {
                    $terbilang= terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
                }
                else if ($angka < 200)
                {
                    $terbilang= " seratus" . terbilang($angka - 100);
                }
                else if ($angka < 1000)
                {
                    $terbilang= terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
                }
                else if ($angka < 2000)
                {
                    $terbilang= " seribu" . terbilang($angka - 1000);
                }
                else if ($angka < 1000000)
                {
                    $terbilang= terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
                }
                else if ($angka < 1000000000)
                {
                    $terbilang= terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
                }
                else if ($angka < 1000000000000)
                {
                    $terbilang= terbilang($angka / 1000000000) . " milyar" . terbilang($angka % 1000000000);
                }
                else if ($angka < 1000000000000000)
                {
                    $terbilang= terbilang($angka / 1000000000000) . " triliun" . terbilang($angka % 1000000000000);
                }
                   return $terbilang;   
                }
                 
            //echo "<br/>";
            echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Terbilang 
            &emsp;&emsp;&emsp;&nbsp;&ensp;&nbsp;&ensp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;: 
            ".terbilang($nominal_terbilang). "&nbsp;rupiah";
            echo "<br/>";
        ?>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Keterangan   &emsp;&emsp;&emsp;  &emsp;&emsp;: {{ $bukti->keterangan }}<br/><br/>   
        Jumlah : Rp. {{ number_format($bukti->nominal) }}<br/>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; 
        
        {{ $bukti->kota }}, {{ date('d-m-Y', strtotime($bukti->tanggal)) }}<br/>   <br/>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        Yang Mengeluarkan :    <br/>   <br/>   <br/>   <br/>   <br/>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        {{ $bukti->name }}

            </div>
        </div>
    </form>
</div>
@endsection