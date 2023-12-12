<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    protected $table = 'pembayaran';
    //public $timestamps = false;
    protected $fillable = 
    [   'kode_bkm', 
        'id_pelanggan', 
        'id_tagihan', 
        'no_kunjungan', 
        'total_tagihan', 
        'jumlah_bayar', 
        'sisa_tagihan', 
        'klaim_bpjs', 
        'diskon',
        'waktu', 
        'flag_batal', 
        'id_bank', 
        'tipe_pasien', 
        'ref', 
        'no_jurnal', 
        'user_update',
        'flag_ak'];
}
