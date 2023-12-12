<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SurplusDefisitRek extends Model {
//    use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="surplus_defisit_rek";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SurplusDefisitRek::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_surplus_defisit_detail') and $query->where('id_surplus_defisit_detail',\Request::input('id_surplus_defisit_detail'));
        \Request::input('id_perkiraan') and $query->where('id_perkiraan',\Request::input('id_perkiraan'));
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
            'id_perkiraan' => 'integer',
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



    public function perkiraan()
    {
        return $this->belongsTo('App\Models\Perkiraan','id_perkiraan');
    }

    public function setPerkiraanAttribute($perkiraan) {
      unset($this->attributes['perkiraan']);
    }


    }
