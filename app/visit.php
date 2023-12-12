<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class visit extends Model
{
    protected $table = "visit";
    public $timestamps = false;
    public $guarded = ["id","created_at","updated_at","user_input","user_update"];
}
