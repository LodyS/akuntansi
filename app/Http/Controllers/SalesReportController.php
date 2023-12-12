<?php

namespace App\Http\Controllers;
use DB;
use App\Models\SettingPerusahaan;
use App\SalesReportDetail;
use App\Http\Requests\SalesReportDetailRequest;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-sales-report');
    }

    public function index()
    {
        $tanggal_awal = date('01-m-Y');
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));

        $tanggal = date('Y-m-d');
        $setting = SettingPerusahaan::select('nama')->first();
        $data = DB::table('jurnal')
        ->selectRaw('MONTH(jurnal.tanggal_posting) AS bulan_satu, MONTH(sales_report_detail.tanggal) AS bulan_dua,
        (( SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet))/ (100 - sales_report_detail.persentase_dispute))/100 AS BILLED,
        100- sales_report_detail.persentase_dispute AS PERSEN_BILLED,
        (( SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet))/ sales_report_detail.persentase_dispute)/100 AS DISPUTE,
        SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet) AS total')
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('sales_report_rek', 'sales_report_rek.id_perkiraan', 'perkiraan.id')
        ->leftJoin('sales_report_detail', 'sales_report_detail.id_sales_report', 'sales_report_rek.id_sales_report')
        ->where('sales_report_rek.id_sales_report', 3)
        ->where('sales_report_detail.id_sales_report', 3)
        ->whereMonth('sales_report_detail.tanggal', $tanggal)
        ->whereMonth('jurnal.tanggal_posting', $tanggal)
        ->whereYear('sales_report_detail.tanggal', $tanggal)
        ->whereYear('jurnal.tanggal_posting', $tanggal)
        ->groupBy(DB::raw('month(sales_report_detail.tanggal)'))
        ->get();

        return view ('sales-report/index', compact('data', 'setting', 'tanggal_awal', 'tanggal_abis'));
    }

    public function pencarian (Request $request)
    {
        $setting = SettingPerusahaan::select('nama')->first();
        $bulan = $request->bulan;

        $tanggal_awal = ($bulan <10) ? date("01-0$bulan-Y") :date("01-$bulan-Y");
        $tanggal_abis = date('t-m-Y', strtotime($tanggal_awal));

        $data = DB::table('jurnal')
        ->selectRaw('MONTH(jurnal.tanggal_posting) AS bulan_satu, MONTH(sales_report_detail.tanggal) AS bulan_dua,
        (( SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet))/ (100 - sales_report_detail.persentase_dispute))/100 AS BILLED,
        100- sales_report_detail.persentase_dispute AS PERSEN_BILLED,
        (( SUM(detail_jurnal.kredit) - SUM(detail_jurnal.debet))/ sales_report_detail.persentase_dispute)/100 AS DISPUTE,
        SUM(detail_jurnal.kredit)-SUM(detail_jurnal.debet) AS total')
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->leftJoin('perkiraan', 'perkiraan.id', 'detail_jurnal.id_perkiraan')
        ->leftJoin('sales_report_rek', 'sales_report_rek.id_perkiraan', 'perkiraan.id')
        ->leftJoin('sales_report_detail', 'sales_report_detail.id_sales_report', 'sales_report_rek.id_sales_report')
        ->where('sales_report_rek.id_sales_report', 3)
        ->where('sales_report_detail.id_sales_report', 3)
        ->where(function($query) use ($bulan){
            if (isset($bulan)){
                $query->whereMonth('sales_report_detail.tanggal', $bulan)
                ->whereMonth('jurnal.tanggal_posting', $bulan);
            }
        })
        ->whereYear('sales_report_detail.tanggal', $request->tahun)
        ->whereYear('jurnal.tanggal_posting', $request->tahun)
        ->groupBy(DB::raw('month(sales_report_detail.tanggal)'))
        ->get();

        return view ('sales-report/index', compact('data', 'setting', 'tanggal_awal', 'tanggal_abis'));
    }

    public function create()
    {
        $salesReport = DB::table('sales_report')->select('id', 'nama')->get();

        return view('sales-report/form', compact('salesReport'));
    }

    public function store(SalesReportDetailRequest $request)
    {
        DB::beginTransaction();

        try {

            $data = $request->only('id_sales_report', 'tanggal', 'persentase_billed', 'dispute', 'persentanse_dispute');

            $act = SalesReportDetail::create($data);

            DB::commit();
            message($act, 'Berhasil simpan', 'Gagal simpan');
            return redirect('sales-report/index');

        } catch (Exception $e){
            DB::rollback();
        }
    }
}
