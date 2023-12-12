<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class SettingSurplusDefisitSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Setting Surplus Defisit");
        DB::table('setting_surplus_defisit')->delete();

        $this->command->info("Import Setting Surplus Defisit");
        //$this->file = '/database/excel/SettingSurplusDefisit/setting-surplus-defisit.xlsx';
        $this->file = '/database/excel/cost-center/cost_center.xlsx';
        $this->tablename = 'setting_surplus_defisit';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
