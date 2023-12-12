<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SubKategoriBarang extends Model {
    use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="sub_kategori_barang";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = SubKategoriBarang::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('id_kategori_barang') and $query->where('id_kategori_barang',\Request::input('id_kategori_barang'));
        \Request::input('permintaan_penjualan') and $query->where('permintaan_penjualan','like','%'.\Request::input('permintaan_penjualan').'%');
        \Request::input('user_input') and $query->where('user_input',\Request::input('user_input'));
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
            'nama' => 'required|string|max:255',
            'id_kategori_barang' => 'integer',
            'permintaan_penjualan' => 'required|string|max:255',
            'user_input' => 'required|integer',
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

    
    public function kategori_barang()
    {
        return $this->belongsTo('App\Models\Kategori_barang','id_kategori_barang');
    }

    public function setKategori_barangAttribute($kategori_barang) {
      unset($this->attributes['kategori_barang']);
    }

    
    }
