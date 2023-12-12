<?php

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::updateOrCreate(['nama' => 'Radiologi'],['kode' => 'Rad']);
    }
}
