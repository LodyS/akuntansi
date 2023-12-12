<?php

use Illuminate\Database\Seeder;
use App\Models\KasBank;

class KasBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KasBank::insert([[
            'kode_bank' => 'Kredit',
            'nama' => 'Kredit',
        ],[
            'kode_bank' => 'Kas',
            'nama' => 'Kas',
        ]]);
    }
}
