<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'voucher';
    public $timestamp = true;
    protected $primaryKey = 'id';
    protected $fillable = ['kode', 'id_jurnal', 'disejutui_oleh', 'dibukukan_oleh', 'diperiksa_oleh', 'diterima_oleh', 'disetor_oleh'];

    public static function generateKode()
    {
        // untuk mendapat kode yang di input ke tabel voucher
        $tanggal = date('Ymd');
        $voucher = static::selectRaw('substr(kode, 13) +1 as kode')->orderByDesc('id')->first();
        $kode_voucher = isset($voucher) ? "KD.".$tanggal.'.'.$voucher->kode : "KD.".$tanggal.".1";
        return $kode_voucher;
    }
}
