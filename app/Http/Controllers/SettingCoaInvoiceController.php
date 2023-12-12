<?php

namespace App\Http\Controllers;

use App\Models\Perkiraan;
use App\SettingCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingCoaInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-setting-coa-invoice');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting_coa = SettingCoa::selectRaw('setting_coa.id, setting_coa.jenis as nama, perkiraan.nama as coa')
        ->join('perkiraan', 'perkiraan.id', 'setting_coa.id_perkiraan')
        ->where('keterangan', 'invoice')
        ->get();

        return view ('setting-coa-invoice.index', compact('setting_coa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting_coa = SettingCoa::select('id', 'jenis as nama', 'id_perkiraan')->where('id', $id)->firstOrFail();
        $perkiraan = Perkiraan::select('id', 'nama')->get();

        return view ('setting-coa-invoice.edit', compact('setting_coa', 'perkiraan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $act = SettingCoa::where('id', $id)
        ->update(['id_perkiraan' => $request->id_perkiraan, 'user_update' => Auth::user()->id]);

        message($act, 'Setting COA Invoice berhasil disimpan', 'Setting COA Invoice gagal disimpan');
        return redirect('/setting-coa-invoice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
