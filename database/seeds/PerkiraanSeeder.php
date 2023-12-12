<?php

use Illuminate\Database\Seeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
//use App\Models\Perkiraan;

class PerkiraanSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Perkiraan");
        DB::table('perkiraan')->delete();

        $this->command->info("Import Perkiraan");
        $this->file = '/database/excel/perkiraan/PERKIRAAN.xlsx';
        $this->tablename = 'perkiraan';
        $this->mapping = ['id','kode','id_kategori','nama','status','alias','multibagian','kuantitas','fungsi','bagian','ukuran','tarif','debet','kredit','flag_detail','level','id_induk','type'];
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
