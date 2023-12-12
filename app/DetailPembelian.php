<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';
    //public $timestamps = 'false';
    protected $fillable = ['id_pembelian','harga_pembelian', 'id_perkiraan', 'diskon', 'jumlah_pembelian', 'id_barang', 'id_packing_barang'];
}
