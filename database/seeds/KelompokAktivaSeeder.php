<?php

use Illuminate\Database\Seeder;

use App\Models\KelompokAktiva;

class KelompokAktivaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KelompokAktiva::insert([[
            'kode' => 'INV-ADM',
            'nama' => 'Alat dan Inventarisasi Administrasi',
            'flag_penyusutan' => 'Y',
        ], [
            'kode_bank' => 'INV-MDS',
            'nama' => 'Alat dan Inventarisasi Medis',
            'flag_penyusutan' => 'Y',
        ], [
            'kode_bank' => 'K-ADM',
            'nama' => 'Kendaraan Umum Kantor',
            'flag_penyusutan' => 'Y',
        ]]);
    }
}
