<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiKasDetail extends Model
{
    protected $table = 'mutasi_kas_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['id_mutasi_kas','id_perkiraan', 'id_unit', 'nominal', 'keterangan', 'id_tarif_pajak', 'tipe'];

    /*protected $casts =
    [
    'id_pekiraan'=>'array',
    'id_unit'=>'array',
    'keterangan'=>'array',
    'nominal'=>'array'
    ];*/
}
