<?php

use Illuminate\Database\Seeder;
use App\kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        kelas::updateOrCreate(['nama'=>'Tanpa Kelas']);
        kelas::updateOrCreate(['nama'=>'VVIP']);
        kelas::updateOrCreate(['nama'=>'III']);
        kelas::updateOrCreate(['nama'=>'II']);
        kelas::updateOrCreate(['nama'=>'I']);
    }
}
