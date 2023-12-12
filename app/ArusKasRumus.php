<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArusKasRumus extends Model
{
    protected $table = 'arus_kas_rumus';
    protected $primaryKey = 'id';
    protected $fillable = ['id_arus_kas', 'id_rumus', 'id_rumus_arus_kas', 'id_transaksi_jurnal'];
}

