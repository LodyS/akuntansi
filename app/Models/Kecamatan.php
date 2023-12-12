<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\RecordSignature;

class Kecamatan extends Model {
    // use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="kecamatan";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Kecamatan::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('kecamatan') and $query->where('kecamatan','like','%'.\Request::input('kecamatan').'%');
        \Request::input('id_kabupaten') and $query->where('id_kabupaten',\Request::input('id_kabupaten'));
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
            'kecamatan' => 'string|max:100',
            'id_kabupaten' => 'required|integer',
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

    
    public function kabupaten()
    {
        return $this->belongsTo('App\Models\Kabupaten','id_kabupaten');
    }

    public function setKabupatenAttribute($kabupaten) {
      unset($this->attributes['kabupaten']);
    }

    
    }
