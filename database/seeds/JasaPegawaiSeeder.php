<?php
use App\JasaPegawai;
use Illuminate\Database\Seeder;

class JasaPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JasaPegawai::updateOrCreate(['nama'=>'Jasa Pegawai']);
    }
}
