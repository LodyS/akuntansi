<?php
namespace App\Models;
use App\Voucher;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class Deposit extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="deposit";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Deposit::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_visit') and $query->where('id_visit',\Request::input('id_visit'));
        \Request::input('id_pelanggan') and $query->where('id_pelanggan',\Request::input('id_pelanggan'));
        \Request::input('waktu') and $query->where('waktu',\Request::input('waktu'));
        \Request::input('kredit') and $query->where('kredit',\Request::input('kredit'));
        \Request::input('pemakaian') and $query->where('pemakaian',\Request::input('pemakaian'));
        \Request::input('status') and $query->where('status',\Request::input('status'));
        \Request::input('id_induk') and $query->where('id_induk',\Request::input('id_induk'));
        \Request::input('id_pengembalian_uang') and $query->where('id_pengembalian_uang',\Request::input('id_pengembalian_uang'));
        \Request::input('id_pembayaran') and $query->where('id_pembayaran',\Request::input('id_pembayaran'));
        \Request::input('ref') and $query->where('ref','like','%'.\Request::input('ref').'%');
        \Request::input('id_jurnal') and $query->where('id_jurnal',\Request::input('id_jurnal'));
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
            'id_visit' => 'integer',
            'id_pelanggan' => '',
            'waktu' => '',
            'kredit' => '',
            'pemakaian' => '',
            'status' => 'required|integer',
            'id_induk' => 'integer',
            'id_pengembalian_uang' => 'integer',
            'id_pembayaran' => '',
            'ref' => 'required|string|max:1',
            'id_jurnal' => 'integer',
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


    public function visit()
    {
        return $this->belongsTo('App\Models\Visit','id_visit');
    }

    public function setVisitAttribute($visit) {
      unset($this->attributes['visit']);
    }



    public function induk()
    {
        return $this->belongsTo('App\Models\Induk','id_induk');
    }

    public function setIndukAttribute($induk) {
      unset($this->attributes['induk']);
    }



    public function pengembalian_uang()
    {
        return $this->belongsTo('App\Models\Pengembalian_uang','id_pengembalian_uang');
    }

    public function setPengembalian_uangAttribute($pengembalian_uang) {
      unset($this->attributes['pengembalian_uang']);
    }



    public function jurnal()
    {
        return $this->belongsTo('App\Models\Jurnal','id_jurnal');
    }

    public function scopeKodeVoucher ()
    {
        $tanggal = date('Ymd');
        $voucher = Voucher::selectRaw('substr(kode, 13) +1 as kode')->orderByDesc('id')->first();
        $kode_voucher = isset($voucher) ? "KD.".$tanggal.'.'.$voucher->kode : "KD.".$tanggal.".1";

        return $kode_voucher;
    }

    public function setJurnalAttribute($jurnal) {
      unset($this->attributes['jurnal']);
    }


    }
