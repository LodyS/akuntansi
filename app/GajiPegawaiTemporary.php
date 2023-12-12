<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GajiPegawaiTemporary extends Model
{
    protected $table = 'gaji_pegawai_temporary';
    protected $fillable = [
        "id_gaji_pegawai",
        "centang",
    ];
}
