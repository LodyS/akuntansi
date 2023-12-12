<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JasaPegawai extends Model
{
    protected $table = "jasa_pegawai";
    public $timestamps = true;
    protected $fillable = ["nama"];
}
