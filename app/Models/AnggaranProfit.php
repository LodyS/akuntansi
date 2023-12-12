<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranProfit extends Model
{
    public $guarded = ["id","created_at","updated_at"];
    protected $table="anggaran_profit";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = false;
}
