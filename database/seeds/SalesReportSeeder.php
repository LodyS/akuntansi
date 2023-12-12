<?php
use App\SalesReport;
use Illuminate\Database\Seeder;

class SalesReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesReport::insert([
            [
                'nama' => 'Kementerian Kesehatan',
            ],
            [
                'nama' => 'BPJS',
            ],
            [
                'nama' => 'Instansi/Asuransi',
            ],
            [
                'nama' => 'Tunai',
            ]
        ]);
    }
}
