<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Transaksi extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="transaksi";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Transaksi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_user') and $query->where('id_user',\Request::input('id_user'));
        \Request::input('id_perkiraan') and $query->where('id_perkiraan',\Request::input('id_perkiraan'));
        \Request::input('tanggal') and $query->where('tanggal',\Request::input('tanggal'));
        \Request::input('keterangan') and $query->where('keterangan','like','%'.\Request::input('keterangan').'%');
        \Request::input('debet') and $query->where('debet',\Request::input('debet'));
        \Request::input('kredit') and $query->where('kredit',\Request::input('kredit'));
        \Request::input('id_periode') and $query->where('id_periode',\Request::input('id_periode'));
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
            'id_user' => 'required|integer',
            'id_perkiraan' => 'integer',
            'tanggal' => 'date',
            'keterangan' => 'string|max:255',
            'debet' => '',
            'kredit' => '',
            'id_periode' => 'integer',
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

    
    public function user()
    {
        return $this->belongsTo('App\Models\User','id_user');
    }

    public function setUserAttribute($user) {
      unset($this->attributes['user']);
    }

    
    
    public function perkiraan()
    {
        return $this->belongsTo('App\Models\Perkiraan','id_perkiraan');
    }

    public function setPerkiraanAttribute($perkiraan) {
      unset($this->attributes['perkiraan']);
    }

    
    
    public function periode()
    {
        return $this->belongsTo('App\Models\Periode','id_periode');
    }

    public function setPeriodeAttribute($periode) {
      unset($this->attributes['periode']);
    }

    
    }
