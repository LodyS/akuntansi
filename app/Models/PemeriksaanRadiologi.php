<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanRadiologi extends Model
{

    public $guarded = ["id"];
    protected $table = "pemeriksaan_radiologi";
    public $timestamps = false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Spesialisasi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id', \Request::input('id'));
        \Request::input('id_golongan_radiologi') and $query->where('id_golongan_radiologi',\Request::input('id_golongan_radiologi'));
        \Request::input('id_jenis_radiologi') and $query->where('id_jenis_radiologi',\Request::input('id_jenis_radiologi'));
        \Request::input('id_layanan') and $query->where('id_layanan',\Request::input('id_layanan'));

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"), \Request::input("sortType", "asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules($attributes = null)
    {
        $rules = [
            'id_golongan_radiologi' => 'required|integer',
            'id_jenis_radiologi' => 'required|integer',
            'id_layanan' => 'required|integer',
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

    public function pk()
    {
        return $this->{$this->primaryKey};
    }

    public function golongan_radiologi()
    {
        return $this->hasOne('App\Models\GolonganRadiologi', 'id', 'id_golongan_radiologi');
    }

    public function jenis_radiologi()
    {
        return $this->hasOne('App\Models\JenisRadiologi', 'id', 'id_jenis_radiologi');
    }

    public function layanan()
    {
        return $this->hasOne('App\Models\Layanan', 'id', 'id_layanan');
    }
}
