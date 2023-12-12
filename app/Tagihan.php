<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = "tagihan";
    public $timestamps = false;

    public function pendapatan_jasa()
    {
        return $this->hasOne('App\pendapatan_jasa', 'id', 'id_pendapatan_jasa');
    }

    public function detail_pendapatan_jasa()
    {
        return $this->hasOne('App\detail_pendapatan_jasa', 'id', 'id_detail_pendapatan_jasa');
    }

    public function unit()
    {
        return $this->hasOne('App\Models\Unit', 'id', 'id_unit');
    }

    // public function layanan()
    // {
    //     return $this->hasOne('App\Layanan', 'id', 'id_layanan');
    // }
}
