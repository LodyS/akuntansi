<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GajiPegawai extends Model
{
    protected $table = 'gaji_pegawai';
    protected $fillable = [
        "id_pegawai",
        "nama_pegawai",
        "id_unit",
        "nominal",
        "nama_rek",
        "id_bank",
        "flag_terposting",
        "tanggal_posting",
    ];
}
