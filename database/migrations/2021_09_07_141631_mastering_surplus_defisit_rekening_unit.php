<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MasteringSurplusDefisitRekeningUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # BELUM SELESAI
        // rencana untuk mastering surplus defisit menggunakan kode rekening dan kode unit
        // $listDetail = DB::table('surplus_defisit_detail')->select('id','list_code_rekening','list_code_unit')->get();
        // foreach ($listDetail as $detail) {
        //     $listRekening = explode('&',$detail->list_code_rekening);
        //     $this->info($listRekening);
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
