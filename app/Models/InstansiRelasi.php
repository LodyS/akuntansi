<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class InstansiRelasi extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="instansi_relasi";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = InstansiRelasi::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('alamat') and $query->where('alamat','like','%'.\Request::input('alamat').'%');
        \Request::input('telp') and $query->where('telp','like','%'.\Request::input('telp').'%');
        \Request::input('email') and $query->where('email','like','%'.\Request::input('email').'%');
        \Request::input('id_jenis_instansi') and $query->where('id_jenis_instansi',\Request::input('id_jenis_instansi'));

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            //'kode' => 'required|string|max:20|unique:instansi_relasi,kode',
            'kode'=> 'required|string',
            'nama' => 'required|string|max:200',
            'alamat' => 'required|string|max:200',
            'telp' => 'required|string|max:13',
            'email' => 'required|string|max:100|email',
            'id_jenis_instansi_relasi' => 'required|integer',
            'id_termin' => 'required|integer',
            'id_tarif_pajak' => 'required|integer',
            //'id_provinsi' => 'integer',
            //'id_kabupaten' => 'integer',
            //'id_kecamatan' => 'integer',
            //'id_kelurahan' => 'integer',
            'id_perkiraan' => 'integer',
            'rekening' => 'required|string',
            'atas_nama' => 'required|string',
            //'saldo_hutang' => 'integer',
            //'batas_kredit' => 'required|integer',
            'tanggal_hutang' => 'date',
            'jatuh_tempo' => 'date',
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


    public function jenis_instansi()
    {
        return $this->belongsTo('App\Models\Jenis_instansi','id_jenis_instansi');
    }

    public function setJenis_instansiAttribute($jenis_instansi) {
      unset($this->attributes['jenis_instansi']);
    }


    }
