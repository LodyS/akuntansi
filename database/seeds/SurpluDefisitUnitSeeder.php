<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class SurpluDefisitUnitSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->command->info("Hapus Setting Surplus Defisit");
        //DB::table('setting_surplus_defisit')->delete();

        $this->command->info("Import Surplus Defisit Unit");
        //$this->file = '/database/excel/SettingSurplusDefisit/setting-surplus-defisit.xlsx';
        $this->file = '/database/excel/surplus_defisit_unit/surplus_defisit_unit.ods.';
        $this->tablename = 'surplus_defisit_unit';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
