<?php

use Illuminate\Database\Seeder;
use App\setting_coa;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class SettingCoaSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/excel/SettingCoa/SETTING_COA_FIX.xlsx';
        $this->tablename = 'setting_coa';
        $this->mapping = ['id','keterangan','jenis','id_bank','id_kelompok_aktiva','id_tarif','type_obat','type','type_bayar','type_pasien','id_kelas','id_perkiraan'];
        $this->truncate = true;
        $this->textOutput = false;

        parent::run();
    }
}
