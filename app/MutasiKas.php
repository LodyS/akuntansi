<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiKas extends Model
{
    protected $table = 'mutasi_kas';
    public $timestamps = true;
    protected $fillable = ['kode', 'id_arus_kas', 'tanggal', 'id_perkiraan', 'id_kas_bank', 'nominal', 'tipe', 'keterangan', 'user_input', 'user_update', 'ref', 'no_jurnal'];

    public function scopeBkm ()
    {
        $date = date('Y-m-d'); // untuk ambil data tanggal sekarang
        //$tanggalAkhir = date('Y-m-t', strtotime($date)); // untuk ambil data tanggal terakhir
        $tanggalAkhir = date('Y-m-t',strtotime('last month'));
        $tanggalAwal = date("Y-m-01", strtotime('last day of previous month')); // untuk ambil data tanggal pertama
        $tanggal = date('Ymd'); // untuk generate kode bkm

        $kode_bkm = MutasiKas::selectRaw("CONCAT('BKM-','$tanggal', '-',SUBSTR(kode, 14)+1) AS kode")
        ->where('kode', 'like', 'BKM%')
        ->orderByDesc('id')
        ->first();

        $kode_tanggal = MutasiKas::selectRaw('SUBSTR(kode, 5,4) AS tahun, SUBSTR(kode,9,2) bulan, SUBSTR(kode, 11,2) AS tanggal')
        ->orderBy('id', 'desc')
        ->first();

        $tanggal_kode = isset($kode_tanggal) ? $kode_tanggal->tahun.'-'.$kode_tanggal->bulan.'-'.$kode_tanggal->tanggal : date('Y').'-'.date('m').'-'.date('d');
        $kode_tanggal = date('Y-m-d', strtotime($tanggal_kode));

        if ($tanggal_kode > $tanggalAkhir)
        {
            $kode_awal = isset($kode_bkm) ? $kode_bkm->kode : "BKM".'-'.$tanggal.'-'.'1';
        } else {
            $kode_awal = "BKM".'-'.$tanggal.'-'.'1';
        }

        return $kode_awal;
    }

    public function scopeBkk()
    {
         //generate kode bkm
         $date = date('Y-m-d'); // untuk ambil data tanggal sekarang
         //$tanggalAkhir = date('Y-m-t', strtotime($date)); // untuk ambil data tanggal terakhir
         $tanggalAkhir = date('Y-m-t',strtotime('last month'));
         $tanggalAwal = date("Y-m-01", strtotime('last day of previous month')); // untuk ambil data tanggal pertama
         $tanggal = date('Ymd'); // untuk generate kode bkm

         $kode_bkm = MutasiKas::selectRaw("CONCAT('BKK-','$tanggal', '-',SUBSTR(kode, 14)+1) AS kode")
         ->where('kode', 'like', 'BKK%')
         ->orderByDesc('id')
         ->first();

         $kode_tanggal = MutasiKas::selectRaw('SUBSTR(kode, 5,4) AS tahun, SUBSTR(kode,9,2) bulan, SUBSTR(kode, 11,2) AS tanggal')
         ->orderBy('id', 'desc')
         ->first();

         $tanggal_kode = isset($kode_tanggal) ? $kode_tanggal->tahun.'-'.$kode_tanggal->bulan.'-'.$kode_tanggal->tanggal : date('Y').'-'.date('m').'-'.date('d');
         $kode_tanggal = date('Y-m-d', strtotime($tanggal_kode));

         if ($tanggal_kode > $tanggalAkhir)
         {
             $kode_awal = isset($kode_bkm) ? $kode_bkm->kode : "BKK".'-'.$tanggal.'-'.'1';
         } else {
             $kode_awal = "BKK".'-'.$tanggal.'-'.'1';
         }

         return $kode_awal;
    }
}
