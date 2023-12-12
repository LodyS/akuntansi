<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDataPemeriksaanLab extends Model
{

    public $guarded = ["id", "created_at", "updated_at"];
    protected $table = "master_data_pemeriksaan_lab";
    public $timestamps = false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = MasterDataPemeriksaanLab::query();

        // search results based on user input
        \Request::input('id') and $query->where('id', \Request::input('id'));
        \Request::input('id_layanan') and $query->where('id_layanan',\Request::input('id_layanan'));

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"), \Request::input("sortType", "asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules($attributes = null)
    {
        $rules = [
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

    public function layanan()
    {
        return $this->hasOne('App\Models\Layanan', 'id', 'id_layanan');
    }
}
