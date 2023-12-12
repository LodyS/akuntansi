<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetLapEkuitasDetail extends Model
{
    protected $table = "set_lap_ekuitas_detail";
    //public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id_set_surplus_defisit', 'id_perkiraan', 'id_set_lap_ekuitas'];
}
