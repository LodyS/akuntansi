<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tarif extends Model
{
    public $guarded = [];
    protected $table = "tarif";
    //public $timestamps = false;
    protected $primaryKey = "id";
    public $incrementing = true;

    public function kelas()
    {
        return $this->hasOne('App\kelas', 'id', 'id_kelas');
    }

    public function layanan()
    {
        return $this->hasOne('App\Layanan', 'id', 'id_layanan');
    }

    public function pk()
    {
        return $this->{$this->primaryKey};
    }

    public static function validationRules($attributes = null)
    {
        $rules = [
            'id_kelas' => 'required|integer',
            'id_layanan' => 'required|integer',
            'jasa_sarana' => 'required|integer',
            'bhp' => 'required|integer',
            'total_utama' => 'required|integer',
            'persen_nakes_utama' => 'required|integer',
            'persen_rs_utama' => 'required|integer',
            'total_pendamping' => 'required|integer',
            'persen_nakes_pendamping' => 'required|integer',
            'persen_rs_pendamping' => 'required|integer',
            'total_pendukung' => 'required|integer',
            'persen_nakes_pendukung' => 'required|integer',
            'persen_rs_pendukung' => 'required|integer',
            'alkes' => 'required|integer',
            'kr' => 'required|integer',
            'ulup' => 'required|integer',
            'adm' => 'required|integer',
            'total' => 'required|integer',
        ];

        // no list is provided
        if (!$attributes)
            return $rules;

        // a single attribute is provided
        if (!is_array($attributes))
            return [$attributes => $rules[$attributes]];

        // a list of attributes is provided
        $newRules = [];
        foreach ($attributes as $attr)
            $newRules[$attr] = $rules[$attr];
        return $newRules;
    }
}
