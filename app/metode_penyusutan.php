<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class metode_penyusutan extends Model
{
    protected $table = "metode_penyusutan";
    public $timestamps = false;
    protected $fillable = ['nama'];
}
