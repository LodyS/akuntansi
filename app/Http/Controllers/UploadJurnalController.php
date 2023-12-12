<?php

namespace App\Http\Controllers;
use DB;
use App\Imports\DetailJurnalImport;
use App\Jurnal;
use Illuminate\Routing\ResponseFactory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class UploadJurnalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-upload-jurnal');
    }

    public function index()
    {
        return view('upload-jurnal/index');
    }

    public function dowload()
    {
        $file = public_path('excel/format_jurnal.xlsx');
        return response()->download($file);
    }

    public function store (Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate(['file'=>'required']);

            if ($request->file('file')->getClientOriginalExtension() === 'csv' || $request->file('file')->getClientOriginalExtension() === 'xlsx')
            {
                $kode = jurnal::selectRaw('CONCAT("GJ-", SUBSTR(kode_jurnal, 4)+1) AS kode')
                ->where('kode_jurnal', 'like', 'GJ%')
                ->orderByDesc('id')
                ->first();

                $kode_jurnal = isset($kode->kode) ? $kode->kode : 'GJ-1';

                $jurnal = new jurnal;
                $jurnal->kode_jurnal = $kode_jurnal;
                $jurnal->tanggal_posting = $request->tanggal_posting;
                $jurnal->keterangan = 'Import Excel';
                $jurnal->id_tipe_jurnal =5;
                $jurnal->save();
                Excel::import(new DetailJurnalImport(), $request->file('file'));
            }

            DB::commit();
        } catch (Exception $e){
            DB::rollback();
            message(false, '', 'Gagal import Jurnal');
            return redirect('upload-jurnal/index');
        }

        if (is_null($request->file('file'))) {
            return redirect('upload-jurnal/index')->with('danger', 'Gagal import Jurnal');
        } else if ($request->file('file')->getClientOriginalExtension() === 'csv' || $request->file('file')->getClientOriginalExtension() === 'xlsx'){
            return redirect('upload-jurnal/index')->with('success', 'Berhasil import Jurnal');
        } else {
            message(false, '', 'Gagal import Jurnal karena format file tidak didukung');
            return redirect('upload-jurnal/index');
        }
    }
}
