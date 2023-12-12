<?php

use Illuminate\Database\Seeder;
use App\Models\ConfigId;
class ConfigIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->configId();
    }
    private function configId()
    {
       $this->command->info("Hapus Config IDS");
       DB::table('config_ids')->delete();

       $this->command->info("Simpan Config IDS");
       $data=array(

        [
          'table_source'=>'roles',
          'config_name'=>'ROLE_ADMIN',
          'config_value'=>'1,2',
          'description'=>'Role yang tercatat sebagai Admin',
        ],
        [
          'table_source'=>'roles',
          'config_name'=>'ROLE_DEVELOPER',
          'config_value'=>1,
          'description'=>'Role untuk Developer',
        ],
        [
          'table_source'=>'roles',
          'config_name'=>'ROLE_SUPERADMINISTRATOR',
          'config_value'=>2,
          'description'=>'Role untuk Superadministrator',
        ],

       );

       if(DB::table('config_ids')->get()->count() == 0){
          $bar=$this->command->getOutput()->createProgressBar(count($data));
          foreach($data as $a){
              ConfigId::create($a);
              $bar->advance();
          }
          $bar->finish();
      }
   }
}
