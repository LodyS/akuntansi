<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pendapatan_jasa extends Model
{
    protected $table = "pendapatan_jasa";
    public $timestamps = false;

    public function pelanggan()
    {
        return $this->hasOne('App\Models\Pelanggan', 'id', 'id_pelanggan');
    }
}
