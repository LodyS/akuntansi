<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    protected $table = 'detail_pembayaran';
    public $timestamps = 'true';
    protected $fillable = ['id_pembayaran', 'no_kunjungan', 'jenis', 'total_pembayaran'];
}
