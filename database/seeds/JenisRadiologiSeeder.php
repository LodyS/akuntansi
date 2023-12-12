<?php

use App\Models\JenisRadiologi;
use Illuminate\Database\Seeder;

class JenisRadiologiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JenisRadiologi::insert([[
            'nama' => 'Tanpa X Ray',
        ],[
            'nama' => 'X Ray',
        ]]);
    }
}
