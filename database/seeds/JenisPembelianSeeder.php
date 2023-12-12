<?php

use Illuminate\Database\Seeder;
use App\JenisPembelian;
class JenisPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JenisPembelian::insert([
            [
                'nama' =>'Farmasi',
            ],
            [
                'nama' =>'Logistik',
            ],
            [
                'nama' =>'Adm',
            ]
        ]);
    }
}
