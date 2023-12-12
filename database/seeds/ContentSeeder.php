<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisKelamin;
use App\Models\Agama;
use App\Models\JenisPekerjaan;
use App\Models\KategoriPasien;
use App\Models\StatusPernikahan;
use App\Models\GolonganDarah;


use App\Models\User;
use App\Permission;
use App\Models\ConfigId;

class ContentSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
      $this->importProvinsiNew();
      $this->importKabupatenNew();
      $this->importKecamatanNew();
      $this->importKelurahanNew();
      $this->importJenisKelamin();
      $this->importAgama();
      $this->importProfesi();
      // $this->importKategoriPasien();
      $this->importStatusPernikahan();
      $this->importGolonganDarah();
      $this->configId();
    }

  
    private function importProvinsiNew(){
      $this->command->info("Hapus Provinsi");
      DB::table('provinsi')->delete();
      $fileName = 'data/wilayah/wilayah_sampang/provinsi1.xlsx';
      $this->command->info("Seeding Provinsi");
      
      \Excel::load($fileName,function($reader){
       
        // $reader->dump();
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          // die("hasil = ".$row->count());

          $row->each(function($provinsi) use ($bar){
            // echo ($provinsi['kode']."\n");


            if(isset($provinsi['id'])){
              $data = Provinsi::firstOrNew(array(
                'id'=>$provinsi['id'],
                'kode'=>$provinsi['kode']
              ));
              $data->provinsi=$provinsi['provinsi'];
              $data->flag_aktif='Y';
              $data->save();
            }
            $bar->advance();
          });
          $bar->finish();

        });
      });
      echo "\n\n";
    }

    private function importKabupatenNew(){
      $this->command->info("Hapus Kabupaten");
      DB::table('kabupaten')->delete();
      $fileName = 'data/wilayah/wilayah_sampang/kabupaten1.xlsx';
      $this->command->info("Seeding Kabupaten");
      \Excel::load($fileName,function($reader){
        // $reader->dump();
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($kabupaten) use ($bar){
            // echo ($kabupaten['kode']."\n");
            if(isset($kabupaten['id'])){

              $data = Kabupaten::firstOrNew(array(
                'kode'=>$kabupaten['kode'],
              'id'=>$kabupaten['id']

              ));
              $data->id_provinsi=$kabupaten['id_provinsi'];
              $data->kabupaten=$kabupaten['kabupaten'];
              $data->flag_aktif='Y';
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function importKecamatanNew(){
      $this->command->info("Hapus Kecamatan");
      DB::table('kecamatan')->delete();
      $fileName = 'data/wilayah/wilayah_sampang/kecamatan1.xlsx';
      $this->command->info("Seeding Kecamatan");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($kecamatan) use ($bar){
            if(isset($kecamatan['id'])){

              $data = Kecamatan::firstOrNew(array(
                'kode'=>$kecamatan['kode'],
              'id'=>$kecamatan['id']

              ));
              $data->id_kabupaten=$kecamatan['id_kabupaten'];
              $data->kecamatan=$kecamatan['kecamatan'];
              $data->flag_aktif='Y';
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function importKelurahanNew(){
      $this->command->info("Hapus Kelurahan");
      DB::table('kelurahan')->delete();
      $fileName = 'data/wilayah/wilayah_sampang/kelurahan1.xlsx';
      $this->command->info("Seeding kelurahan");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($kelurahan) use ($bar){
            if(isset($kelurahan['id'])){

              $data = Kelurahan::firstOrNew(array(
                'kode'=>$kelurahan['kode'],
              'id'=>$kelurahan['id']

              ));
              $data->id_kecamatan=$kelurahan['id_kecamatan'];
              $data->kelurahan=$kelurahan['kelurahan'];
              $data->latitude=$kelurahan['latitude'];
              $data->longitude=$kelurahan['longitude'];
              $data->kode_bps=$kelurahan['kode_bps'];
              $data->flag_aktif='Y';
              $data->kodepos=$kelurahan['kode_pos'];
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

     private function importJenisKelamin(){
      $this->command->info("Hapus Jenis Kelamin");
      DB::table('jenis_kelamin')->delete();
      $fileName = 'data/master/jenis_kelamin.xlsx';
      $this->command->info("Seeding Jenis Kelamin");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($jk) use ($bar){
            if(isset($jk['id'])){

              $data =JenisKelamin::firstOrNew(array(
                'jenis_kelamin'=>$jk['jenis_kelamin'],
              'id'=>$jk['id']

              ));
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function importAgama(){
      $this->command->info("Hapus Agama");
      DB::table('agama')->delete();
      $fileName = 'data/master/agama.xlsx';
      $this->command->info("Seeding Agama");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($agama) use ($bar){
            if(isset($agama['id'])){

              $data =Agama::firstOrNew(array(
                'agama'=>$agama['agama'],
              'id'=>$agama['id']

              ));
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

      private function importProfesi(){
      $this->command->info("Hapus Profesi");
      DB::table('jenis_pekerjaan')->delete();
      $fileName = 'data/master/profesi.xlsx';
      $this->command->info("Seeding Profesi");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($profesi) use ($bar){
            if(isset($profesi['id'])){

              $data =JenisPekerjaan::firstOrNew(array(
                'jenis_pekerjaan'=>$profesi['jenis_pekerjaan'],
              'id'=>$profesi['id']

              ));
              $data->keterangan=$profesi['keterangan'];
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function importKategoriPasien(){
      $this->command->info("Hapus Kategori Pasien");
      DB::table('kategori_pasien')->delete();
      $fileName = 'data/master/kategori_pasien.xlsx';
      $this->command->info("Seeding Kategori Pasien");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($kp) use ($bar){
            if(isset($kp['id'])){

              $data =KategoriPasien::firstOrNew(array(
                'kategori'=>$kp['kategori'],
              'id'=>$kp['id']

              ));
               $data->flag_aktif='Y';
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function importStatusPernikahan(){
      $this->command->info("Hapus Status Pernikahan");
      DB::table('status_pernikahan')->delete();
      $fileName = 'data/master/status_pernikahan.xlsx';
      $this->command->info("Seeding status_pernikahan");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($sp) use ($bar){
            if(isset($sp['id'])){

              $data =StatusPernikahan::firstOrNew(array(
                'status_pernikahan'=>$sp['status_pernikahan'],
              'id'=>$sp['id']

              ));
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function importGolonganDarah(){
      $this->command->info("Hapus Golongan Darah");
      DB::table('golongan_darah')->delete();
      $fileName = 'data/master/golongan_darah.xlsx';
      $this->command->info("Seeding Golongan Darah");
      \Excel::load($fileName,function($reader){
        $reader->each(function($row){
          $bar = $this->command->getOutput()->createProgressBar($row->count());
          $row->each(function($sp) use ($bar){
            if(isset($sp['id'])){

              $data =GolonganDarah::firstOrNew(array(
                'golongan_darah'=>$sp['golongan_darah'],
              'id'=>$sp['id']

              ));
              $data->save();

            }
            $bar->advance();
          });
          $bar->finish();
        });
      });
      echo "\n\n";
    }

    private function configId()
    {
     $this->command->info("Hapus Config IDS");
     DB::table('config_ids')->delete();

     $this->command->info("Simpan Config IDS");
     $config = ConfigId::firstOrNew(array(
      'table_source'=>'provinsi',
      'config_name'=>'JAWA_TIMUR',
    )
   );
     $config->config_value='15';
     $config->description='Provinsi Jawa Timur';
     $config->save();

     $config = ConfigId::firstOrNew(array(
      'table_source'=>'kabupaten',
      'config_name'=>'SAMPANG',
    )
   );
     $config->config_value='1';
     $config->description='Kabupaten Sampang';
     $config->save();
   }



}
