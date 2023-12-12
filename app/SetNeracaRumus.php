<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetNeracaRumus extends Model
{
    protected $table = 'set_neraca_rumus';
    protected $primaryKey = 'id';
    protected $fillable = ['id_set_neraca', 'id_rumus', 'id_sub_rumus'];
}
