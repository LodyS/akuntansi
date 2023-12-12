<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingCoaJasaPegawai extends Model
{
    protected $table = "setting_coa_jasa_pegawai";
    public $timestamps = true;
    protected $fillable = ['id_unit', 'id_perkiraan', 'id_jasa_pegawai'];
    protected $casts =
    [
        'id'=>'array',
        'id_unit'=>'array',
        'id_jasa_pegawai'=>'array',
        'id_perkiraan'=>'array' 
    ];
}
