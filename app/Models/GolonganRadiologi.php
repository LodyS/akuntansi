<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GolonganRadiologi extends Model
{

    public $guarded = ["id"];
    protected $table = "golongan_radiologi";
    public $timestamps = false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = GolonganRadiologi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id', \Request::input('id'));
        \Request::input('nama') and $query->where('nama', 'like', '%' . \Request::input('nama') . '%');

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"), \Request::input("sortType", "asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules($attributes = null)
    {
        $rules = [
            'nama' => 'required|string|max:50',
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
}
