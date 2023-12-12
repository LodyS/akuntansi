<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SetSurplusDefisitDetail extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="set_surplus_defisit_detail";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SetSurplusDefisitDetail::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_set_surplus_defisit') and $query->where('id_set_surplus_defisit',\Request::input('id_set_surplus_defisit'));
        \Request::input('id_unit') and $query->where('id_unit',\Request::input('id_unit'));
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
            'id_set_surplus_defisit' => 'integer',
            'id_unit' => 'integer',
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

    
    public function set_surplus_defisit()
    {
        return $this->belongsTo('App\Models\Set_surplus_defisit','id_set_surplus_defisit');
    }

    public function setSet_surplus_defisitAttribute($set_surplus_defisit) {
      unset($this->attributes['set_surplus_defisit']);
    }

    
    
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','id_unit');
    }

    public function setUnitAttribute($unit) {
      unset($this->attributes['unit']);
    }

    
    }
