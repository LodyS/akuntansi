<?php

use Illuminate\Database\Seeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use App\Models\Perkiraan;

class NewPerkiraanSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/perkiraan/PERKIRAAN.xlsx';
        $this->tablename = 'perkiraan';
        //$this->truncate = false;

        parent::run();
    }
}
