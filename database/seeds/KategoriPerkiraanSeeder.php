<?php

use Illuminate\Database\Seeder;
use App\kategori_perkiraan;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class KategoriPerkiraanSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/KategoriPerkiraan/KategoriPerkiraan.xlsx';
        $this->tablename = 'kategori_perkiraan';
        $this->mapping = ['id','kode','nama','status','created_at','updated_at'];
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
