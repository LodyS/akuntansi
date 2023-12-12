<?php

use Illuminate\Database\Seeder;

class UpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(ConfigIdSeeder::class);
    }
}
