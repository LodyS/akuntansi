<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\RecordSignature;

class Provinsi extends Model {
    // use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="provinsi";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Provinsi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('provinsi') and $query->where('provinsi','like','%'.\Request::input('provinsi').'%');
        \Request::input('flag_aktif') and $query->where('flag_aktif','like','%'.\Request::input('flag_aktif').'%');
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'string|max:100',
            'provinsi' => 'string|max:100',
            'flag_aktif' => 'string|max:1',
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
