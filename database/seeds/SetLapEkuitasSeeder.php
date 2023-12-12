<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class SetLapEkuitasSeeder extends  SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Hapus Set Lap Ekuitas");
        DB::table('set_lap_ekuitas')->delete();

        $this->file = '/database/excel/set-lap-ekuitas/set_lap_ekuitas.xlsx';
        $this->tablename = 'set_lap_ekuitas';
        $this->truncate = false;
        $this->textOutput = false;

        parent::run();
    }
}
