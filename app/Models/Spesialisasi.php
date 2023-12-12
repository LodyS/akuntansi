<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Spesialisasi extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="spesialisasi";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Spesialisasi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:50',
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
