<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Setting extends Model {
    use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="setting";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Setting::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('nama_aplikasi') and $query->where('nama_aplikasi','like','%'.\Request::input('nama_aplikasi').'%');
        \Request::input('alamat') and $query->where('alamat','like','%'.\Request::input('alamat').'%');
        \Request::input('website') and $query->where('website','like','%'.\Request::input('website').'%');
        \Request::input('fax') and $query->where('fax','like','%'.\Request::input('fax').'%');
        \Request::input('telepon') and $query->where('telepon','like','%'.\Request::input('telepon').'%');
        \Request::input('email') and $query->where('email','like','%'.\Request::input('email').'%');
        \Request::input('logo') and $query->where('logo','like','%'.\Request::input('logo').'%');
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
            'nama_aplikasi' => 'required|string|max:100',
            'alamat' => 'string',
            'website' => 'string|max:100',
            'fax' => 'string|max:100',
            'telepon' => 'string|max:100',
            'email' => 'string|max:100|email',
            'logo' => 'string',
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
