<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriPerkiraan extends Model
{
    protected $table   = "kategori_perkiraan";
    public $timestamps = "false";
    protected $fillable = ['kode', 'nama'];
}
