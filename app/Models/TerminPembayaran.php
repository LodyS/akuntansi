<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;

class TerminPembayaran extends Model {
    //use RecordSignature;

    public $guarded = ["id","created_at","updated_at"];
    protected $table="termin_pembayaran";
    public $timestamps=false;
    protected $primaryKey = "id";
    public $incrementing = true;
    public static function findRequested()
    {
        $query = TerminPembayaran::query();

        // search results based on user input
        \Request::input('id') and $query->where('id',\Request::input('id'));
        \Request::input('kode') and $query->where('kode','like','%'.\Request::input('kode').'%');
        \Request::input('termin') and $query->where('termin','like','%'.\Request::input('termin').'%');
        \Request::input('deskripsi') and $query->where('deskripsi','like','%'.\Request::input('deskripsi').'%');
        \Request::input('diskon') and $query->where('diskon',\Request::input('diskon'));
        \Request::input('min_pembayaran') and $query->where('min_pembayaran',\Request::input('min_pembayaran'));
        \Request::input('denda') and $query->where('denda',\Request::input('denda'));
        \Request::input('jumlah_hari') and $query->where('jumlah_hari',\Request::input('jumlah_hari'));

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
            'kode' => 'required|string|max:20|unique:termin_pembayaran,kode',
            'deskripsi' => 'required|string|max:100',
            'termin' => 'required|string|max:100',
            'diskon' => 'required|integer',
            'min_pembayaran' => 'required|integer',
            'denda' => 'required|integer',
            'jumlah_hari' => 'required|integer',
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
