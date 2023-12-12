<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class SettingPerusahaan extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="setting_perusahaan";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    protected $fillable = ['id', 'kode', 'nama', 'alamat', 'email', 'website', 'telepon', 'fax', 'tanggal_berdiri', 'flag_aktif', 'url', 'kode_pos'];
    public static function findRequested()
    {
        $query = SettingPerusahaan::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('alamat') and $query->where('alamat','like','%'.\Request::input('alamat').'%');
        \Request::input('email') and $query->where('email','like','%'.\Request::input('email').'%');
        \Request::input('website') and $query->where('website','like','%'.\Request::input('website').'%');
        \Request::input('telepon') and $query->where('telepon','like','%'.\Request::input('telepon').'%');
        \Request::input('fax') and $query->where('fax','like','%'.\Request::input('fax').'%');
        \Request::input('url') and $query->where('url','like','%'.\Request::input('url').'%');
        \Request::input('tanggal_berdiri') and $query->where('tanggal_berdiri',\Request::input('tanggal_berdiri'));
        \Request::input('flag_aktif') and $query->where('flag_aktif','like','%'.\Request::input('flag_aktif').'%');
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
            'kode' => 'string|max:200',
            'nama' => 'string|max:200',
            'alamat' => 'string|max:100',
            'email' => 'string|max:100|email',
            'website' => 'string|max:100',
            'telepon' => 'string|max:100',
            'fax' => 'string|max:100',
            'url'=>'string',
            'tanggal_berdiri' => '',
            'flag_aktif' => 'string|max:191',
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
