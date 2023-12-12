<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class PendapatanJasa extends Model {
    //use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="pendapatan_jasa";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = PendapatanJasa::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('no_bukti_transaksi') and $query->where('no_bukti_transaksi','like','%'.\Request::input('no_bukti_transaksi').'%');
        \Request::input('no_kunjungan') and $query->where('no_kunjungan',\Request::input('no_kunjungan'));
        \Request::input('tanggal') and $query->where('tanggal',\Request::input('tanggal'));
        \Request::input('id_pelanggan') and $query->where('id_pelanggan',\Request::input('id_pelanggan'));
        \Request::input('jenis') and $query->where('jenis','like','%'.\Request::input('jenis').'%');
        \Request::input('type_bayar') and $query->where('type_bayar','like','%'.\Request::input('type_bayar').'%');
        \Request::input('type_pasien') and $query->where('type_pasien','like','%'.\Request::input('type_pasien').'%');
        \Request::input('id_user') and $query->where('id_user',\Request::input('id_user'));
        \Request::input('total_tagihan') and $query->where('total_tagihan',\Request::input('total_tagihan'));
        \Request::input('id_bank') and $query->where('id_bank',\Request::input('id_bank'));
        \Request::input('discharge') and $query->where('discharge','like','%'.\Request::input('discharge').'%');
        \Request::input('waktu_pulang') and $query->where('waktu_pulang',\Request::input('waktu_pulang'));
        \Request::input('ref_discharge') and $query->where('ref_discharge','like','%'.\Request::input('ref_discharge').'%');
        \Request::input('no_jurnal') and $query->where('no_jurnal','like','%'.\Request::input('no_jurnal').'%');
        \Request::input('user_update') and $query->where('user_update',\Request::input('user_update'));
        
        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'keterangan' =>'required|integer',
            'no_bukti_transaksi' => 'required|string|max:50',
            'no_kunjungan' => 'required|integer',
            'tanggal' => 'required|date',
            'id_pelanggan' => 'required',
            'jenis' => 'required|string|max:2',
            'type_bayar' => 'required|string|max:20',
            'type_pasien' => 'required|string|max:20',
            'id_user' => 'required|integer',
            'total_tagihan' => 'required',
            'id_bank' => 'required|integer',
            'discharge' => 'required|string|max:2',
            'waktu_pulang' => 'date',
            'ref_discharge' => 'required|string|max:2',
            'no_jurnal' => 'string|max:50',
            'user_update' => 'integer',
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

    
    
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank','id_bank');
    }

    public function setBankAttribute($bank) {
      unset($this->attributes['bank']);
    }

    
    }
