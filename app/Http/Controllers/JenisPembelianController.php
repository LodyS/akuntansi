<?php
namespace App\Http\Controllers;
use DB;
use App\Models\JenisPembelian;
use Illuminate\Http\Request;
use App\Models\Perkiraan;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class JenisPembelianController extends Controller
{
    public $viewDir = "jenis_pembelian";
    public $breadcrumbs = array('permissions'=>array('title'=>'Jenis-pembelian','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-jenis-pembelian');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $Perkiraan = Perkiraan::select('id', 'nama')->get();
        $jenisPembelian = new JenisPembelian;

        return $this->view("form", compact('jenisPembelian', 'Perkiraan'));
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            $this->validate($request, JenisPembelian::validationRules());
            $act=JenisPembelian::create($request->all());
            DB::commit();
            message($act,'Data Jenis Pembelian berhasil ditambahkan','Data Jenis Pembelian gagal ditambahkan');
            return redirect('jenis-pembelian');
        } catch (Exception $e){
            DB::rollback();
            message(false, 'Data Jenis Pembelian berhasil ditambahkan','Data Jenis Pembelian gagal ditambahkan');
            return redirect('jenis-pembelian');
        }
    }
  
    public function show(Request $request, $kode)
    {
        $jenisPembelian=JenisPembelian::find($kode);
        return $this->view("show",['jenisPembelian' => $jenisPembelian]);
    }

    public function edit(Request $request, $kode)
    {
        $Perkiraan = Perkiraan::all();
        $jenisPembelian=JenisPembelian::find($kode);
        return $this->view( "form", ['jenisPembelian' => $jenisPembelian])->with('Perkiraan', $Perkiraan);
    }

    public function update(Request $request, $kode)
    {
        $jenisPembelian=JenisPembelian::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, JenisPembelian::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $jenisPembelian->update($data);
                return "Record updated";
        }
        $this->validate($request, JenisPembelian::validationRules());

        $act=$jenisPembelian->update($request->all());
        message($act,'Data Jenis Pembelian berhasil diupdate','Data Jenis Pembelian gagal diupdate');

        return redirect('/jenis-pembelian');
    }
 
    public function destroy(Request $request, $kode)
    {
        $jenisPembelian=JenisPembelian::find($kode);
        $act=false;
        try {
            $act=$jenisPembelian->forceDelete();
        } catch (\Exception $e) {
            $jenisPembelian=JenisPembelian::find($jenisPembelian->pk());
            $act=$jenisPembelian->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }
       
    public function loadData()
    {
        $nama = request()->get('nama');
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $dataList = JenisPembelian::select('jenis_pembelian.id', 'jenis_pembelian.nama', 'diskon.nama as diskon', 'pajak.nama as pajak', 
        'materai.nama as materai', 'pembelian.nama as pembelian', 'hutang.nama as hutang')
        ->leftJoin('perkiraan as diskon', 'diskon.id', 'jenis_pembelian.id_perkiraan_diskon')
        ->leftJoin('perkiraan as pajak', 'pajak.id',  'jenis_pembelian.id_perkiraan_pajak')
        ->leftJoin('perkiraan as materai', 'materai.id', 'jenis_pembelian.id_perkiraan_materai')
        ->leftJoin('perkiraan as pembelian', 'pembelian.id',  'jenis_pembelian.id_perkiraan_pembelian')
        ->leftJoin('perkiraan as hutang', 'hutang.id', 'jenis_pembelian.id_perkiraan_hutang');

        if ($nama){
            $dataList->where('jenis_pembelian.nama', 'like', $nama.'%');
        }

        if (request()->get('status') == 'trash') {
            $dataList->onlyTrashed();
        }
        
        return Datatables::of($dataList)->addColumn('nomor',function($kategori){
            return $GLOBALS['nomor']++;
        })->addColumn('action', function ($data) {
                   
            $edit=url("jenis-pembelian/".$data->pk())."/edit";
            $delete=url("jenis-pembelian/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

            return $content;
        })->make(true);
    }
}