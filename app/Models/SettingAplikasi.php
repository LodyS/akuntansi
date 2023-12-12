<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SettingAplikasi extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="setting_aplikasi";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SettingAplikasi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('deskripsi') and $query->where('deskripsi','like','%'.\Request::input('deskripsi').'%');
        \Request::input('logo') and $query->where('logo','like','%'.\Request::input('logo').'%');
        \Request::input('base_url') and $query->where('base_url','like','%'.\Request::input('base_url').'%');
        \Request::input('flag_morbis') and $query->where('flag_morbis','like','%'.\Request::input('flag_morbis').'%');
        \Request::input('version') and $query->where('version','like','%'.\Request::input('version').'%');
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
            'nama' => 'string|max:200',
            'deskripsi' => 'string|max:200',
            //'logo' => 'string|max:100',
            'base_url' => 'string|max:100',
            'flag_morbis' => 'string|max:100',
            'version' => 'string|max:100',
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
