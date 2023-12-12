<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SettingPusher extends Model {
    // use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="setting_pusher";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SettingPusher::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('pusher_app_id') and $query->where('pusher_app_id','like','%'.\Request::input('pusher_app_id').'%');
        \Request::input('pusher_app_key') and $query->where('pusher_app_key','like','%'.\Request::input('pusher_app_key').'%');
        \Request::input('pusher_app_secret') and $query->where('pusher_app_secret','like','%'.\Request::input('pusher_app_secret').'%');
        \Request::input('pusher_app_cluster') and $query->where('pusher_app_cluster','like','%'.\Request::input('pusher_app_cluster').'%');
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
            'pusher_app_id' => 'string|max:200',
            'pusher_app_key' => 'string|max:100',
            'pusher_app_secret' => 'string|max:100',
            'pusher_app_cluster' => 'string|max:100',
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
