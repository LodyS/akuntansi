<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class DetailJurnalSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Detail Jurnal");
        DB::table('detail_jurnal')->delete();
        $this->file = '/database/excel/detail_jurnal/detail_jurnal_oktober.ods';
        $this->tablename = 'detail_jurnal';
        $this->truncate = false;

        parent::run();
    }
}
