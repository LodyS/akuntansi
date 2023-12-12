<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    protected $table = 'log_stok';
    protected $fillable = ['id_stok','waktu', 'stok_awal', 'selisih', 'stok_akhir', 'id_transaksi', 'user_input', 'user_update'];
}
