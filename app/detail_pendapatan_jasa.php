<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detail_pendapatan_jasa extends Model
{
    protected $table = "detail_pendapatan_jasa";
    public $timestamps = false;

    public function tarif()
    {
        return $this->hasOne('App\tarif', 'id', 'id_tarif');
    }
}
