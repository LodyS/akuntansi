<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailJurnal extends Model
{
    protected $table = "detail_jurnal";
    //public $timestamps = false;
    protected $fillable = ['id_jurnal', 'id_perkiraan', 'ref', 'debet', 'kredit', 'id_unit', 'tanggal', 'keterangan'];
}

