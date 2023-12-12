<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class SettingSurplusDefisitIndukSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/SettingSurplusDefisit/SEEDER_SURPLUS_DEFSIIT.xlsx';
        $this->tablename = 'setting_surplus_defisit';
        $this->truncate = false;
        //$this->textOutput = false;

        parent::run();
    }
}
