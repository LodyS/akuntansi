<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Payroll;
use App\Jurnal;
use App\DetailJurnal;
use App\DetailPayroll;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TransaksiPayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-transaksi-payroll');
    }

    public function index()
    {
        return view ('transaksi-payroll/index');
    }

    public function pencarian(Request $request)
    {
        $request->validate([
            'tanggal_transaksi'=>'required',
        ]);

        $tanggal_transaksi = $request->tanggal_transaksi;
        $keterangan = $request->keterangan;

        $data = DB::table('payroll')
        ->selectRaw('payroll.id, tanggal_transaksi, unit.nama as unit, payroll.keterangan, no_rekening')
        ->selectRaw('pemilik_rekening, total_tagihan, biaya_adm_bank, pajak, total_uang_diterima')
        ->leftJoin('unit', 'unit.id', 'payroll.id_unit')
        ->leftJoin('jurnal', 'jurnal.id', 'payroll.id_jurnal')
        ->leftJoin('detail_jurnal', 'detail_jurnal.id_jurnal', 'jurnal.id')
        ->where('tanggal_transaksi', $request->tanggal_transaksi)
        ->when($keterangan, function($query,$keterangan){
            return $query->where('payroll.keterangan',$keterangan);
        })
        ->groupBy('payroll.id_unit', 'id_perkiraan')
        ->orderBy('detail_jurnal.layer', 'detail_jurnal.urutan, perkiraan.kode_rekening')
        ->paginate(100);

        return view ('transaksi-payroll/index', compact('data', 'tanggal_transaksi'));
    }

    public function detail (Request $request)
    {
        $payroll = Payroll::findOrFail($request->id);

        $detail = DB::table('detail_payroll')
        ->selectRaw('komponen, nominal')
        ->where('kode_referal', $payroll->kode_referal)
        ->paginate(100);

        return view ('transaksi-payroll/detail', compact('payroll', 'detail'));
    }

    public function jurnal (Request $request)
    {
        $tanggal_transaksi = $request->tanggal_transaksi;
        $keterangan = $request->keterangan;

        $payroll = Payroll::where('payroll.id', $request->id)
        ->selectRaw('payroll.id, payroll.keterangan, unit.nama as unit, tanggal_transaksi')
        ->leftJoin('unit', 'unit.id', 'payroll.id_unit')
        ->where('flag_jurnal', 'N')
        ->whereNull('id_jurnal')
        ->first();

        $kodeJurnal = Jurnal::gjCode();

        //
        $data = collect(DB::select("SELECT  A.keterangan, A.cost_centre,
        A.id_unit,A.id AS id_perkiraan, A.kode_rekening, A.rekening, A.debet, A.kredit, A.layer, A.urutan
        FROM
        (
        SELECT p.id AS id_payroll, um.id AS id_unit, p.keterangan, u.code_cost_centre AS cost_centre, pk.id, pk.kode_rekening,
        CONCAT(pk.nama,'-', u.nama) AS rekening, SUM(dp.nominal) AS debet, 0 AS kredit , 1 AS layer, 1 AS urutan FROM payroll p
        JOIN detail_payroll dp ON dp.kode_referal=p.kode_referal
        JOIN setting_coa_payroll s ON s.komponen=dp.komponen
        JOIN perkiraan pk ON pk.id=s.id_perkiraan
        LEFT JOIN unit_morhuman um ON um.id=p.id_unit_morhuman
        LEFT JOIN unit u ON u.id_unit_morhuman=um.id
        WHERE p.tanggal_transaksi='$tanggal_transaksi' AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE
        tanggal_transaksi='$tanggal_transaksi')  END

        GROUP BY layer, urutan

        UNION ALL

        SELECT p.id AS id_payroll, um.id AS id_unit, p.keterangan, u.code_cost_centre AS cost_centre, pk.id, pk.kode_rekening,
        CONCAT(pk.nama,'-', u.nama) AS rekening,0 AS debet, SUM(dp.nominal) AS kredit ,  1 AS layer, 2 AS urutan FROM payroll p
        JOIN detail_payroll dp ON dp.kode_referal=p.kode_referal
        JOIN setting_coa_payroll s ON s.komponen=dp.komponen
        JOIN perkiraan pk ON pk.id=s.id_perkiraan
        LEFT JOIN unit_morhuman um ON um.id=p.id_unit_morhuman
        LEFT JOIN unit u ON u.id_unit_morhuman=um.id
        WHERE p.tanggal_transaksi='$tanggal_transaksi'  AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE tanggal_transaksi='$tanggal_transaksi') END

        AND dp.status='pengurangan'
        GROUP BY layer, urutan

        UNION ALL

        SELECT p.id AS id_payroll,'' AS id_unit, p.keterangan, '' AS cost_centre,
        (SELECT pk.id FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Hutang gaji') AS id,
        (SELECT pk.kode_rekening FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Hutang gaji') AS kode_rekening,
        CONCAT((SELECT pk.nama FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Hutang gaji')) AS Rekening,
        0 AS debet, SUM(p.total_uang_diterima)  AS kredit ,  1 AS layer, 3 AS urutan
        FROM payroll p
        WHERE p.tanggal_transaksi='$tanggal_transaksi'  AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE tanggal_transaksi='$tanggal_transaksi')  END
        GROUP BY layer, urutan

        UNION ALL


        SELECT p.id AS id_payroll, um.id AS id_unit, p.keterangan, u.code_cost_centre AS cost_centre,
        (SELECT pk.id FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Pajak') AS id,
        (SELECT pk.kode_rekening FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Pajak') AS kode_rekening,
        CONCAT((SELECT pk.nama FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Biaya Adm'),'-', u.nama) AS Rekening,
        0 AS debet, SUM(p.biaya_adm_bank) AS kredit ,  1 AS layer, 4 AS urutan
        FROM payroll p
        LEFT JOIN unit_morhuman um ON um.id=p.id_unit_morhuman
        LEFT JOIN unit u ON u.id_unit_morhuman=um.id
        WHERE p.tanggal_transaksi='$tanggal_transaksi' AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE tanggal_transaksi='$tanggal_transaksi')  END
        GROUP BY layer, urutan

        UNION ALL


        SELECT p.id AS id_payroll, um.id AS id_unit, p.keterangan, u.code_cost_centre AS cost_centre,
        (SELECT pk.id FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Pajak') AS id,
        (SELECT pk.kode_rekening FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Pajak') AS kode_rekening,
        CONCAT((SELECT pk.nama FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Pajak'),'-', u.nama) AS Rekening,
        0 AS debet, SUM(p.pajak) AS kredit ,  1 AS layer, 5 AS urutan
        FROM payroll p
        LEFT JOIN unit_morhuman um ON um.id=p.id_unit_morhuman
        LEFT JOIN unit u ON u.id_unit_morhuman=um.id
        WHERE p.tanggal_transaksi='$tanggal_transaksi'  AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE tanggal_transaksi='$tanggal_transaksi')  END
        GROUP BY layer, urutan

        UNION ALL


        SELECT p.id AS id_payroll, '' AS id_unit, p.keterangan, '' AS cost_centre,
        (SELECT pk.id FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Hutang gaji') AS id,
        (SELECT pk.kode_rekening FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Hutang gaji') AS kode_rekening,
        CONCAT((SELECT pk.nama FROM setting_coa_payroll_dua sp JOIN perkiraan pk ON pk.id=sp.id_perkiraan WHERE sp.nama='Hutang gaji')) AS rekening,
        SUM(p.total_uang_diterima) AS debet,0  AS kredit ,  2 AS layer, 1 AS urutan
        FROM payroll p
        WHERE p.tanggal_transaksi='$tanggal_transaksi'  AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE tanggal_transaksi='$tanggal_transaksi')   END
        GROUP BY layer, urutan

        UNION ALL

        SELECT p.id AS id_payroll, '' AS id_unit, p.keterangan, '' AS cost_centre,
        (SELECT id FROM perkiraan WHERE id=4) AS id,
        (SELECT kode_rekening FROM perkiraan WHERE id=4) AS kode_rekening,
        (SELECT nama FROM perkiraan WHERE id=4) AS rekening,
        0 AS debet,  SUM(p.total_uang_diterima)  AS kredit ,  2 AS layer, 2 AS urutan
        FROM payroll p
        WHERE p.tanggal_transaksi='$tanggal_transaksi' AND CASE WHEN '' <> NULL THEN p.keterangan = ''
        ELSE
        p.keterangan IN (SELECT keterangan FROM payroll WHERE tanggal_transaksi='$tanggal_transaksi')  END
        GROUP BY layer, urutan) A

        ORDER BY layer, urutan"));


        $debet = $data->sum('debet');
        $kredit = $data->sum('kredit');

        return view ('transaksi-payroll/jurnal', compact('data', 'payroll', 'kodeJurnal', 'debet', 'kredit', 'tanggal_transaksi', 'keterangan'));
    }

    public function store (Request $request)
    {
		$id_user = Auth::user()->id;
		DB::beginTransaction();

        $keterangan = $request->keterangan_payroll;

        $validator = Validator::make($request->all(), [
            'debet'=>'required',
            'kredit'=>'required',
            'kode_jurnal'=>'required',
            'tanggal'=>'required',
            'keterangan'=>'required',
            'id_perkiraan'=>'required',
        ]);

		try {

            if ($request->balance == 0)
            {
                $success = true;
			    $act = new Jurnal;
			    $act->kode_jurnal = $request->kode_jurnal;
			    $act->tanggal_posting = $request->tanggal;
			    $act->keterangan = $request->keterangan;
			    $act->id_tipe_jurnal = 5;
			    $act->id_user = $id_user;
			    $act->save();

			    $id_jurnal = $act->id;
			    $data = $request->all();

			    for ($i=0; $i<count($data['id_perkiraan']); $i++)
                {
				    $insert = array (
					    'id_jurnal'=>$id_jurnal,
					    'id_perkiraan'=>$data['id_perkiraan'][$i],
					    'debet'=>$data['debet'][$i],
					    'kredit'=>$data['kredit'][$i],
					    'ref'=>'N',);

				    DetailJurnal::create($insert);
                }


                if ($keterangan == null)
                {
                    DB::table('payroll')->where('tanggal_transaksi', $request->tanggal_transaksi)
                    ->update([
                        'id_jurnal'=>$id_jurnal,
                        'flag_jurnal'=>'Y'
                    ]);

                } else {
                    DB::table('payroll')->where('tanggal_transaksi', $request->tanggal_transaksi)
                    ->where('keterangan', $keterangan)
                    ->update([
                        'id_jurnal'=>$id_jurnal,
                        'flag_jurnal'=>'Y'
                    ]);
                }

			}
			DB::commit();
		} catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Invalid data');
            $success =false;
		}

        if ($success == true){
            return redirect('transaksi-payroll/index')->with('success', 'Jurnal Payroll Berhasil disimpan');
        } else if ($success == false){
            return redirect('transaksi-payroll/index')->with('danger', 'Jurnal Payroll Gagal simpan karena error sistem');
        }
	}
}
