<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class TrialBalanceSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/trial_balance/agustus.xlsx';
        $this->tablename = 'detail_jurnal_satu';
        $this->truncate = false;

        parent::run();
    }
}
