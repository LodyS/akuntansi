<?php

use Illuminate\Database\Seeder;
use App\Models\TipeJurnal;
use Carbon\Carbon;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class TipeJurnalSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Tipe Jurnal");
        DB::table('tipe_jurnal')->delete();

        $this->file = '/database/excel/tipe_jurnal/TIPE_JURNAL.xlsx';
        $this->tablename = 'tipe_jurnal';
        $this->mapping = ['id','kode_jurnal','tipe_jurnal','jenis_jurnal'];
        $this->truncate = false;

        parent::run();
    }
}
