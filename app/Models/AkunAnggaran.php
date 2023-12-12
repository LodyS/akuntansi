<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class AkunAnggaran extends Model {
    use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="akun_anggaran";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = AkunAnggaran::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('tipe') and $query->where('tipe',\Request::input('tipe'));
        \Request::input('level') and $query->where('level',\Request::input('level'));
        \Request::input('urutan') and $query->where('urutan',\Request::input('urutan'));
        \Request::input('id_induk') and $query->where('id_induk',\Request::input('id_induk'));
        \Request::input('keterangan') and $query->where('keterangan',\Request::input('keterangan'));
        \Request::input('id_perkiraan') and $query->where('id_perkiraan',\Request::input('id_perkiraan'));
        \Request::input('user_input') and $query->where('user_input',\Request::input('user_input'));
        \Request::input('user_update') and $query->where('user_update',\Request::input('user_update'));
        \Request::input('user_delete') and $query->where('user_delete',\Request::input('user_delete'));
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        \Request::input('delete_at') and $query->where('delete_at',\Request::input('delete_at'));
        
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'string|max:255',
            'nama' => 'string|max:255',
            //'tipe' => 'integer',
            //'level' => 'integer',
            //'urutan' => 'integer',
            //'id_induk' => 'integer',
            'keterangan'=>'',
            'id_perkiraan' => 'integer',
            'user_input' => 'integer',
            'user_update' => 'integer',
            'user_delete' => 'integer',
            'delete_at' => '',
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

    
    public function induk()
    {
        return $this->belongsTo('App\Models\Induk','id_induk');
    }

    public function setIndukAttribute($induk) {
      unset($this->attributes['induk']);
    }

    
    
    public function perkiraan()
    {
        return $this->belongsTo('App\Models\Perkiraan','id_perkiraan');
    }

    public function setPerkiraanAttribute($perkiraan) {
      unset($this->attributes['perkiraan']);
    }

    
    }
