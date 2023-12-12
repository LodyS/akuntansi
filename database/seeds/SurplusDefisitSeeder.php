<?php

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class SurplusDefisitSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Surplus Defisit Detail");
        DB::table('surplus_defisit_detail')->delete();

        $this->command->info("Hapus Surplus Defisit");
        DB::table('surplus_defisit')->delete();

        $this->command->info("Import Surplus Defisit");
        $this->file = '/database/excel/SurplusDefisit/SurplusDefisit.xls';
        $this->tablename = 'surplus_defisit';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();

        $this->command->info("Import Surplus Defisit Detail");
        // $this->file = '/database/excel/SurplusDefisit/SurplusDefisitDetail.xls';
        $this->file = '/database/excel/SurplusDefisit/surplus_defisit_detail_dankode.xls';
        $this->tablename = 'surplus_defisit_detail';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
