<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SettingEmail extends Model {
    // use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="setting_email";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SettingEmail::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('mail_driver') and $query->where('mail_driver','like','%'.\Request::input('mail_driver').'%');
        \Request::input('mail_host') and $query->where('mail_host','like','%'.\Request::input('mail_host').'%');
        \Request::input('mail_port') and $query->where('mail_port','like','%'.\Request::input('mail_port').'%');
        \Request::input('mail_username') and $query->where('mail_username','like','%'.\Request::input('mail_username').'%');
        \Request::input('mail_password') and $query->where('mail_password','like','%'.\Request::input('mail_password').'%');
        \Request::input('mail_encryption') and $query->where('mail_encryption','like','%'.\Request::input('mail_encryption').'%');
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
            'mail_driver' => 'string|max:200',
            'mail_host' => 'string|max:200',
            'mail_port' => 'string|max:100',
            'mail_username' => 'string|max:100',
            'mail_password' => 'string|max:100',
            'mail_encryption' => 'string|max:100',
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
