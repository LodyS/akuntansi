<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = "transaksi";
    public $timestamps = "true";
    protected $fillable = ['id_user', 'id_perkiraan', 'tanggal', 'keterangan', 'debet', 'kredit', 'id_periode', 'id_jurnal', 'id_unit'];
}

