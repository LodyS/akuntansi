<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailSyaratAnggaran extends Model
{
    protected $table = 'detail_syarat_anggaran';
    protected $fillable = ['id_syarat_anggaran', 'syarat'];
}
