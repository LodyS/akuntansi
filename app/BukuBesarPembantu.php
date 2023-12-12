<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukuBesarPembantu extends Model
{
    protected $table = 'buku_besar_pembantu';
    //public $timestamps = false;
    protected $fillable = [
        'tanggal', 
        'id_pelanggan', 
        'id_periode', 
        'id_perkiraan', 
        'keterangan', 
        'debet', 
        'kredit', 
        'user_input', 
        'id_invoice', 
        'id_pembayaran_invoice'];
}
