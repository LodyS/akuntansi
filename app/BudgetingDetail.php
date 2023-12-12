<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetingDetail extends Model
{
    protected $table = 'budgeting_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['id_budgeting', 'id_perkiraan', 'id_unit', 'nominal', 'flag_verif'];
}
