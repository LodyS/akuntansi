<?php
use App\SettingCoaPayrollDua;
use Illuminate\Database\Seeder;

class SettingCoaPayrollDuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingCoaPayrollDua::insert([
            [
                'nama' => 'Pajak',
                'id_perkiraan' =>1,
            ],
            [
                'nama' => 'Biaya ADM',
                'id_perkiraan' =>1,
            ]
        ]);
    }
}
