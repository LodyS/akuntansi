<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Perkiraan extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="perkiraan";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Perkiraan::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('id_kategori') and $query->where('id_kategori',\Request::input('id_kategori'));
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('kuantitas') and $query->where('kuantitas','like','%'.\Request::input('kuantitas').'%');
        \Request::input('fungsi') and $query->where('fungsi',\Request::input('fungsi'));
        \Request::input('bagian') and $query->where('bagian',\Request::input('bagian'));
        \Request::input('debet') and $query->where('debet',\Request::input('debet'));
        \Request::input('kredit') and $query->where('kredit',\Request::input('kredit'));
        \Request::input('level') and $query->where('level',\Request::input('level'));
        \Request::input('id_induk') and $query->where('id_induk',\Request::input('id_induk'));
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        \Request::input('type') and $query->where('type',\Request::input('type'));
        \Request::input('delete_at') and $query->where('delete_at',\Request::input('delete_at'));
        \Request::input('flag_sistem') and $query->where('flag_sistem',\Request::input('flag_sistem'));
        \Request::input('kode_rekening') and $query->where('kode_rekening','like','%'.\Request::input('kode_rekening').'%');
        \Request::input('kelompok') and $query->where('kelompok','like','%'.\Request::input('kelompok').'%');
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'string|max:30',
            'id_kategori' => 'integer',
            'nama' => 'string|max:255',
            'fungsi' => 'integer',
            'bagian' => 'integer',
            'debet' => '',
            'kredit' => '',
            'level' => 'integer',
            'id_induk' => 'integer',
            'type' => 'required|integer',
            'delete_at' => '',
            'flag_sistem'=>'',
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

    public function kategori()
    {
        return $this->belongsTo('App\Models\Kategori','id_kategori');
    }

    public function setKategoriAttribute($kategori) {
      unset($this->attributes['kategori']);
    }

    public function induk()
    {
        return $this->belongsTo('App\Models\Perkiraan','id_induk','id');
    }

    public function setIndukAttribute($induk) {
      unset($this->attributes['induk']);
    }

}
