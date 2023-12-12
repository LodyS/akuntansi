<?php

use Illuminate\Database\Seeder;
use App\SettingCoa;

class SettingCoaPembayaranOlehPasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingCoa::insert([
            [
                'keterangan' => 'Deposit',
                'jenis' => 'Pembayaran',
                'id_perkiraan' =>17,
                'user_input' =>1
            ],
            [
                'keterangan' => 'Biaya Administrasi',
                'jenis' => 'Pembayaran',
                'id_perkiraan' =>116,
                'user_input' =>1
            ],
            [
                'keterangan' => 'Biaya Materai',
                'jenis' => 'Pembayaran',
                'id_perkiraan' =>118,
                'user_input' =>1
            ],
            [
                'keterangan' => 'Biaya Kirim',
                'jenis' => 'Pembayaran',
                'id_perkiraan' =>17,
                'user_input' =>1
            ],
            [
                'keterangan' => 'Charge',
                'jenis' => 'Pembayaran',
                'id_perkiraan' =>107,
                'user_input' =>1
            ]
        ]);
    }
}
