<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingCoa extends Model
{
    protected $table = "setting_coa";
    //public $timestamps = true;

    protected $fillable = ['keterangan', 'id','id_tarif', 'type_obat','type', 'type_bayar', 'type_pasien', 'id_kelas', 'id_perkiraan',
    'user_input', 'user_update', 'jenis','id_kelompok_aktiva_tetap', 'id_bank'];

    protected $casts =
    [
    'id'                 =>'array',
    'keterangan'         =>'array',
    'jenis'              =>'array',
    'id_bank'            =>'array',
    'user_input'         =>'array',
    'user_update'        =>'array',
    'id_tarif'           =>'array',
    'id_kelompok_aktiva' =>'array',
    'id_kelas'           =>'array',
    'type_bayar'         =>'array',
    'type_pasien'        =>'array',
    'id_perkiraan'       =>'array',
    'type_obat'          =>'array'
    ];

    public function kelas (){
        return $this->belongsTo('App\kelas', 'setting_coa', 'id', 'id_kelas');
    }

    public function perkiraan (){
        return $this->belongsTo('App\Models\Perkiraan', 'id_perkiraan');
    }
}
