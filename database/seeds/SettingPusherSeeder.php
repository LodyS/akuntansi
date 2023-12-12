<?php

use Illuminate\Database\Seeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use App\Models\SettingPusher;

class SettingPusherSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/setting_pusher.xlsx';
        $this->tablename = 'setting_pusher';

        parent::run();
    }
}
