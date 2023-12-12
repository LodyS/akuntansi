<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class JenisPembelian extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="jenis_pembelian";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = JenisPembelian::select('jenis_pembelian.id', 'jenis_pembelian.nama', 'diskon.nama', 'pajak.nama as perkiraan_pajak', 'materai.nama as perkiraan_materai', 'pembelian.nama as perkiraan_pembelian', 'hutang.nama as perkiraan_hutang')
        ->join('perkiraan as diskon', 'diskon.id', 'jenis_pembelian.id_perkiraan_diskon')
        ->join('perkiraan as pajak', 'pajak.id',  'jenis_pembelian.id_perkiraan_pajak')
        ->join('perkiraan as materai', 'materai.id', 'jenis_pembelian.id_perkiraan_materai')
        ->join('perkiraan as pembelian', 'pembelian.id',  'jenis_pembelian.id_perkiraan_pembelian')
        ->join('perkiraan as hutang', 'hutang.id', 'jenis_pembelian.id_perkiraan_hutang');

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('nama') and $query->where('nama','like','%'.\Request::input('nama').'%');
        \Request::input('perkiraan_diskon') and $query->where('perkiraan_diskon', \Request::input('perkiraan_diskon'));
        \Request::input('perkiraan_pajak') and $query->where('perkiraan_pajak',  \Request::input('perkiraan_pajak'));
        \Request::input('perkiraan_materai') and $query->where('perkiraan_materai', \Request::input('perkiraan_materai'));
        \Request::input('perkiraan_pembelian') and $query->where('perkiraan_pembelian', \Request::input('perkiraan_pembelian'));
        \Request::input('perkiraan_hutang') and $query->where('perkiraan_hutang', \Request::input('perkiraan_hutang'));
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
            'nama' => 'required|string|max:255',
            'id_perkiraan_diskon' => 'integer',
            'id_perkiraan_pajak' => 'integer',
            'id_perkiraan_materai' => 'integer',
            'id_perkiraan_pembelian' => 'integer',
            'id_perkiraan_hutang' => 'integer',
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

    
    public function perkiraan_diskon()
    {
        return $this->belongsTo('App\Models\Perkiraan_diskon','id_perkiraan_diskon');
    }

    public function setPerkiraan_diskonAttribute($perkiraan_diskon) {
      unset($this->attributes['perkiraan_diskon']);
    }

    
    
    public function perkiraan_pajak()
    {
        return $this->belongsTo('App\Models\Perkiraan_pajak','id_perkiraan_pajak');
    }

    public function setPerkiraan_pajakAttribute($perkiraan_pajak) {
      unset($this->attributes['perkiraan_pajak']);
    }

    
    
    public function perkiraan_materai()
    {
        return $this->belongsTo('App\Models\Perkiraan_materai','id_perkiraan_materai');
    }

    public function setPerkiraan_materaiAttribute($perkiraan_materai) {
      unset($this->attributes['perkiraan_materai']);
    }

    
    
    public function perkiraan_pembelian()
    {
        return $this->belongsTo('App\Models\Perkiraan_pembelian','id_perkiraan_pembelian');
    }

    public function setPerkiraan_pembelianAttribute($perkiraan_pembelian) {
      unset($this->attributes['perkiraan_pembelian']);
    }

    
    
    public function perkiraan_hutang()
    {
        return $this->belongsTo('App\Models\Perkiraan_hutang','id_perkiraan_hutang');
    }

    public function setPerkiraan_hutangAttribute($perkiraan_hutang) {
      unset($this->attributes['perkiraan_hutang']);
    }

    
    }
