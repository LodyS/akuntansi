<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\RecordSignature;

class Notification extends Model {
    // use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="notification";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Notification::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('title') and $query->where('title','like','%'.\Request::input('title').'%');
        \Request::input('content') and $query->where('content','like','%'.\Request::input('content').'%');
        \Request::input('user_id') and $query->where('user_id',\Request::input('user_id'));
        \Request::input('read') and $query->where('read',\Request::input('read'));
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
            'title' => 'string|max:50',
            'content' => 'string|max:50',
            'user_id' => 'integer',
            'read' => 'required',
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

    
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function setUserAttribute($user) {
      unset($this->attributes['user']);
    }

    
    }
