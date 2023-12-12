<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\RecordSignature;

class Kelurahan extends Model {
    // use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="kelurahan";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Kelurahan::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('kelurahan') and $query->where('kelurahan','like','%'.\Request::input('kelurahan').'%');
        \Request::input('id_kecamatan') and $query->where('id_kecamatan',\Request::input('id_kecamatan'));
        \Request::input('flag_aktif') and $query->where('flag_aktif','like','%'.\Request::input('flag_aktif').'%');
        \Request::input('kodepos') and $query->where('kodepos','like','%'.\Request::input('kodepos').'%');
        \Request::input('latitude') and $query->where('latitude','like','%'.\Request::input('latitude').'%');
        \Request::input('longitude') and $query->where('longitude','like','%'.\Request::input('longitude').'%');
        \Request::input('kode_bps') and $query->where('kode_bps','like','%'.\Request::input('kode_bps').'%');
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
            'kode' => 'string|max:100',
            'kelurahan' => 'string|max:100',
            'id_kecamatan' => 'required|integer',
            'flag_aktif' => 'string|max:1',
            'kodepos' => 'string|max:10',
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

    
    public function kecamatan()
    {
        return $this->belongsTo('App\Models\Kecamatan','id_kecamatan');
    }

    public function setKecamatanAttribute($kecamatan) {
      unset($this->attributes['kecamatan']);
    }

    
    }
