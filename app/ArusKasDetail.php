<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArusKasDetail extends Model
{
    protected $table = 'arus_kas_detail';
    protected $primaryKey = 'id';
    protected $fillable = ["id_arus_kas", "id_perkiraan"];
}
