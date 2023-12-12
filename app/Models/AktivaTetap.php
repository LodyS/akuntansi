<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class AktivaTetap extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="aktiva_tetap";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = AktivaTetap::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_user') and $query->where('id_user',\Request::input('id_user'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('id_kelompok_aktiva') and $query->where('id_kelompok_aktiva',\Request::input('id_kelompok_aktiva'));
        \Request::input('id_unit') and $query->where('id_unit',\Request::input('id_unit'));
        \Request::input('penyusutan') and $query->where('penyusutan',\Request::input('penyusutan'));
        \Request::input('id_metode_penyusutan') and $query->where('id_metode_penyusutan',\Request::input('id_metode_penyusutan'));
        \Request::input('lokasi') and $query->where('lokasi','like','%'.\Request::input('lokasi').'%');
        \Request::input('no_seri') and $query->where('no_seri',\Request::input('no_seri'));
        \Request::input('tanggal_pemakaian') and $query->where('tanggal_pemakaian',\Request::input('tanggal_pemakaian'));
        \Request::input('tanggal_selesai_pakai') and $query->where('tanggal_selesai_pakai',\Request::input('tanggal_selesai_pakai'));
        \Request::input('tanggal_pembelian') and $query->where('tanggal_pembelian',\Request::input('tanggal_pembelian'));
        \Request::input('nilai_residu') and $query->where('nilai_residu',\Request::input('nilai_residu'));
        \Request::input('umur_ekonomis') and $query->where('umur_ekonomis',\Request::input('umur_ekonomis'));
        \Request::input('depreciated') and $query->where('depreciated',\Request::input('depreciated'));
        \Request::input('harga_perolehan') and $query->where('harga_perolehan',\Request::input('harga_perolehan'));
        \Request::input('penyesuaian') and $query->where('penyesuaian',\Request::input('penyesuaian'));
        \Request::input('penyusutan_berjalan') and $query->where('penyusutan_berjalan',\Request::input('penyusutan_berjalan'));
        \Request::input('tarif') and $query->where('tarif',\Request::input('tarif'));
        \Request::input('status_penyusutan') and $query->where('status_penyusutan',\Request::input('status_penyusutan'));
        \Request::input('status') and $query->where('status',\Request::input('status'));
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
            'id_user' => 'integer',
            'kode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'id_kelompok_aktiva' => 'integer',
            'id_unit' => 'integer',
            'penyusutan' => 'required|integer',
            'id_metode_penyusutan' => 'integer',
            'lokasi' => 'required|string|max:255',
            'no_seri' => 'required|integer',
            'tanggal_pemakaian' => 'date',
            'tanggal_selesai_pakai' => 'date',
            'tanggal_pembelian' => 'date',
            'nilai_residu' => '',
            'umur_ekonomis' => 'required|integer',
            'depreciated' => 'required|integer',
            'harga_perolehan' => 'required',
            'penyesuaian' => 'required',
            'penyusutan_berjalan' => 'required',
            'tarif' => 'required',
            'status_penyusutan' => 'integer',
            'status' => 'integer',
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

    
    
    public function kelompok_aktiva()
    {
        return $this->belongsTo('App\Models\Kelompok_aktiva','id_kelompok_aktiva');
    }

    public function setKelompok_aktivaAttribute($kelompok_aktiva) {
      unset($this->attributes['kelompok_aktiva']);
    }

    
    
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','id_unit');
    }

    public function setUnitAttribute($unit) {
      unset($this->attributes['unit']);
    }

    
    
    public function metode_penyusutan()
    {
        return $this->belongsTo('App\Models\Metode_penyusutan','id_metode_penyusutan');
    }

    public function setMetode_penyusutanAttribute($metode_penyusutan) {
      unset($this->attributes['metode_penyusutan']);
    }

    
    }
