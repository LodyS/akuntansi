<?php

use App\Models\AnggaranProfit;
use Illuminate\Database\Seeder;

class AnggaranProfitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnggaranProfit::insert([
            [
                'id' => 1,
                'nama' =>'Rajal Highlight',
            ],
            [
                'id' => 2,
                'nama' =>'Ranap Highlight',
            ],
            [
                'id' => 3,
                'nama' =>'Penunjang Highlight',
            ],
            [
                'id' => 4,
                'nama' =>'Farmasi Highlight',
            ]
        ]);
    }
}
