<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class KasBank extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="kas_bank";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = KasBank::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode_bank') and $query->where('kode_bank','like','%'.\Request::input('kode_bank').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('keterangan') and $query->where('keterangan','like','%'.\Request::input('keterangan').'%');
        \Request::input('alamat') and $query->where('alamat','like','%'.\Request::input('alamat').'%');
        \Request::input('email') and $query->where('email','like','%'.\Request::input('email').'%');
        \Request::input('telepon') and $query->where('telepon',\Request::input('telepon'));
        \Request::input('fax') and $query->where('fax','like','%'.\Request::input('fax').'%');
        \Request::input('status_aktif') and $query->where('status_aktif','like','%'.\Request::input('status_aktif').'%');
        \Request::input('id_jenis_usaha') and $query->where('id_jenis_usaha',\Request::input('id_jenis_usaha'));
        \Request::input('rekening') and $query->where('rekening','like','%'.\Request::input('rekening').'%');
        \Request::input('kode_pos') and $query->where('kode_pos',\Request::input('kode_pos'));
        \Request::input('negara') and $query->where('negara','like','%'.\Request::input('negara').'%');
        \Request::input('id_user') and $query->where('id_user',\Request::input('id_user'));
        \Request::input('id_perkiraan') and $query->where('id_perkiraan',\Request::input('id_perkiraan'));
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            //'kode_bank' => 'string|max:50',
            //'nama' => 'string|max:250',
            //'keterangan' => 'string|max:250',
            //'alamat' => 'string|max:255',
            'email' => 'string|max:255|email',
            'telepon' => 'integer',
            //'fax' => 'string|max:255',
            //'status_aktif' => 'string|max:1',
            //'id_jenis_usaha' => 'integer',
            //'rekening' => 'string|max:255',
            'kode_pos' => 'integer',
            //'negara' => 'string|max:255',
            //'id_user' => 'integer',
            'id_perkiraan'=>'integer',
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


    public function jenis_usaha()
    {
        return $this->belongsTo('App\Models\Jenis_usaha','id_jenis_usaha');
    }

    public function setJenis_usahaAttribute($jenis_usaha) {
      unset($this->attributes['jenis_usaha']);
    }



    public function user()
    {
        return $this->belongsTo('App\Models\User','id_user');
    }

    public function setUserAttribute($user) {
      unset($this->attributes['user']);
    }


    }
