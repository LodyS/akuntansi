<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiJurnal extends Model
{
    protected $table ='transaksi_jurnal';
    protected $fillable = ['nama', 'flag_transaksi'];
    protected $primaryKey = 'id';

    public function pk()
    {
        return $this->{$this->primaryKey};
    }
}
