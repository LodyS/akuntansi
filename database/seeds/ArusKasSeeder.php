<?php

use Illuminate\Database\Seeder;
use App\ArusKas;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class ArusKasSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->command->info("Hapus Arus Kas");
        DB::table('arus_kas')->delete();

        $this->file = '/database/excel/ArusKas/ARUS_KAS.xlsx';
        $this->tablename = 'arus_kas';
        $this->mapping = ['id', 'nama', 'tipe','level','urutan','id_induk','jenis','user_input'];
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
