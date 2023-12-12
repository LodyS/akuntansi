<?php

use Illuminate\Database\Seeder;
use App\metode_penyusutan;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class MetodePenyusutanSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/MetodePenyusutan/METODE_PENYUSUTAN.xlsx';
        $this->tablename = 'metode_penyusutan';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
