<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(WilayahSeeder::class);
        $this->call(ConfigIdSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(KelasSeeder::class);
        $this->call(TipePasienSeeder::class);
        $this->call(TipeJurnalSeeder::class);
        $this->call(JenisTransaksiSeeder::class);
        $this->call(KasBankSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(FungsiSeeder::class);
        $this->call(ArusKasSeeder::class);
        $this->call(MetodePenyusutanSeeder::class);
        $this->call(TarifPajakSeeder::class);
        $this->call(KategoriPerkiraanSeeder::class);
        $this->call(PerkiraanSeeder::class);
        $this->call(SettingCoaSeeder::class);
        $this->call(TransaksiSeeder::class);
        $this->call(AnggaranProfitSeeder::class);
        // $this->call(LayananSeeder::class);
        // $this->call(TarifSeeder::class);
        // $this->call(PelangganSeeder::class);
        // $this->call(PendapatanJasaSeeder::class);
        // $this->call(DetailPendapatanJasaSeeder::class);
        // $this->call(TagihanSeeder::class);


    }
}
