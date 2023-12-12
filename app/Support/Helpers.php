<?php
//use DB;
use App\Models\Spesialisasi;

function message($isSuccess,$successMessage="Data has been saved",$failedMessage="Failed to save data")
{
    if($isSuccess){
        Session::flash('message',$successMessage);
    } else {
        Session::flash('message',$failedMessage);
    }

    Session::flash('messageType',$isSuccess ? 'success' : 'error');
}

function validatorMessageStr($errors){
	$err="<ul>";
    foreach ($errors->all() as $error):
        $err.="<li>$error</li>";
    endforeach;
    $err.="</ul>";
    return $err;
}

function rupiahTanpaKoma($nominal,$withRp=true){
    $rupiah = number_format($nominal, 0, ",", ".");
    if($withRp)
        $rupiah = "Rp " . $rupiah ;
    return $rupiah;
}

function nominalKoma($nominal,$withRp=true){
    $pecah = explode('.',$nominal);
    if (empty($pecah[1])) {
        $rupiah = number_format($nominal, 0, ".", ",");
    }else {
        $rupiah = number_format($nominal, 2, ".", ",");
    }
if($withRp)
    $rupiah = "Rp " . $rupiah ;
return $rupiah;
}

function nominalTitik($nominal,$withRp=true){
    $pecah = explode('.',$nominal);
    if (empty($pecah[1])) {
        $rupiah = number_format($nominal, 0, ",", ".");
    }else {
        $rupiah = number_format($nominal, 2, ",", ".");
    }
if($withRp)
    $rupiah = "Rp " . $rupiah ;
return $rupiah;
}

function currencyToNumber($a){
    $b=str_replace(".", "", $a);
    return str_replace(",",".",$b);
}

// 5.000,01 to 5000.01
function currencyToFloat($a){
    $b=str_replace(".", "", $a);
    return str_replace(",",".",$b);
}

function date_indo($tgl)
{
    $ubah = gmdate($tgl, time()+60*60*8);
    $pecah = explode("-",$ubah);
    $tanggal = $pecah[2];
    $bulan = bulan($pecah[1]);
    $tahun = $pecah[0];
    return $tanggal.' '.$bulan.' '.$tahun;
}



function bulan($bln)
{
    switch ($bln)
    {
        case 1:
        return "Januari";
        break;
        case 2:
        return "Februari";
        break;
        case 3:
        return "Maret";
        break;
        case 4:
        return "April";
        break;
        case 5:
        return "Mei";
        break;
        case 6:
        return "Juni";
        break;
        case 7:
        return "Juli";
        break;
        case 8:
        return "Agustus";
        break;
        case 9:
        return "September";
        break;
        case 10:
        return "Oktober";
        break;
        case 11:
        return "November";
        break;
        case 12:
        return "Desember";
        break;
    }
}

function date_test()
{
    return date('Y-m-d');
}

function hour_test()
{
    // return date('09:00:s');
    // return date('17:00:s');
    return date('H:i:s');
}

// date format dd/mm/yyyy to yyyy-mm-dd
function dbDate($tgl)
{
    $new = null;
    $tgl = explode("/", $tgl);
    if (empty($tgl[2]))
        return "";
    $new = "$tgl[2]-$tgl[1]-$tgl[0]";
    return $new;
}

function limitTextChars($content = false, $limit = false, $stripTags = false, $ellipsis = false)
{
    if ($content && $limit) {
        $content  = ($stripTags ? strip_tags($content) : $content);
        $ellipsis = ($ellipsis ? "..." : $ellipsis);
        $content  = mb_strimwidth($content, 0, $limit, $ellipsis);
    }
    return $content;
}

function limitTextWords($content = false, $limit = false, $stripTags = false, $ellipsis = false)
{
    if ($content && $limit) {
        $content = ($stripTags ? strip_tags($content) : $content);
        $content = explode(' ', $content, $limit+1);
        array_pop($content);
        if ($ellipsis) {
            array_push($content, '...');
        }
        $content = implode(' ', $content);
    }
    return $content;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}

function getConfigValues($configName){
    return \App\Models\ConfigId::getValues($configName);
}

function time_diff_string($from, $to, $full = false) {
    $from = new DateTime($from);
    $to = new DateTime($to);
    $diff = $to->diff($from);

    // $diff->w = floor($diff->d / 7);
    // $diff->d -= $diff->w * 7;

    // $string = array(
    //     'y' => 'year',
    //     'm' => 'month',
    //     'w' => 'week',
    //     'd' => 'day',
    //     'h' => 'hour',
    //     'i' => 'minute',
    //     's' => 'second',
    // );
    // foreach ($string as $k => &$v) {
    //     dd($diff->$k);
    //     // if ($diff->$k) {
    //     //     $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    //     // }
    //     // else {
    //     //     unset($string[$k]);
    //     // }
    // }
    $data['tahun']=$diff->y;
    $data['bulan']=$diff->m;
    $data['hari']=($diff->d-1);

    return $data;
}

function getKodeSpesialisasi (){

	$kode_awal = 'S-';
	$base_kode = strtoupper($kode_awal);
	$base_kode = $base_kode;

	$kode_terakhir = \DB::table('spesialisasi')->select(\DB::raw('MAX(REPLACE(kode, "S", ""))'))->where('kode', 'like', '%$base_kode%')->first();
	$kode_terakhir = \DB::select('select kode from spesialisasi order by substr(kode from 1 for 1), cast(substr(kode from 2) as unsigned) desc limit 1');

	if (isset($kode_terakhir[0]) && !empty($kode_terakhir[0])){
		$kode_akhir = $kode_terakhir[0]->kode;

		$tambah_kode = str_replace('S-','', $kode_akhir);
		$max_panjang = strlen($tambah_kode);

		$tambah_kode++;
		$next_kode = $tambah_kode;
		$diff_length = $max_panjang - strlen($next_kode);

		$kode_tambahan = '';

		for ($x=1; $x<=$diff_length; $x++){
			$kode_tambahan .='0';
		}

		$next_kode = $kode_tambahan.$next_kode;
		$next_kode_spesialisasi = $next_kode;

	} else {
		$next_kode_spesialisasi = '1';
	}

	return $next_kode_spesialisasi;
}


