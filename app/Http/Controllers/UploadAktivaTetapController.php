<?php

namespace App\Http\Controllers;
use App\Imports\AktivaTetapImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UploadAktivaTetapController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-upload-aktiva-tetap');
    }

    public function index()
    {
        return view('upload-aktiva-tetap/index');
    }

    public function dowload()
    {
        $file = public_path('excel/format_aktiva_tetap.xlsx');
        return response()->download($file);
    }

    public function store (Request $request)
    {
        $request->validate(['file'=>'required']);

        if ($request->file('file')->getClientOriginalExtension() === 'csv' || $request->file('file')->getClientOriginalExtension() === 'xlsx')
        {
            Excel::import(new AktivaTetapImport(), $request->file('file'));
        }

        if (is_null($request->file('file'))) {
            return redirect('upload-aktiva-tetap/index')->with('danger', 'Gagal import Aktiva Tetap');
        } else if ($request->file('file')->getClientOriginalExtension() === 'csv' || $request->file('file')->getClientOriginalExtension() === 'xlsx'){
            return redirect('upload-aktiva-tetap/index')->with('success', 'Berhasil import Aktiva Tetap');
        } else {
            message(false, '', 'Gagal import Aktiva Tetap karena format file tidak didukung');
            return redirect('upload-aktiva-tetap/index');
        }
    }
}
