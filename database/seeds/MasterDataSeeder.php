<?php

use Illuminate\Database\Seeder;
use App\Models\Ptkp;
use App\Models\IuranBpjs;
use App\Models\JenisCuti;
use App\Models\Agama;
use App\Models\StatusPernikahan;
use App\Models\GolonganDarah;
use App\Models\JenisKelamin;
use App\Models\Profesi;
use App\Models\Bahasa;
use App\Models\Libur;
use App\Models\Jenjang;
use App\Models\HubunganKeluarga;
use App\Models\Perusahaan;
use App\Models\KategoriEvent;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class MasterDataSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->MasterAgama();
        // $this->MasterStatusPernikahan();
        // $this->MasterPtkp();
        // $this->MasterIuranBpjs();
        // $this->MasterCuti();
        // $this->MasterGolonganDarah();
        // $this->MasterJenisKelamin();
        // $this->MasterProfesi();
        // $this->MasterBahasa();
        // $this->MasterLibur();
        // $this->MasterJenjang();
        // $this->MasterHubunganKeluarga();
        //$this->importProvinsi();
        $this->importKabupaten();
        $this->importKecamatan();
        $this->importKelurahan();
        // $this->MasterKategoriEvent();


    }

    private function MasterAgama()
    {
        $this->command->info("Hapus Agama");
        DB::table('agama')->delete();
        $fileName = 'data/master/agama/agama.xlsx';
        $this->command->info("Seeding Agama");
        \Excel::load($fileName, function ($reader) {
            // $reader->dump();
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($agama) use ($bar) {
                    if (isset($agama['id'])) {
                        // die("hasil = ".$row->count());
                        $data = Agama::firstOrNew(array(
                            'id' => $agama['id']
                        ));

                        $data->agama = $agama['agama'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterStatusPernikahan()
    {
        $this->command->info("Hapus Status Pernikahan");
        DB::table('status_pernikahan')->delete();
        $fileName = 'data/master/status_pernikahan/status_pernikahan.xlsx';
        $this->command->info("Seeding Status Pernikahan");
        \Excel::load($fileName, function ($reader) {
            // $reader->dump();
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($sp) use ($bar) {
                    if (isset($sp['id'])) {
                        // die("hasil = ".$row->count());
                        $data = StatusPernikahan::firstOrNew(array(
                            'id' => $sp['id']
                        ));

                        $data->status_pernikahan = $sp['status_pernikahan'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterPtkp()
    {
        $this->command->info("Hapus Ptkp");
        DB::table('ptkp')->delete();
        $fileName = 'data/master/ptkp/master-ptkp.xls';
        $this->command->info("Seeding Ptkp");
        \Excel::load($fileName, function ($reader) {
            // $reader->dump();
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($ptkp) use ($bar) {
                    if (isset($ptkp['id'])) {
                        // die("hasil = ".$row->count());
                        $data = Ptkp::firstOrNew(array(
                            'id' => $ptkp['id']
                        ));

                        $data->id_status_pernikahan = $ptkp['id_status_pernikahan'];
                        $data->status_ptkp = $ptkp['status_ptkp'];
                        $data->kondisi_ptkp = $ptkp['kondisi_ptkp'];
                        $data->nominal_ptkp = $ptkp['nominal_ptkp'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterIuranBpjs()
    {
        $this->command->info("Hapus Iuran Bpjs");
        DB::table('iuran_bpjs')->delete();
        $fileName = 'data/master/iuran_bpjs/master-bpjs.xls';
        $this->command->info("Seeding Iuran Bpjs");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($iuran_bpjs) use ($bar) {
                    if (isset($iuran_bpjs['id'])) {
                        // die("hasil = ".$row->count());
                        $data = IuranBpjs::firstOrNew(array(
                            'id' => $iuran_bpjs['id']
                        ));

                        $data->jenis_jaminan = $iuran_bpjs['jenis_jaminan'];
                        $data->pemberi_kerja = $iuran_bpjs['pemberi_kerja'];
                        $data->pekerja = $iuran_bpjs['pekerja'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterCuti()
    {
        $this->command->info("Hapus Jenis Cuti");
        DB::table('jenis_cuti')->delete();
        $fileName = 'data/master/jenis_cuti/jenis_cuti.xls';
        $this->command->info("Seeding Jenis Cuti");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($jenis_cuti) use ($bar) {
                    if (isset($jenis_cuti['id'])) {
                        // die("hasil = ".$row->count());
                        $data = JenisCuti::firstOrNew(array(
                            'id' => $jenis_cuti['id']
                        ));

                        $data->jenis_cuti = $jenis_cuti['jenis_cuti'];
                        $data->jumlah_hari = $jenis_cuti['jumlah_hari'];
                        $data->status_mengurangi = $jenis_cuti['status_mengurangi'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    // private function MasterLibur()
    // {
    //   $this->command->info("Hapus Libur");
    //   DB::table('libur')->delete();
    //   $fileName = 'data/master/libur/libur.xlsx';
    //   $this->command->info("Seeding Libur");
    //   // $reader->dump();
    //   \Excel::load($fileName,function($reader){
    //     $reader->each(function($row){
    //       $bar = $this->command->getOutput()->createProgressBar($row->count());
    //       $row->each(function($jenis_cuti) use ($bar){
    //         if(isset($jenis_cuti['id'])){
    //                     // die("hasil = ".$row->count());
    //           $data = JenisCuti::firstOrNew(array(
    //             'id'=>$jenis_cuti['id']
    //           ));

    //           $data->jenis_cuti=$jenis_cuti['jenis_cuti'];
    //           $data->jumlah_hari=$jenis_cuti['jumlah_hari'];
    //           $data->status_mengurangi=$jenis_cuti['status_mengurangi'];
    //           $data->save();
    //         }
    //         $bar->advance();
    //       });
    //       $bar->finish();

    //     });
    //   });
    //   echo "\n\n";
    // }

    private function MasterGolonganDarah()
    {
        $this->command->info("Hapus Golongan Darah");
        DB::table('golongan_darah')->delete();
        $fileName = 'data/master/golongan_darah/golongan_darah.xlsx';
        $this->command->info("Seeding Golongan Darah");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = GolonganDarah::firstOrNew(array(
                            'id' => $gd['id']
                        ));

                        $data->golongan_darah = $gd['golongan_darah'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterJenisKelamin()
    {
        $this->command->info("Hapus Jenis Kelamin");
        DB::table('jenis_kelamin')->delete();
        $fileName = 'data/master/jenis_kelamin/jenis_kelamin.xlsx';
        $this->command->info("Seeding Jenis Kelamin");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = JenisKelamin::firstOrNew(array(
                            'id' => $gd['id']
                        ));

                        $data->jenis_kelamin = $gd['jenis_kelamin'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterProfesi()
    {
        $this->command->info("Hapus Profesi");
        DB::table('profesi')->delete();
        $fileName = 'data/master/profesi/profesi.xlsx';
        $this->command->info("Seeding Profesi");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = Profesi::firstOrNew(array(
                            'id' => $gd['id']
                        ));

                        $data->nama_profesi = $gd['profesi'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterBahasa()
    {
        $this->command->info("Hapus Bahasa");
        DB::table('bahasa')->delete();
        $fileName = 'data/master/bahasa/bahasa.xlsx';
        $this->command->info("Seeding Bahasa");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = Bahasa::firstOrNew(array(
                            'id' => $gd['id']
                        ));

                        $data->bahasa = $gd['bahasa'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterLibur()
    {
        $this->command->info("Hapus Libur");
        DB::table('libur')->delete();
        $fileName = 'data/master/libur/libur.xlsx';
        $this->command->info("Seeding Libur");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = Libur::firstOrNew(array(
                            'id' => $gd['id']
                        ));
                        $data->hari = namahari(date('Y-m-d', strtotime($gd['tanggal'])));
                        $data->tanggal = $gd['tanggal'] == 'null' ? null : $gd['tanggal'];
                        $data->keterangan = $gd['keterangan'];
                        $data->flag_cuti_bersama = $gd['flag_cuti_bersama'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterJenjang()
    {
        $this->command->info("Hapus Jengang");
        DB::table('jenjang')->delete();
        $fileName = 'data/master/jenjang/jenjang.xlsx';
        $this->command->info("Seeding Jenjang");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = Jenjang::firstOrNew(array(
                            'id' => $gd['id']
                        ));

                        $data->nama_jenjang = $gd['nama_jenjang'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterHubunganKeluarga()
    {
        $this->command->info("Hapus Hubungan Keluarga");
        DB::table('hubungan_keluarga')->delete();
        $fileName = 'data/master/hubungan_keluarga/hubungan_keluarga.xlsx';
        $this->command->info("Seeding Hubungan Keluarga");
        // $reader->dump();
        \Excel::load($fileName, function ($reader) {
            $reader->each(function ($row) {
                $bar = $this->command->getOutput()->createProgressBar($row->count());
                $row->each(function ($gd) use ($bar) {
                    if (isset($gd['id'])) {
                        // die("hasil = ".$row->count());
                        $data = HubunganKeluarga::firstOrNew(array(
                            'id' => $gd['id']
                        ));

                        $data->hubungan_keluarga = $gd['hubungan_keluarga'];
                        $data->save();
                    }
                    $bar->advance();
                });
                $bar->finish();
            });
        });
        echo "\n\n";
    }

    private function MasterKategoriEvent()
    {
        $this->command->info("Hapus Data Kategori Event");
        DB::table('kategori_event')->delete();

        $this->command->info("Simpan Data Kategori Event");
        $data = array(
            [
                'nama' => 'Eksternal',

            ],
            [
                'nama' => 'Internal',

            ],

        );


        if (DB::table('kategori_event')->get()->count() == 0) {
            $bar = $this->command->getOutput()->createProgressBar(count($data));
            foreach ($data as $a) {
                KategoriEvent::create($a);
                $bar->advance();
            }
            $bar->finish();
        }
    }

    private function perusahaan()
    {
        $this->command->info("Hapus Data Perusahaan");
        DB::table('perusahaan')->delete();

        $this->command->info("Simpan Data Perusahaan");
        $data = array(
            [
                'nama_perusahaan' => 'Morhuman',
                'alamat' => 'Alamat Perusahaan Anda',
                'website' => 'Alamat Website Anda',
                'email' => 'morhuman@gmail.com',
                'logo' => null,
                'toleransi_keterlambatan' => 0,
                'jumlah_pengurangan_gaji' => 0,
                'jumlah_pengurangan_jam' => 0,
                'status_shift' => 'N',
                'link_perusahaan' => 'morhuman.com',
                'jml_jam_kerja' => 8,
                'no_telp' => '081xxxx',

            ],

        );


        if (DB::table('perusahaan')->get()->count() == 0) {
            $bar = $this->command->getOutput()->createProgressBar(count($data));
            foreach ($data as $a) {
                ConfigId::create($a);
                $bar->advance();
            }
            $bar->finish();
        }
    }
}
