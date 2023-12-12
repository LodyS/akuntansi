<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SyaratAnggaran extends Model
{
    protected $table = "syarat_anggaran";
    protected $fillable = ['nama', 'keterangan'];
}
