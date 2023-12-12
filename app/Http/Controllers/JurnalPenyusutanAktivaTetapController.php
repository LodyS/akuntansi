<?php

namespace App\Http\Controllers;
use DB;
use App\Models\AktivaTetap;
use App\Penyusutan;
use App\Models\TipeJurnal;
use App\jurnal;
use Auth;
use App\transaksi;
use App\Models\PeriodeKeuangan;
use App\DetailJurnal;
use App\Models\KelompokAktiva;
use App\Models\Perkiraan;
use Illuminate\Http\Request;

class JurnalPenyusutanAktivaTetapController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jurnal-penyusutan-aktiva-tetap');
    }

    public function index ()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $aktivaTetap = AktivaTetap::get(['id', 'nama']);

        $data = DB::table('penyusutan')
        ->selectRaw('aktiva_tetap.nama AS aktiva, nominal, biaya.nama AS biaya_penyusutan, akumulasi.nama AS akumulasi_penyusutan')
        ->leftJoin('aktiva_tetap', 'aktiva_tetap.id', 'penyusutan.id_aktiva_tetap')
        ->leftJoin('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->leftJoin('perkiraan as biaya', 'biaya.id', 'kelompok_aktiva.biaya_penyusutan')
        ->leftJoin('perkiraan as akumulasi', 'akumulasi.id', 'kelompok_aktiva.akumulasi_penyusutan')
        ->where('ref', 'N')
        ->WhereMonth('tanggal_penyusutan', $bulan)
        ->whereYear('tanggal_penyusutan', $tahun)
        ->simplePaginate(25);

        return view('jurnal-penyusutan-aktiva-tetap/index', compact('aktivaTetap', 'data'));
    }

    public function rekapitulasi (Request $request)
    {
        $bulan = $request->bulan;
        $aktiva_tetap = $request->aktiva_tetap;
        $tahun = $request->tahun;
        $aktivaTetap = AktivaTetap::get(['id', 'nama']);

        $data = DB::table('penyusutan')
        ->selectRaw('aktiva_tetap.nama AS aktiva, nominal, biaya.nama AS biaya_penyusutan, akumulasi.nama AS akumulasi_penyusutan')
        ->leftJoin('aktiva_tetap', 'aktiva_tetap.id', 'penyusutan.id_aktiva_tetap')
        ->leftJoin('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->leftJoin('perkiraan as biaya', 'biaya.id', 'kelompok_aktiva.biaya_penyusutan')
        ->leftJoin('perkiraan as akumulasi', 'akumulasi.id', 'kelompok_aktiva.akumulasi_penyusutan')
        ->where('ref', 'N')
        ->where(function ($query) use ($bulan, $aktiva_tetap, $tahun)
        {
            if (isset($bulan)  && isset($aktiva_tetap)  && isset($tahun))
            {
                $query->WhereMonth('tanggal_penyusutan', $bulan)
                ->where('id_aktiva_tetap', $aktiva_tetap)
                ->whereYear('tanggal_penyusutan', $tahun);
            }
        })
        ->get();

        return view('jurnal-penyusutan-aktiva-tetap/index', compact('data', 'bulan','tahun', 'aktiva_tetap', 'aktivaTetap'));
    }

    public function jurnal (Request $request)
    {
        $aktiva_tetap = $request->aktiva_tetap;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $kredit = Penyusutan::selectRaw('perkiraan.id AS id_perkiraan, perkiraan.nama AS perkiraan,  0 AS debet, SUM(nominal) AS kredit')
        ->leftJoin('aktiva_tetap', 'aktiva_tetap.id', 'penyusutan.id_aktiva_tetap')
        ->leftJoin('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kelompok_aktiva.akumulasi_penyusutan')
        ->where('ref', 'N')
        ->where(function($query) use($aktiva_tetap, $bulan, $tahun)
        {
            if (isset($aktiva_tetap) && isset($bulan) && isset($tahun))
            {
                $query->whereMonth('tanggal_penyusutan', $bulan)
                ->whereYear('tanggal_penyusutan', $tahun)
                ->where('id_aktiva_tetap', $aktiva_tetap);
            }
        });

        $debet = Penyusutan::selectRaw('perkiraan.id AS id_perkiraan, perkiraan.nama AS perkiraan, SUM(nominal) AS debet, 0 AS kredit')
        ->leftJoin('aktiva_tetap', 'aktiva_tetap.id', 'penyusutan.id_aktiva_tetap')
        ->leftJoin('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->leftJoin('perkiraan', 'perkiraan.id', 'kelompok_aktiva.biaya_penyusutan')
        ->where('ref', 'N')
        ->where(function($query) use($aktiva_tetap, $bulan, $tahun)
        {
            if (isset($aktiva_tetap) && isset($bulan) && isset($tahun))
            {
                $query->whereMonth('tanggal_penyusutan', $bulan)
                ->whereYear('tanggal_penyusutan', $tahun)
                ->where('id_aktiva_tetap', $aktiva_tetap);
            }
        })
        ->unionAll($kredit)
        ->get();

        $data = [
            'total_debet'=>$debet->sum('debet'),
            'total_kredit'=>$debet->sum('kredit'),
            'penyusutan'=>Penyusutan::get(['id']),
            'tipe_jurnal'=>TipeJurnal::find(6),  //untuk mendapatan id data jurnal Cash Dishburtment Journal
            'kode_jurnal'=> jurnal::AdjCode(),
            'debet'=>$debet,
            'periode_keuangan'=>PeriodeKeuangan::where('status_aktif', 'Y')->first(),
            'aktiva_tetap'=>$aktiva_tetap
        ];

        return view ('jurnal-penyusutan-aktiva-tetap/jurnal')->with($data);
    }

    public function simpan (Request $request)
    {
        $id_user = Auth::user()->id;
        DB::beginTransaction();

        try {

            if (in_array(null, $request->id_perkiraan))
            {
				message(false, '', 'Maaf tidak bisa input jurnal Penyusutan Aktiva Tetap');
				return back();
            }

            if (isset($request->id_periode))
            {
                $act = new jurnal;
                $act->kode_jurnal = $request->kode_jurnal;
                $act->tanggal_posting = $request->tanggal;
                $act->keterangan = $request->keterangan;
                $act->id_tipe_jurnal = $request->id_tipe_jurnal;
                $act->id_user = $id_user;
                $act->save();

                $id_jurnal = $act->id;
                $data = $request->all();

                for ($i=0; $i<count($data['id_perkiraan']); $i++ ){
                    $insert = array (
                        'id_jurnal'=>$id_jurnal,
                        'id_perkiraan'=>$data['id_perkiraan'][$i],
                        'ref'=>'N',
                        'debet'=>$data['debet'][$i],
                        'kredit'=>$data['kredit'][$i],);

                DetailJurnal::insert($insert); // untuk insert ke detail jurnal
            }

                if (isset($request->id_penyusutan))
                {   // update jika yang dipilih aktiva tetapnya semua
                    DB::table('penyusutan')->whereIn('id', $request->id_penyusutan)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);
                } else { //update jika yang dipilih aktiva tetapnya hanya satu
                    DB::table('penyusutan')->where('id', $request->penyusutan_id)->update(['ref'=>'Y', 'no_jurnal'=>$id_jurnal]);
                } //update tabel penyusutan kolom ref dan no_jurnal

                DB::commit();
                message($act, 'Jurnal Penyusutan Aktiva Tetap berhasil disimpan', '');
                return redirect ('jurnal-penyusutan-aktiva-tetap/index');
            }
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            message(false, '', 'Jurnal Penyusutan Aktiva Tetap Gagal disimpan');
            return redirect ('jurnal-penyusutan-aktiva-tetap/index');
        }
    }
}
