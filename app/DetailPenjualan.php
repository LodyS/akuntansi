<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = "detail_penjualan";
    protected $primaryKey = 'id';
    protected $fillable = ['id_penjualan', 'id_barang', 'hna', 'margin', 'jumlah_penjualan', 'diskon', 'total', 'id_user'];
}
