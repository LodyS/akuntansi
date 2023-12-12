<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPayroll extends Model
{
    protected $table = 'detail_payroll';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
