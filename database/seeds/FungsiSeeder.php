<?php

use Illuminate\Database\Seeder;
use App\Models\Fungsi;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class FungsiSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->file = '/database/excel/Fungsi/FUNGSI.xlsx';
        $this->tablename = 'fungsi';
        $this->mapping = ['id','nama_fungsi','status_aktif','created_at','updated_at'];
        $this->textOutput = false;
        $this->truncate = false;

        parent::run();
    }
}
