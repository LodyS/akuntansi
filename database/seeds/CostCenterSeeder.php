<?php

use Illuminate\Database\Seeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class CostCenterSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->command->info("Hapus Setting Surplus Defisit");
        DB::table('setting_surplus_defisit')->delete();

        $this->command->info("Import Setting Surplus Defisit");
        $this->file = '/database/excel/cost-center/cost-center.xlsx';
        $this->tablename = 'setting_surplus_defisit';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
