<?php

use Illuminate\Database\Seeder;
use App\JenisTransaksi;

class JenisTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JenisTransaksi::insert([
            [
            'nama' =>'Penjualan',
            ],
            [
            'nama' =>'Pembelian',
            ],
            [
            'nama' =>'Retur Penjualan',
            ],
            [
            'nama' =>'Return Pembelian',
            ],
            [
            'nama' =>'Saldo Awal',
            ]
        ]);
    }
}
