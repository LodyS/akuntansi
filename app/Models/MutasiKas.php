<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;
use App\Models\Perkiraan;

class MutasiKas extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="mutasi_kas";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = MutasiKas::query();
        $perkiraan = Perkiraan::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('id_arus_kas') and $query->where('id_arus_kas',\Request::input('id_arus_kas'));
        \Request::input('tanggal') and $query->where('tanggal',\Request::input('tanggal'));
        \Request::input('id_perkiraan') and $query->where('id_perkiraan',\Request::input('id_perkiraan'));
        \Request::input('id_kas_bank') and $query->where('id_kas_bank',\Request::input('id_kas_bank'));
        \Request::input('nominal') and $query->where('nominal',\Request::input('nominal'));
        \Request::input('nama') and $perkiraan->where('nama',\Request::input('pengeluaran'));
        \Request::input('tipe') and $query->where('tipe',\Request::input('tipe'));
        \Request::input('keterangan') and $query->where('keterangan','like','%'.\Request::input('keterangan').'%');
        \Request::input('user_input') and $query->where('user_input',\Request::input('user_input'));
        \Request::input('user_update') and $query->where('user_update',\Request::input('user_update'));
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        \Request::input('delete_at') and $query->where('delete_at',\Request::input('delete_at'));
        \Request::input('ref') and $query->where('ref','like','%'.\Request::input('ref').'%');
        \Request::input('no_jurnal') and $query->where('no_jurnal',\Request::input('no_jurnal'));
        
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'required|string|max:255',
            'id_arus_kas' => 'integer',
            'tanggal' => 'date',
            'id_perkiraan' => 'integer',
            //'id_pembayaran'=>'integer',
            //'id_kas_bank' => 'integer',
            'nominal' => 'required',
            'tipe' => 'integer',
            'keterangan' => 'string',
            'user_input' => 'required|integer',
            'user_update' => 'integer',
            'delete_at' => '',
            'ref' => 'string|max:1',
            'no_jurnal' => 'integer',
            'file'=>'mimes:image',
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

    
    public function arus_kas()
    {
        return $this->belongsTo('App\ArusKas','id_arus_kas');
    }

    public function setArus_kasAttribute($arus_kas) {
      unset($this->attributes['ArusKas']);
    }

    
    
    public function perkiraan()
    {
        return $this->belongsTo('App\Models\Perkiraan','id_perkiraan');
    }

    public function setPerkiraanAttribute($perkiraan) {
      unset($this->attributes['perkiraan']);
    }

    
    
    public function kas_bank()
    {
        return $this->belongsTo('App\Models\KasBank','id_kas_bank');
    }

    public function setKas_bankAttribute($kas_bank) {
      unset($this->attributes['KasBank']);
    }

    
    }
