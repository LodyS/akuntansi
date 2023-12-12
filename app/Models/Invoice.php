<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\SoftDeletes;
class Invoice extends Model {
    use RecordSignature;
    use SoftDeletes;
    public $guarded = ["id","created_at","updated_at"];
    protected $table="invoice";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = Invoice::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('id_termin_pembayaran') and $query->where('id_termin_pembayaran',\Request::input('id_termin_pembayaran'));
        \Request::input('id_pelanggan') and $query->where('id_pelanggan',\Request::input('id_pelanggan'));
        \Request::input('number') and $query->where('number','like','%'.\Request::input('number').'%');
        \Request::input('invoice_date') and $query->where('invoice_date',\Request::input('invoice_date'));
        \Request::input('payment') and $query->where('payment','like','%'.\Request::input('payment').'%');
        \Request::input('due_date') and $query->where('due_date',\Request::input('due_date'));
        \Request::input('pesan') and $query->where('pesan','like','%'.\Request::input('pesan').'%');
        \Request::input('total') and $query->where('total',\Request::input('total'));
        \Request::input('ppn') and $query->where('ppn',\Request::input('ppn'));
        \Request::input('subtotal') and $query->where('subtotal',\Request::input('subtotal'));
        \Request::input('pph_23') and $query->where('pph_23',\Request::input('pph_23'));
        \Request::input('status') and $query->where('status',\Request::input('status'));
        \Request::input('flag_cetak') and $query->where('flag_cetak','like','%'.\Request::input('flag_cetak').'%');
        \Request::input('flag_jurnal') and $query->where('flag_jurnal','like','%'.\Request::input('flag_jurnal').'%');
        \Request::input('id_jurnal') and $query->where('id_jurnal',\Request::input('id_jurnal'));
        \Request::input('user_input') and $query->where('user_input',\Request::input('user_input'));
        \Request::input('user_update') and $query->where('user_update',\Request::input('user_update'));
        \Request::input('user_delete') and $query->where('user_delete',\Request::input('user_delete'));
        \Request::input('created_at') and $query->where('created_at',\Request::input('created_at'));
        \Request::input('updated_at') and $query->where('updated_at',\Request::input('updated_at'));
        \Request::input('deleted_at') and $query->where('deleted_at',\Request::input('deleted_at'));

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'id_termin_pembayaran' => 'required|integer',
            'id_pelanggan' => 'required',
            'number' => 'required|string|max:225',
            'invoice_date' => 'required|date',
            'payment' => 'required|string|max:225',
            'due_date' => 'required',
            'pesan' => 'required|string',
            'total' => 'required',
            'ppn' => '',
            'subtotal' => '',
            'pph_23' => '',
            'status' => 'integer',
            'flag_cetak' => 'string|max:1',
            'flag_jurnal' => 'string|max:1',
            'id_jurnal' => 'integer',
            'user_input' => 'integer',
            'user_update' => 'integer',
            'user_delete' => 'integer',
            'deleted_at' => '',
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


    public function termin_pembayaran()
    {
        return $this->belongsTo('App\Models\Termin_pembayaran','id_termin_pembayaran');
    }

    public function setTermin_pembayaranAttribute($termin_pembayaran) {
      unset($this->attributes['termin_pembayaran']);
    }



    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan','id_pelanggan');
    }

    public function setPelangganAttribute($pelanggan) {
      unset($this->attributes['pelanggan']);
    }



    public function jurnal()
    {
        return $this->belongsTo('App\Models\Jurnal','id_jurnal');
    }

    public function setJurnalAttribute($jurnal) {
      unset($this->attributes['jurnal']);
    }


    }
