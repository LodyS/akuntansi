<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Visit extends Model
{
    use RecordSignature;

    public $guarded = ["id", "created_at", "updated_at", "user_input", "user_update"];
    protected $table = "visit";
    public $timestamps = false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Visit::query();

        // search results based on user input
        \Request::input('id') and $query->where('id', \Request::input('id'));
        \Request::input('id_pelanggan') and $query->where('id_pelanggan', \Request::input('id_pelanggan'));
        \Request::input('waktu') and $query->where('waktu', \Request::input('waktu'));
        \Request::input('status') and $query->where('status', 'like', '%' . \Request::input('status') . '%');

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"), \Request::input("sortType", "asc"));

        // paginate results
        return $query->paginate(10);
    }

    public static function validationRules($attributes = null)
    {
        $rules = [
            'id_pelanggan' => 'required',
            'waktu' => 'required|date',
            'status' => 'string|max:1',
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

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan','id_pelanggan');
    }
}
