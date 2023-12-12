<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class PackingBarang extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="packing_barang";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = PackingBarang::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('barcode') and $query->where('barcode','like','%'.\Request::input('barcode').'%');
        \Request::input('satuan') and $query->where('satuan','like','%'.\Request::input('satuan').'%');
        \Request::input('id_barang') and $query->where('id_barang',\Request::input('id_barang'));
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
            'barcode' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
            'id_barang' => 'integer',
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

    
    public function barang()
    {
        return $this->belongsTo('App\Models\Barang','id_barang');
    }

    public function setBarangAttribute($barang) {
      unset($this->attributes['barang']);
    }

    
    }
