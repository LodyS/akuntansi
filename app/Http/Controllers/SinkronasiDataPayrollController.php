<?php

namespace App\Http\Controllers;

use App\DetailPayroll;
use App\Models\Unit;
use App\Payroll;
use App\SettingCoaPayroll;
use App\Support\ApiMorhuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SinkronasiDataPayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-sinkronasi-data-payroll');
    }

    public function index()
    {
        return view ('sinkronasi-data-payroll.index');
    }

    public function getDataPayroll(Request $request)
    {
        $Api = new ApiMorhuman;
        $Api->transaction_history([
            'tanggal_transaksi' => $request->tanggal_transaksi
        ]);

        $historys = $Api->get();
        if (isset($historys->isActive)) {
            return response()->json($historys);
        }

        return response()->json($historys);
    }

    public function getDataPayroll_old(Request $request)
    {
        $Api = new ApiMorhuman;

        $Api->transaction_history([
            'tanggal_transaksi' => $request->tanggal_transaksi
        ]);

        $historys = $Api->get();
        if (isset($historys->isActive)) {
            return response()->json($historys);
        }

        $fullData = collect($historys->data)->flatMap(function ($history, $index) use ($Api, $request){
            $history->nomor = $index + 1;
            $Api->transaction_list([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'kode_referal' => $history->Kode_Referal,
            ]);
            $lists = $Api->get();

            return collect($lists->data->Detail)->map(function ($list) use ($history, $lists) {
                $list->Pajak = $lists->data->Pajak;
                return collect($list)->merge($history);
            });
        })->toArray();

        return response()->json(['data' => $fullData]);
    }


    public function sinkronPayroll(Request $request)
    {
        if (empty($request->data)) {
            return response()->json(['type'=>'warning', 'message'=>'Tidak ada data untuk disimpan.']);
        }

        try {
            $Api = new ApiMorhuman;
            DB::beginTransaction();

            foreach ($request->data as $history) {
                $unit = Unit::firstOrCreate(
                    ['nama' => $history['Nama Unit']], ['keterangan' => 'from sync payroll morhuman']
                );

                $Api->transaction_list([
                    'tanggal_transaksi' => date("d-m-Y", strtotime($history['Tanggal_Transaksi'])),
                    'kode_referal' => $history['Kode_Referal'],
                ]);
                $lists = $Api->get();

                Payroll::updateOrCreate(
                    ['kode_referal' => $history['Kode_Referal']],
                    [
                        'tanggal_transaksi' => date("Y-m-d", strtotime($history['Tanggal_Transaksi'])),
                        'keterangan' => $history['Keterangan'],
                        'no_rekening' => $history['Nomor_Rekening'],
                        'pemilik_rekening' => $history['Pemilik_Rekening'],
                        'total_tagihan' => $history['Total_Tagihan'],
                        'biaya_adm_bank' => $history['Biaya_Adm_Bank'],
                        'total_uang_diterima' => $history['Total_Uang_Diterima'],
                        'pajak' => $lists->data->Pajak ?? 0,
                        'id_unit' => $unit->id
                    ]
                );

                foreach ($lists->data->Detail as $list) {
                    DetailPayroll::updateOrCreate(
                        [
                            'kode_referal' => $history['Kode_Referal'],
                            'komponen' => $list->Komponen,
                        ],
                        [
                            'nominal' => $list->Nominal,
                            'status' => $list->Status ?? '',
                        ]
                    );

                    SettingCoaPayroll::firstOrCreate(['komponen' => $list->Komponen],['flag_aktif'=>'Y']);
                }
            }
            DB::commit();
            return response()->json(['type'=>'success', 'message'=>'Data berhasil disimpan.']);

        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return response()->json(['type'=>'error', 'message'=>'Data gagal disimpan.','error'=>$th->getMessage()]);
        }
    }

    public function getDataPayrollDetail(Request $request)
    {
        $Api = new ApiMorhuman;
        $Api->transaction_list([
            'tanggal_transaksi' => date("d-m-Y", strtotime($request->Tanggal_Transaksi)),
            'kode_referal' => $request->Kode_Referal,
        ]);
        $lists = $Api->get();
        return response()->json($lists);
    }
}
