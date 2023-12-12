<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    protected $table = 'jenis_transaksi';
    protected $fillable = ['id_transaksi_jurnal', 'kode', 'urutan', 'level','id_induk', 'tipe'];

    public function pk()
    {
        return $this->{$this->primaryKey};
    }
}
