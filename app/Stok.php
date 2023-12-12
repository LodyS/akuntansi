<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';
    protected $fillable= ['id_packing_barang', 'id_unit', 'hpp', 'hna', 'jumlah_stok'];
}
