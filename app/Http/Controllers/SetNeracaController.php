<?php
namespace App\Http\Controllers;
use App\Models\SetNeraca;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SetNeracaController extends Controller
{
    public $viewDir = "set_neraca";
    public $breadcrumbs = array('permissions'=>array('title'=>'Set-neraca','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-set-neraca');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $aksi ="create";
        $generate = SetNeraca::selectRaw('max(urutan) as urutan, max(kode) as kode')->where('level', '0')->orWhereNull('level')->first();
        $kode_induk = (isset($generate->kode)) ? $generate->kode + 1 : '1';
        $urutan = (isset($generate->urutan)) ? $generate->urutan +1 :'1';

        $induk = SetNeraca::select('id', 'nama')->get();
        $setNeraca = new setNeraca;
        return $this->view("form", compact('setNeraca', 'induk', 'kode_induk','urutan', 'aksi'));
    }

    public function isi($induk)
    {
        $data = SetNeraca::select('kode')->where('id', $induk)->first();
        $jumlah = SetNeraca::selectRaw('id, kode, level')->where('induk', $induk)->orderByDesc('id')->first();
        $max_kode = SetNeraca::selectRaw("CONCAT((SELECT kode FROM set_neraca WHERE id='$induk' ),'.', '',
        MAX(SUBSTRING_INDEX(kode, '.',-1)+1)) AS kode")
        ->where('induk', $induk)
        ->first();

        $kode = (isset($jumlah->id)) ? $max_kode->kode : $data->kode.'.'.'1' ;

        $levell = (isset($jumlah->id)) ? $jumlah->level +1 : '1';
        $cari_level = SetNeraca::selectRaw('urutan +1 as urutan')->where('induk', $induk)->orderByDesc('id')->first();
        $cek_level = SetNeraca::select('induk')->where('induk', $induk)->first();

        if ($cek_level == null)
        {
            $tambah_level = SetNeraca::selectRaw('level + 1 as level')->where('id', $induk)->first();
            $level = $tambah_level->level;
        } else {
            $level =$jumlah->level;
        }

        if($levell >0)
        {
            $cari_level = SetNeraca::selectRaw('urutan +1 as urutan')->where('induk', $induk)->orderByDesc('id')->first();
            $urutan = (isset($cari_level)) ? $cari_level->urutan : '1';
        }

        return response()->json(['level'=>$level, 'urutan'=>$urutan, 'kode'=>$kode]);
    }

    public function store( Request $request )
    {
        //$this->validate($request, SetNeraca::validationRules());
        if ($request->jenis_neraca == 'Aktiva')

            $proses_urutan = SetNeraca::selectRaw('urutan + 1 AS urutan')
            ->where('jenis_neraca', 'Aktiva')
            ->where('level', 0)
            ->orderByDesc('id')
            ->first();

            $urutan = (isset($proses_urutan)) ? $proses_urutan->urutan : '1';

        if ($request->jenis_neraca == 'Passiva')
        {
            $proses_urutan = SetNeraca::selectRaw('urutan + 1 AS urutan')
            ->where('jenis_neraca', 'Passiva')
            ->where('level', 0)
            ->orderByDesc('id')
            ->first();

            $urutan = (isset($proses_urutan)) ? $proses_urutan->urutan : '1';
        }

        $act= new SetNeraca;
        $act->nama = $request->nama;
        $act->induk = $request->induk;
        $act->kode = $request->kode;
        $act->level = $request->level;
        $act->urutan = ($request->level == 0) ? $urutan : $request->urutan;
        $act->jenis = $request->jenis;
        $act->jenis_neraca = $request->jenis_neraca;
        $act->save();

        message($act,'Data Set Neraca berhasil ditambahkan','Data Set Neraca gagal ditambahkan');
        return redirect('set-neraca');
    }

    public function show(Request $request, $kode)
    {
        $setNeraca=SetNeraca::find($kode);
        return $this->view("show",['setNeraca' => $setNeraca]);
    }

    public function edit(Request $request, $kode)
    {
        $aksi="update";
        $setNeraca=SetNeraca::find($kode);
        $induk = SetNeraca::select('id', 'nama')->get();
        $generate = SetNeraca::selectRaw('max(urutan)+1 as urutan, max(kode)+1 as kode')->where('level', '0')->first();
        $kode_induk = (isset($generate->kode)) ? $generate->kode : 1;
        $urutan = (isset($generate->urutan)) ? $generate->urutan :1;
        return $this->view("form", compact('setNeraca', 'induk', 'urutan', 'kode_induk', 'aksi'));
    }

    public function update(Request $request, $kode)
    {
        $setNeraca=SetNeraca::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, SetNeraca::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $setNeraca->update($data);
                return "Record updated";
            }
        $this->validate($request, SetNeraca::validationRules());
        $act=$setNeraca->update($request->except('tipe'));
        message($act,'Data Set Neraca berhasil diupdate','Data Set Neraca gagal diupdate');
        return redirect('/set-neraca');
    }

    public function destroy(Request $request, $kode)
    {
        $setNeraca=SetNeraca::find($kode);
        $act=false;
        try {
            $act=$setNeraca->forceDelete();
        } catch (\Exception $e) {
            $setNeraca=SetNeraca::find($setNeraca->pk());
            $act=$setNeraca->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = SetNeraca::selectRaw('set_neraca.id, set_neraca.nama as nama, set_neraca.kode as kode, set_neraca.jenis_neraca as jenis_neraca,
        set_neraca.level as level, case when set_neraca.jenis = "-1" then "Pengurang"
        when set_neraca.jenis= "1" then "Penambah" else ""end as jenis, dua.nama as induk')
        ->leftJoin('set_neraca as dua', 'dua.id', 'set_neraca.induk')
        ->orderBy('set_neraca.kode', 'asc');

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
        $edit=url("set-neraca/".$data->pk())."/edit";
        $delete=url("set-neraca/".$data->pk());
        $content = '';
        $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row '
        data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
        $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
        data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

        return $content;
        })->make(true);
    }
}
