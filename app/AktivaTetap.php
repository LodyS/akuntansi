<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AktivaTetap extends Model
{
    protected $table = 'aktiva_tetap';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'kode',
        'nama',
        'id_kelompok_aktiva',
        'id_unit',
        'penyusutan',
        'id_metode_penyusutan',
        'lokasi',
        'no_seri',
        'tanggal_pemakaian',
        'tanggal_selesai_pakai',
        'tanggal_pembelian',
        'nilai_residu',
        'umur_ekonomis',
        'depreciated',
        'harga_perolehan',
        'penyesuaian',
        'penyusutan_berjalan',
        'tarif',
        'status_penyusutan',
        'status'
    ];
}
