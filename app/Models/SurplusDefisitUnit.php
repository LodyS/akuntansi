<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SurplusDefisitUnit extends Model {
 //   use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="surplus_defisit_unit";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SurplusDefisitUnit::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_surplus_defisit_detail') and $query->where('id_surplus_defisit_detail',\Request::input('id_surplus_defisit_detail'));
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
            'id_surplus_defisit_detail' => 'integer',
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


    public function surplus_defisit_detail()
    {
        return $this->belongsTo('App\Models\Surplus_defisit_detail','id_surplus_defisit_detail');
    }

    public function setSurplus_defisit_detailAttribute($surplus_defisit_detail) {
      unset($this->attributes['surplus_defisit_detail']);
    }



    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','id_unit');
    }

    public function setUnitAttribute($unit) {
      unset($this->attributes['unit']);
    }


    }
