<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class TarifPajak extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="tarif_pajak";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = TarifPajak::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('nama_pajak') and $query->where('nama_pajak','like','%'.\Request::input('nama_pajak').'%');
        \Request::input('persentase_pajak') and $query->where('persentase_pajak',\Request::input('persentase_pajak'));
        \Request::input('status_aktif') and $query->where('status_aktif','like','%'.\Request::input('status_aktif').'%');
        \Request::input('id_perkiraan') and $query->where('id_perkiraan', 'like', '%'.\Request::input('id_perkiraan').'%');

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'nama_pajak' => 'required|string|max:100',
            'persentase_pajak' => 'required|integer',
            'id_perkiraan'=>'',
            'status_aktif' => 'string|max:1',
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
