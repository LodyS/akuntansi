<?php

use Illuminate\Database\Seeder;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class WilayahSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->textOutput = false;

        $this->importProvinsi();
        $this->importKabupaten();
        $this->importKecamatan();
        $this->importKelurahan();
    }

    private function importProvinsi()
    {
        $this->command->info("Hapus Provinsi");
        DB::table('provinsi')->delete();

        $this->command->info("Import Provinsi");
        $this->file = '/data/master/wilayah/provinsi.xls';
        $this->tablename = 'provinsi';
        $this->mapping = ['id', 'kode', 'provinsi'];
        $this->truncate = false;

        parent::run();
    }

    private function importKabupaten()
    {
        $this->command->info("Hapus Kabupaten");
        DB::table('kabupaten')->delete();

        $this->command->info("Import Kabupaten");
        $this->file = '/data/master/wilayah/kabupaten.xls';
        $this->tablename = 'kabupaten';
        $this->mapping = ['id', 'kode', 'kabupaten', 'id_provinsi'];
        $this->truncate = false;

        parent::run();
    }

    private function importKecamatan()
    {
        $this->command->info("Hapus Kecamatan");
        DB::table('kecamatan')->delete();

        $this->command->info("Import Kecamatan");
        $this->file = '/data/master/wilayah/kecamatan.xls';
        $this->tablename = 'kecamatan';
        $this->mapping = ['id', 'kode', 'kecamatan', 'id_kabupaten'];
        $this->truncate = false;

        parent::run();
    }

    private function importKelurahan()
    {
        $this->command->info("Hapus Kelurahan");
        DB::table('kelurahan')->delete();

        $this->command->info("Import Kelurahan");
        $this->file = '/data/master/wilayah/kelurahan.xlsx';
        $this->tablename = 'kelurahan';
        $this->mapping = ['id', 'kode', 'kelurahan', 'id_kecamatan', 'kodepos'];
        $this->truncate = false;

        parent::run();
    }
}
