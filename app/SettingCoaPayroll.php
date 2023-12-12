<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingCoaPayroll extends Model
{
    protected $table = 'setting_coa_payroll';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;
    // protected $fillable = ['komponen', 'id_perkiraan', 'flag_aktif'];
}
