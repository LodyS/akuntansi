<?php
use App\tipe_pasien;
use Illuminate\Database\Seeder;

class TipePasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tipe_pasien::insert([
            [
                'tipe_pasien' => 'Perusahaan Langganan'
            ],

            [
                'tipe_pasien' => 'Antar Unit'
            ],

            [
                'tipe_pasien' => 'Karyawan'
            ],

            [
                'tipe_pasien' => 'Tunai'
            ],
        ]);
    }
}
