<?php

use Illuminate\Database\Seeder;
use App\Models\Fungsi;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class GroupSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->file = '/database/excel/Group/Grup.xlsx';
        $this->tablename = 'fungsi';
        $this->mapping = ['nama_fungsi'];
        $this->textOutput = false;
        $this->truncate = false;

        parent::run();
    }
}
