<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budgeting extends Model
{
    protected $table = 'budgeting';
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'tanggal_input', 'periode_anggaran', 'user_input', 'flag_verif', 'user_verif', 'verified_at'];
}
