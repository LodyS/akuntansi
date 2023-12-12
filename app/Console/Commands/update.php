<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use App\Models;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Validator;
class update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbarui Aplikasi Secara Otomatis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("");
        $this->info("==================================================");
        $this->info("");
        $this->info("Selamat Datang di Menu Pembaruan Aplikasi");
        $this->info("Silakan untuk menunggu proses pembaruan hingga selesai");
        $this->info("");
        $this->info("==================================================");
        sleep(2);
        $this->info("");
        $this->info("Requirement Sistem");
        $headers1 = ['Requirement','Minimum','Recommended'];
        $array1 =array
        (
        array("Web Service","Apache","Apache"),
        array("PHP Version","7.2","Latest Version"),
        array("MYSQL","5.1","Latest Version"),
        array("Composer","1.9.1","Latest Version"),
        array("Browser",'Chrome, Firefox','Chrome'),
        );
        $this->table($headers1, $array1);
        sleep(10);
        $this->info("");
        $this->info("==================================================");
        $this->info("");
        $this->info("Melakukan Pembaruan Aplikasi ....");
        $this->info("");
        $this->info("==================================================");
        sleep(2);
        $this->info("");
        $this->info("Berikut Informasi Kegiatan / Aktivitas Pembaruan");
        $headers2 = ['Nama Aktivitas','Perkiraan Waktu (Menit)'];
        $array2 =array
        (
        array("Penyesuaian Environment","0-5"),
        array("Pembaruan Aplikasi","0-2"),
        array("Pembaruan Database","0-2")

        );
        $this->table($headers2, $array2);
        $this->info("");
        $this->info("Pengambilan Data Environment Local");
        $process1 = new Process(['composer','config','--global','process-timeout','2000']);

        try {
         $process1->mustRun();

         // echo $process1->getOutput();
     } catch (ProcessFailedException $exception1) {
         echo $exception1->getMessage();
         exit();
     };
     $process2 = new Process(['composer','install']);
     try {
         $process2->mustRun();

         // echo $process2->getOutput();
     } catch (ProcessFailedException $exception2) {
         echo $exception2->getMessage();
         exit();
     };
     $process3 = new Process(['composer','dump-autoload']);
     try {
         $process3->mustRun();

         // echo $process2->getOutput();
     } catch (ProcessFailedException $exception3) {
         echo $exception3->getMessage();
         exit();
     };
     $this->info("Melakukan Penyesuaian Environment ....");
     $this->output->progressStart(100);

     for ($i = 0; $i < 100; $i++) {
        sleep(0.2);

         $this->output->progressAdvance();
     }

     $this->output->progressFinish();
     sleep(5);
     $this->info("Penyesuaian Environment Selesai");
     $this->info("Memulai Pembaruan Aplikasi ....");
     sleep(5);
     $process5 = new Process(['php','artisan','vendor:publish','--all']);
     try {
         $process5->mustRun();

         // echo $process2->getOutput();
     } catch (ProcessFailedException $exception5) {
         echo $exception5->getMessage();
         exit();
     };
     $process6 = new Process(['git','checkout','--','.']);
     try {
         $process6->mustRun();

         // echo $process2->getOutput();
     } catch (ProcessFailedException $exception6) {
         echo $exception6->getMessage();
         exit();
     };
     $process7 = new Process(['git','clean','-f','-d']);
     try {
         $process7->mustRun();

         // echo $process2->getOutput();
     } catch (ProcessFailedException $exception7) {
         echo $exception7->getMessage();
         exit();
     };
     Artisan::call('cache:clear');
     Artisan::call('config:clear');
     Artisan::call('route:clear');
     Artisan::call('view:clear');
      $this->output->progressStart(100);

     for ($i = 0; $i < 100; $i++) {
        sleep(0.2);
         $this->output->progressAdvance();
     }

     $this->output->progressFinish();
     sleep(5);
     $this->info("Pembaruan Aplikasi Selesai");
     $this->info("");
     $this->info("Memulai Pembaruan Database ....");
     Artisan::call('cache:clear');
     Artisan::call('config:clear');
     $artisans = Artisan::call('migrate');
     Artisan::call('key:generate');
     Artisan::call('config:cache');
     $this->output->progressStart(100);

     for ($i = 0; $i < 100; $i++) {
         sleep(0.2);

         $this->output->progressAdvance();
     }

     $this->output->progressFinish();
     $this->info("Pembaruan Database Selesai");
     $this->info("");
     $this->info("Penyelesaian Semua Pembaruan Data");
     Artisan::call('cache:clear');
     Artisan::call('config:clear');
     Artisan::call('config:cache');
     $process0 = new Process(['php','artisan','db:seed','--class=UpdateSeeder']);

            try {
             $process0->mustRun();
             } catch (ProcessFailedException $exception0) {
             echo $exception0->getMessage();
             exit();
         };
     $this->info("");
     $this->info("");
        $this->info("");
        $this->info("==================================================");
        $this->info("");
        $this->info("Selamat , Aplikasi Sudah Di Perbarui");
        $this->info("");
        $this->info("==================================================");
        $this->info("");
        $this->info("Berikut Merupakan Informasi Terkait Akses Aplikasi");
        $headers3 = ['Nama','Role','Email','Username'];
    $users3 = \App\Models\User::select(\DB::raw('users.name,roles.display_name as role_name,users.email,users.username'))
    ->join('role_user','role_user.user_id','=','users.id')
    ->join('roles','roles.id','=','role_user.role_id')->get();

    $this->table($headers3, $users3);
    sleep(20);
    $this->info("");
    $this->info("Proses Pembaruan Sudah Selesai");
    $this->info("Terima Kasih");
    }
}
