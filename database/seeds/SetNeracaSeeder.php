<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class SetNeracaSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Set Neraca");
        DB::table('set_neraca')->delete();

        $this->file = '/database/excel/set_neraca/set_neraca.xlsx';
        $this->tablename = 'set_neraca';
        $this->truncate = false;

        parent::run();
    }
}
