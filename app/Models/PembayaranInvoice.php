<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class PembayaranInvoice extends Model {
    use RecordSignature;
    
    public $guarded = ["id","created_at","updated_at"];
    protected $table="pembayaran_invoice";
    public $timestamps=true;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = PembayaranInvoice::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('tanggal') and $query->where('tanggal',\Request::input('tanggal'));
        \Request::input('id_pelanggan') and $query->where('id_pelanggan',\Request::input('id_pelanggan'));
        \Request::input('id_invoice') and $query->where('id_invoice',\Request::input('id_invoice'));
        \Request::input('sub_total') and $query->where('sub_total',\Request::input('sub_total'));
        \Request::input('ppn') and $query->where('ppn',\Request::input('ppn'));
        \Request::input('total') and $query->where('total',\Request::input('total'));
        \Request::input('pph_23') and $query->where('pph_23',\Request::input('pph_23'));
        \Request::input('jumlah_bayar') and $query->where('jumlah_bayar',\Request::input('jumlah_bayar'));
        \Request::input('id_bank') and $query->where('id_bank',\Request::input('id_bank'));
        \Request::input('kurang_bayar') and $query->where('kurang_bayar',\Request::input('kurang_bayar'));
        \Request::input('flag_jurnal') and $query->where('flag_jurnal','like','%'.\Request::input('flag_jurnal').'%');
        \Request::input('no_jurnal') and $query->where('no_jurnal',\Request::input('no_jurnal'));
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
            'tanggal' => 'date',
            'id_pelanggan' => '',
            'id_invoice' => 'integer',
            'sub_total' => '',
            'ppn' => '',
            'total' => '',
            'pph_23' => '',
            'jumlah_bayar' => '',
            'id_bank' => 'integer',
            'kurang_bayar' => '',
            'flag_jurnal' => 'string|max:1',
            'no_jurnal' => 'integer',
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

    
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice','id_invoice');
    }

    public function setInvoiceAttribute($invoice) {
      unset($this->attributes['invoice']);
    }

    
    
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank','id_bank');
    }

    public function setBankAttribute($bank) {
      unset($this->attributes['bank']);
    }

    
    }
