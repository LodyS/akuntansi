<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SubJenisUsaha extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="sub_jenis_usaha";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SubJenisUsaha::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('id_jenis_usaha') and $query->where('id_jenis_usaha',\Request::input('id_jenis_usaha'));
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('flag_aktif') and $query->where('flag_aktif','like','%'.\Request::input('flag_aktif').'%');

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'required|string|max:255',
            'id_jenis_usaha' => 'required|integer',
            'nama' => 'required|string|max:255',
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


    public function jenis_usaha()
    {
        return $this->belongsTo('App\Models\Jenis_usaha','id_jenis_usaha');
    }

    public function setJenis_usahaAttribute($jenis_usaha) {
      unset($this->attributes['jenis_usaha']);
    }


    }
