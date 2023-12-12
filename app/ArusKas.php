<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArusKas extends Model
{
    protected $table = 'arus_kas';
    //public $timestamps = false;
    protected $fillable = ['nama', 'tipe','level', 'urutan','id_induk', 'jenis', 'user_input', 'user_update', 'id_perkiraan'];

    public function scopefilterSatu ()
    {
        return static::where('tipe',1);
    }
}
