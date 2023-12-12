<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Unit extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="unit";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Unit::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('flag_aktif') and $query->where('flag_aktif','like','%'.\Request::input('flag_aktif').'%');
        \Request::input('keterangan') and $query->where('keterangan','like','%'.\Request::input('keterangan').'%');

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'required|unique:unit,kode|string|max:20',
            'nama' => 'required|string|max:100',
            'flag_aktif' => 'string|max:1',
            // 'keterangan' => 'string|max:100',
        ];

        // no list is provided
        if(!$attributes)
            return $rules;

        // a single attribute is provided
        if(!is_array($attributes))
            return [ $attributes => $rules[$attributes] ];

        // a list of attributes is provided
        $newRules = [];
        foreach ( $attributes as $attr )
            $newRules[$attr] = $rules[$attr];
        return $newRules;
    }

    public function pk(){
      return $this->{$this->primaryKey};
    }

    }
