<?php

use Illuminate\Database\Seeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use App\transaksi;

class TransaksiSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Transaksi");
        DB::table('transaksi')->delete();
        $this->file = '/database/excel/Transaksi/saldoawal.xlsx';
        $this->tablename = 'transaksi';
        //$this->mapping = ['id','id_user','id_perkiraan','tanggal','keterangan','debet','kredit','id_periode','created_at','updated_at'];
        $this->truncate = false;

        parent::run();
    }
}
