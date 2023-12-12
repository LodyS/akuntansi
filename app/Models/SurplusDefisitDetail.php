<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SurplusDefisitDetail extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="surplus_defisit_detail";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SurplusDefisitDetail::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_surplus_defisit') and $query->where('id_surplus_defisit',\Request::input('id_surplus_defisit'));
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        \Request::input('list_code_rekening') and $query->where('list_code_rekening','like','%'.\Request::input('list_code_rekening').'%');
        \Request::input('list_code_unit') and $query->where('list_code_unit','like','%'.\Request::input('list_code_unit').'%');
        \Request::input('type') and $query->where('type',\Request::input('type'));
        \Request::input('urutan') and $query->where('urutan',\Request::input('urutan'));
        \Request::input('aktif') and $query->where('aktif',\Request::input('aktif'));

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'id_surplus_defisit' => 'integer',
            'nama' => 'string|max:255',

            'type' => 'required|integer',
            'urutan' => 'required',

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


    public function surplus_defisit()
    {
        return $this->belongsTo('App\Models\Surplus_defisit','id_surplus_defisit');
    }

    public function setSurplus_defisitAttribute($surplus_defisit) {
      unset($this->attributes['surplus_defisit']);
    }


    }
