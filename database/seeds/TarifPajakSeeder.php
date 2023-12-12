<?php

use Illuminate\Database\Seeder;
use App\Models\TarifPajak;

class TarifPajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TarifPajak::insert([[
            'nama_pajak' => 'NT',
            'persentase_pajak' =>0,
        ],
        [
            'nama_pajak' => 'PPN',
            'persentase_pajak' =>10,
        ],
        
        ]);
    }
}
