<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Budgeting;
use App\BudgetingDetail;
use Illuminate\Http\Request;

class ManajemenAnggaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-manajemen-anggaran');
    }

    public function index ()
    {
        $data = [
            'perkiraan'=>DB::table('perkiraan')->get(['id', 'kode_rekening', 'nama']),
            'unit'=>DB::table('unit')->get(['id', 'code_cost_centre', 'nama']),
        ];

        return view ('manajemen-anggaran/index')->with($data);
    }

    public function store(Request $request)
    {
        $id_user = Auth::user()->id;
        $data = $request->all();
        DB::beginTransaction();

        try {

            $request->validate([
                'nama'=>'required',
                'periode_anggaran'=>'required',
                'id_perkiraan'=>'required',
                'id_unit'=>'required',
                'nominal'=>'required'
            ]);

            $budget = new Budgeting;
            $budget->nama = $request->nama;
            $budget->periode_anggaran = $request->periode_anggaran.'-'.'1';
            $budget->tanggal_input = date('Y-m-d');
            $budget->user_input = $id_user;
            $budget->save();

            $id_budget = $budget->id;
            $jumlah = count($request->id_perkiraan);

            for ($i=0; $i<$jumlah; $i++)
            {
                $nominal = str_replace(',', '', $data['nominal'][$i]);

                $insert = array (
                    'id_budgeting'=>$id_budget,
                    'id_perkiraan'=>$data['id_perkiraan'][$i],
                    'id_unit'=>$data['id_unit'][$i],
                    'nominal'=>$nominal,);

                BudgetingDetail::insert($insert);
            }

            DB::commit();
            message(true, 'Berhasil disimpan', 'Gagal disimpan');
            return redirect('manajemen-anggaran/index');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            message(false, '', 'Gagal disimpan');
            return redirect('manajemen-anggaran/index');
        }
    }
}
