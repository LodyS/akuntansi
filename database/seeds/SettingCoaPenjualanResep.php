<?php

use Illuminate\Database\Seeder;
use App\SettingCoa;

class SettingCoaPenjualanResep extends Seeder
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
                'keterangan' => 'Pendapatan Obat',
                'type_obat' => 'Resep',
                'id_perkiraan' =>27,
                'user_input' =>1
            ],

            [
                'keterangan' => 'Pendapatan Obat',
                'type_obat' => 'Diskon',
                'id_perkiraan' =>29,
                'user_input' =>1
            ],

            [
                'keterangan' => 'Pendapatan Obat',
                'type_obat' => 'Pajak',
                'id_perkiraan' =>54,
                'user_input' =>1
            ]

        ]);
    }
}
