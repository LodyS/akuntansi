<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table='payroll';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
