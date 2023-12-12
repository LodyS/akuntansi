<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    public $timestamps = true;
    protected $table = "jurnal";
    protected $primaryKey = "id";
    public static $validasi = ['kode_jurnal'=>'required', 'tanggal_posting'=>'required'];
    protected $fillable = ['kode_jurnal', 'tanggal_posting','keterangan', 'id_tipe_jurnal','id_user', 'no_dokumen', 'flag_jurnal, flag_tutup_buku'];

    public function pk()
    {
        return $this->{$this->primaryKey};
    }

    protected static function newCodeCRJ()
    {
        $code = static::selectRaw('CONCAT("CRJ-", SUBSTR(kode_jurnal, 5)+1) AS kode')
        ->where('kode_jurnal', 'like', 'CRJ-%')
        ->orderByDesc('id')
        ->first();
        return $code->kode ?? "CRJ-1";
    }

    protected static function newCode($prefix)
    {
        $length = strlen($prefix) + 2;
        $code = static::selectRaw('CONCAT("'.$prefix.'-", SUBSTR(kode_jurnal, '.$length.')+1) AS kode')
        ->where('kode_jurnal', 'like', $prefix.'-%')
        ->orderByDesc('id')
        ->first();
        return $code->kode ?? $prefix."-1";
    }

    public function scopeGjCode ()
    {
        $kode_jurnal = Jurnal::selectRaw('CONCAT("GJ-", SUBSTR(kode_jurnal, 4)+1) AS kode')
        ->where('kode_jurnal', 'like', 'GJ%')
        ->orderByDesc('id')
        ->first();

        return $kode_jurnal->kode ?? 'GJ-1';
    }

    public function scopeAdjCode ()
    {
        $kode_jurnal = Jurnal::selectRaw('CONCAT("ADJ-", SUBSTR(kode_jurnal, 5)+1) AS kode_jurnal')
        ->where('kode_jurnal', 'like', 'ADJ%')
        ->orderByDesc('id')
        ->first(); // untuk mendapat urutan kode jurnal

        return $kode_jurnal ?? 'ADJ-1';
    }
}
