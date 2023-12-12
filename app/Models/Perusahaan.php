<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Perusahaan extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="perusahaan";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Perusahaan::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('nama_badan_usaha') and $query->where('nama_badan_usaha','like','%'.\Request::input('nama_badan_usaha').'%');
        \Request::input('nama_unit_usaha') and $query->where('nama_unit_usaha','like','%'.\Request::input('nama_unit_usaha').'%');
        \Request::input('kode_unit_usaha') and $query->where('kode_unit_usaha','like','%'.\Request::input('kode_unit_usaha').'%');
        \Request::input('alamat_perusahaan') and $query->where('alamat_perusahaan','like','%'.\Request::input('alamat_perusahaan').'%');
        \Request::input('kota') and $query->where('kota','like','%'.\Request::input('kota').'%');
        \Request::input('negara_perusahaan') and $query->where('negara_perusahaan','like','%'.\Request::input('negara_perusahaan').'%');
        \Request::input('kode_pos') and $query->where('kode_pos','like','%'.\Request::input('kode_pos').'%');
        \Request::input('telepon_perusahaan') and $query->where('telepon_perusahaan','like','%'.\Request::input('telepon_perusahaan').'%');
        \Request::input('fax_perusahaan') and $query->where('fax_perusahaan','like','%'.\Request::input('fax_perusahaan').'%');
        \Request::input('email_perusahaan') and $query->where('email_perusahaan','like','%'.\Request::input('email_perusahaan').'%');

        \Request::input('id_kelompok_bisnis') and $query->where('id_kelompok_bisnis','like','%'.\Request::input('id_kelompok_bisnis').'%');
        \Request::input('id_jenis_usaha') and $query->where('id_jenis_usaha','like','%'.\Request::input('id_jenis_usaha').'%');
        \Request::input('id_sub_jenis_usaha') and $query->where('id_sub_jenis_usaha','like','%'.\Request::input('id_sub_jenis_usaha').'%');
        \Request::input('id_unit') and $query->where('id_unit','like','%'.\Request::input('id_unit').'%');
        \Request::input('id_sub_unit_usaha') and $query->where('id_sub_unit_usaha','like','%'.\Request::input('id_sub_unit_usaha').'%');
        \Request::input('npwp') and $query->where('npwp','like','%'.\Request::input('npwp').'%');

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'nama_perusahaan' =>'required',
            //'nama_badan_usaha' => 'required|string|max:50',
            //'nama_unit_usaha' => 'required|string|max:50',
            //'kode_unit_usaha' => 'required|string|max:6',
            'alamat_perusahaan' => 'required|string|max:100',
            //'kota' => 'required|string|max:50',
            //'negara_perusahaan' => 'required|string|max:50',
            //'kode_pos' => 'required|string|max:50',
            //'telepon_perusahaan' => 'required|string|max:191',
            //'fax_perusahaan' => 'required|string|max:50',
            //'email_perusahaan' => 'required|string|max:50|email',
            //'id_kelompok_bisnis' => 'integer',
            //'id_jenis_usaha' => 'integer',
            //'id_sub_jenis_usaha' => 'integer',
            //'id_unit'      => 'required|integer',
            //'id_sub_unit_usaha' => 'required|integer',
            //'npwp' => 'required|integer',

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

    }
