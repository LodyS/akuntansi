<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class LogStok extends Model {
    use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="log_stok";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = LogStok::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_stok') and $query->where('id_stok',\Request::input('id_stok'));
        \Request::input('waktu') and $query->where('waktu',\Request::input('waktu'));
        \Request::input('stok_awal') and $query->where('stok_awal',\Request::input('stok_awal'));
        \Request::input('selisih') and $query->where('selisih',\Request::input('selisih'));
        \Request::input('stok_akhir') and $query->where('stok_akhir',\Request::input('stok_akhir'));
        \Request::input('id_transaksi') and $query->where('id_transaksi',\Request::input('id_transaksi'));
        \Request::input('user_input') and $query->where('user_input',\Request::input('user_input'));
        \Request::input('user_update') and $query->where('user_update',\Request::input('user_update'));
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
            'id_stok' => 'integer',
            'waktu' => 'required',
            'stok_awal' => 'required',
            'selisih' => 'required',
            'stok_akhir' => 'required',
            //'id_transaksi' => 'integer',
            'user_input' => 'required|integer',
            'user_update' => 'integer',
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

    
    public function stok()
    {
        return $this->belongsTo('App\Models\Stok','id_stok');
    }

    public function setStokAttribute($stok) {
      unset($this->attributes['stok']);
    }

    
    
    public function transaksi()
    {
        return $this->belongsTo('App\Models\Transaksi','id_transaksi');
    }

    public function setTransaksiAttribute($transaksi) {
      unset($this->attributes['transaksi']);
    }

    
    }
