<?php
namespace App\Models;
use Cache;
use App\Traits\CacheUpdater;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\RecordSignature;

class ConfigId extends Model {
    use CacheUpdater;
    // use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="config_ids";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = ConfigId::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('config_name') and $query->where('config_name','like','%'.\Request::input('config_name').'%');
        \Request::input('table_source') and $query->where('table_source','like','%'.\Request::input('table_source').'%');
        \Request::input('config_value') and $query->where('config_value','like','%'.\Request::input('config_value').'%');
        \Request::input('description') and $query->where('description','like','%'.\Request::input('description').'%');
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
            'config_name' => 'string|max:255',
            'table_source' => 'string|max:255',
            'config_value' => 'string|max:255',
            'description' => 'string|max:255',
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

    public static function getValues($configName){
      $configs = Cache::remember('config_ids_'.$configName,120, function() use($configName)
      {
        $temp = ConfigId::select('config_value')->where('config_name',$configName)->first();
        if($temp==null)return null;
        return explode(',',$temp->config_value);
    });

      return $configs;
  }

  private function updateCache(){
      Cache::forget('config_ids_'.$this->config_name);

      return self::getValues($this->config_name);
  }


    }
