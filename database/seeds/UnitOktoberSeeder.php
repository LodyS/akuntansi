<?php
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Database\Seeder;

class UnitOktoberSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Unit");
        DB::table('unit')->delete();
        $this->file = '/database/excel/unit-oktober/unit.xls';
        $this->tablename = 'unit';
        $this->truncate = false;

        parent::run();
    }
}
