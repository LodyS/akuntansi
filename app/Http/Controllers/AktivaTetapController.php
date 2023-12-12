<?php
namespace App\Http\Controllers;
use DB;
use App\Models\AktivaTetap;
use Illuminate\Http\Request;
use App\metode_penyusutan;
use App\Models\KelompokAktiva;
use App\Models\Perkiraan;
use App\Models\Unit;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class AktivaTetapController extends Controller
{
    public $viewDir = "aktiva_tetap";
    public $breadcrumbs = array('permissions'=>array('title'=>'Aktiva-tetap','link'=>"#",'active'=>false,'display'=>true),);

    public function __construct()
    {
        $this->middleware('permission:read-aktiva-tetap');
    }

    public function index()
    {
        return $this->view( "index");
    }

    public function create()
    {
        $data = [
            'KelompokAktiva'=>KelompokAktiva::get(['id', 'nama']),
            'Unit'=>Unit::get(['id', 'nama']),
            'MetodePenyusutan'=>metode_penyusutan::get(['id', 'nama']),
            'lastCode'=> AktivaTetap::selectRaw("concat('M-', substr(kode,3)+1) as lastCode")->orderByDesc('id')->first(),
            'kode'=>null,
            'aktivaTetap'=>new AktivaTetap
        ];

        return $this->view("form")->with($data);
    }

    public function isiKode ($id)
    {
        $kelompok_aktiva = KelompokAktiva::selectRaw("concat(kode, '-') as kode")->where('id', $id)->first();
        $kode = $kelompok_aktiva->kode;
        $panjang_kata = strlen($kode);
        $panjang = $panjang_kata +1;
        $kode_pertama = $kode.'1';

        $data = DB::table('aktiva_tetap')
        ->selectRaw("CASE WHEN id is null THEN '$kode_pertama' else CONCAT('$kode', SUBSTR(kode, '$panjang')+1) END AS kode")
        ->where('kode', 'like', $kode.'%')
        ->orderByDesc('id')
        ->first();

        echo json_encode($data);
        exit;
    }

    public function store( Request $request )
    {
        DB::beginTransaction();

        try {
            //$this->validate($request, AktivaTetap::validationRules());

            $tariff = str_replace('.','', $request->tarif);
            $tarif = str_replace(',', '.', $tariff);
            $nilai_residuu = str_replace('.', '', $request->nilai_residu);
            $nilai_residu = str_replace(',', '.', $nilai_residuu);
            $harga_perolehann = str_replace('.', '', $request->harga_perolehan);
            $harga_perolehan = str_replace(',', '.', $harga_perolehann);
            $penyesuaiann = str_replace('.', '', $request->penyesuaian);
            $penyesuaian = str_replace('.', '', $penyesuaiann);

            $tanggal_selesai = DB::select("select DATE_ADD('$request->tanggal_pemakaian', INTERVAL $request->umur_ekonomis MONTH) AS pakai");
            $tanggal = json_encode($tanggal_selesai);
            $tanggal_akhir = substr($tanggal, 11,10);
            $flag_penyusutan = KelompokAktiva::select('flag_penyusutan')->where('id', $request->id_kelompok_aktiva)->first();
            $akumulasi_penyusutan = $tarif * $request->depreciated;
            $flag_penyusutan = $flag_penyusutan->flag_penyusutan;


            $act = new AktivaTetap;
            $act->id_user = $request->id_user;
            $act->kode = $request->kode;
            $act->nama = $request->nama;
            $act->id_kelompok_aktiva = $request->id_kelompok_aktiva;
            $act->id_unit = $request->id_unit;
            $act->penyusutan = ($flag_penyusutan == 'Y') ? 1 : 0;
            $act->id_metode_penyusutan = $request->id_metode_penyusutan;
            $act->lokasi = $request->lokasi;
            $act->no_seri = $request->no_seri;
            $act->tanggal_pemakaian = $request->tanggal_pemakaian;
            $act->tanggal_selesai_pakai = $tanggal_akhir;
            $act->tanggal_pembelian = $request->tanggal_pembelian;
            $act->nilai_residu = $nilai_residu;
            $act->umur_ekonomis = $request->umur_ekonomis;
            $act->depreciated = $request->depreciated;
            $act->harga_perolehan = $harga_perolehan;
            $act->penyesuaian = $penyesuaian;
            $act->penyusutan_berjalan = $akumulasi_penyusutan;
            $act->tarif = $tarif;
            $act->status_penyusutan = ($flag_penyusutan == 'Y') ? 1 : 3;
            $act->save();

            message($act,'Data Aktiva Tetap berhasil ditambahkan','Data Aktiva Tetap gagal ditambahkan');
            DB::commit();
            return redirect('aktiva-tetap');
        }
        catch (Exception $e){
            DB::rollback();
            message(false,'Data Aktiva Tetap berhasil ditambahkan','Data Aktiva Tetap gagal ditambahkan');
            return redirect('aktiva-tetap');
        }
    }

    public function show(Request $request, $kode)
    {
        $aktivaTetap=AktivaTetap::find($kode);
        return $this->view("show",['aktivaTetap' => $aktivaTetap]);
    }

    public function detail(Request $request)
    {
        $master = AktivaTetap::selectRaw("penyusutan.id, aktiva_tetap.nama, kelompok_aktiva.nama as kelompok_aktiva")
        ->selectRaw('tanggal_pembelian, aktiva_tetap.harga_perolehan, nilai_residu, umur_ekonomis, aktiva_tetap.kode')
        ->leftJoin('penyusutan', 'penyusutan.id_aktiva_tetap', 'aktiva_tetap.id')
        ->leftJoin('kelompok_aktiva', 'kelompok_aktiva.id', 'aktiva_tetap.id_kelompok_aktiva')
        ->where('id_aktiva_tetap', $request->id)
        ->firstOrFail();

        $statement = DB::statement("SET @nilai_buku := (SELECT SUM(harga_perolehan) - SUM(penyusutan_berjalan) - SUM(penyusutan.nominal)
        AS nilai_buku FROM penyusutan
        LEFT JOIN aktiva_tetap ON aktiva_tetap.id = penyusutan.id_aktiva_tetap
        WHERE id_aktiva_tetap='$request->id' AND penyusutan.id='$master->id')");

        $barisTiga = AKtivaTetap::selectRaw('aktiva_tetap.nama, YEAR(tanggal_penyusutan) AS tahun')
        ->selectRaw('MONTH(tanggal_penyusutan) AS bulan, penyusutan.nominal AS penyusutan')
        ->selectRaw("CASE WHEN penyusutan.id <> '$master->id' THEN CAST(@nilai_buku := @nilai_buku - penyusutan.nominal AS decimal(20,2))
        WHEN penyusutan.id = '$master->id'  THEN CAST(@nilai_buku := @nilai_buku AS decimal(20,2)) END AS nilai_buku")
        ->leftJoin('penyusutan', 'aktiva_tetap.id', 'penyusutan.id_aktiva_tetap')
        ->where('id_aktiva_tetap', $request->id)
        ->get();

        $barisDua = AktivaTetap::selectRaw('aktiva_tetap.nama, YEAR(tanggal_pembelian) AS tahun')
        ->selectRaw('MONTH(tanggal_pembelian)AS bulan,  penyusutan_berjalan AS penyusutan, harga_perolehan - penyusutan_berjalan AS nilai_buku')
        ->where('id', $request->id);
        //->unionAll($barisTiga);

        $barisSatu = AktivaTetap::selectRaw('aktiva_tetap.nama, YEAR(tanggal_pembelian) AS tahun')
        ->selectRaw('MONTH(tanggal_pembelian) AS bulan, 0 AS penyusutan, harga_perolehan AS nilai_buku')
        ->where('id', $request->id)
        ->unionAll($barisDua)
        ->get();

        return $this->view('detail', compact('barisSatu', 'master', 'barisTiga'));
    }

    public function edit(Request $request, $kode)
    {
        $data = [
            'aktivaTetap'=>AktivaTetap::find($kode),
            'KelompokAktiva'=>KelompokAktiva::get(['id', 'nama']),
            'lastCode'=>AktivaTetap::selectRaw("concat('M-', substr(kode,3)+1) as lastCode")->orderByDesc('id')->first(),
            'Unit'=>Unit::get(['id', 'nama']),
            'MetodePenyusutan'=>metode_penyusutan::get(['id', 'nama'])
        ];

        return $this->view( "form")->with($data);
    }

    public function update(Request $request, $kode)
    {
        $aktivaTetap=AktivaTetap::find($kode);
        if( $request->isXmlHttpRequest() )
        {
            $data = [$request->name  => $request->value];
            $validator = \Validator::make( $data, AktivaTetap::validationRules( $request->name ) );
            if($validator->fails())
                return response($validator->errors()->first( $request->name),403);
                $aktivaTetap->update($data);
                return "Record updated";
            }
            //$this->validate($request, AktivaTetap::validationRules());

        $tariff = str_replace('.','', $request->tarif);
        $tarif = str_replace(',', '.', $tariff);
        $nilai_residuu = str_replace('.', '', $request->nilai_residu);
        $nilai_residu = str_replace(',', '.', $nilai_residuu);
        $harga_perolehann = str_replace('.', '', $request->harga_perolehan);
        $harga_perolehan = str_replace(',', '.', $harga_perolehann);
        $penyesuaiann = str_replace('.', '', $request->penyesuaian);
        $penyesuaian = str_replace('.', '', $penyesuaiann);

        $tanggal_selesai = DB::select("select DATE_ADD('$request->tanggal_pemakaian', INTERVAL $request->umur_ekonomis MONTH) AS pakai");
        $tanggal = json_encode($tanggal_selesai);
        $tanggal_akhir = substr($tanggal, 11,10);
        $flag_penyusutan = KelompokAktiva::select('flag_penyusutan')->where('id', $request->id_kelompok_aktiva)->first();
        $akumulasi_penyusutan = $tarif * $request->depreciated;
        $flag_penyusutan  = $flag_penyusutan->flag_penyusutan;

        if ($flag_penyusutan == 'Y'):
            $act = AktivaTetap::where('id', $kode)->update([
                'kode'=>$request->kode,
                'nama'=>$request->nama,
                'id_kelompok_aktiva'=>$request->id_kelompok_aktiva,
                'id_unit'=>$request->id_unit,
                'penyusutan'=>1,
                'id_metode_penyusutan'=>$request->id_metode_penyusutan,
                'lokasi'=>$request->lokasi,
                'no_seri'=>$request->no_seri,
                'tanggal_pemakaian'=>$request->tanggal_pemakaian,
                'tanggal_selesai_pakai'=>$tanggal_akhir,
                'tanggal_pembelian'=>$request->tanggal_pembelian,
                'nilai_residu'=>$nilai_residu,
                'umur_ekonomis'=>$request->umur_ekonomis,
                'depreciated'=>$request->depreciated,
                'harga_perolehan'=>$harga_perolehan,
                'penyesuaian'=>$penyesuaian,
                'penyusutan_berjalan'=>$akumulasi_penyusutan,
                'tarif'=>$tarif,
                'status_penyusutan'=>1,
           ]);
           message($act,'Data Aktiva Tetap berhasil diupdate','Data Aktiva Tetap gagal diupdate');

        endif;

        if ($flag_penyusutan == 'N'):
    
            $act = AktivaTetap::where('id', $kode)->update([
                'kode'=>$request->kode,
                'nama'=>$request->nama,
                'id_kelompok_aktiva'=>$request->id_kelompok_aktiva,
                'id_unit'=>$request->id_unit,
                'penyusutan'=>0,
                'id_metode_penyusutan'=>$request->id_metode_penyusutan,
                'lokasi'=>$request->lokasi,
                'no_seri'=>$request->no_seri,
                'tanggal_pemakaian'=>$request->tanggal_pemakaian,
                'tanggal_selesai_pakai'=>$tanggal_akhir,
                'tanggal_pembelian'=>$request->tanggal_pembelian,
                'nilai_residu'=>$nilai_residu,
                'umur_ekonomis'=>$request->umur_ekonomis,
                'depreciated'=>$request->depreciated,
                'harga_perolehan'=>$harga_perolehan,
                'penyesuaian'=>$penyesuaian,
                'penyusutan_berjalan'=>$akumulasi_penyusutan,
                'tarif'=>$tarif,
                'status_penyusutan'=>3,
           ]);
           message($act,'Data Aktiva Tetap berhasil diupdate','Data Aktiva Tetap gagal diupdate');
        endif;

        message($act,'Data Aktiva Tetap berhasil diupdate','Data Aktiva Tetap gagal diupdate');

        return redirect('/aktiva-tetap');
    }

    public function destroy(Request $request, $kode)
    {
        $aktivaTetap=AktivaTetap::find($kode);
        $act=false;

        try {
            $act=$aktivaTetap->forceDelete();
        } catch (\Exception $e) {
            $aktivaTetap=AktivaTetap::find($aktivaTetap->pk());
            $act=$aktivaTetap->delete();
        }
    }

    protected function view($view, $data = [])
    {
        return view($this->viewDir.".".$view, $data);
    }

    public function loadData()
    {
        $GLOBALS['nomor']=\Request::input('start',1)+1;
        $nama = request()->get('nama');
        $kode = request()->get('kode');
        $dataList = AktivaTetap::selectRaw('aktiva_tetap.id, aktiva_tetap.kode, aktiva_tetap.nama, tanggal_pembelian, aktiva_tetap.harga_perolehan')
        ->selectRaw('nilai_residu, umur_ekonomis, depreciated, penyusutan_berjalan, aktiva_tetap.harga_perolehan-penyusutan_berjalan as nilai_buku')
        ->leftJoin('kelompok_aktiva', 'kelompok_aktiva.id',  'aktiva_tetap.id_kelompok_aktiva')
        ->leftJoin('unit', 'unit.id', 'aktiva_tetap.id_unit')
        ->leftJoin('metode_penyusutan', 'metode_penyusutan.id', 'aktiva_tetap.id_metode_penyusutan');

        if ($nama):
            $dataList->where('aktiva_tetap.nama', 'like', $nama.'%');
        endif;

        if ($kode):
            $dataList->where('aktiva_tetap.kode', 'like', $kode.'%');
        endif;

        if (request()->get('status') == 'trash'):
            $dataList->onlyTrashed();
        endif;

        return Datatables::of($dataList)
        ->addColumn('nomor',function($kategori){

            return $GLOBALS['nomor']++;
        })
        ->addColumn('tanggal_pembelian', function($data){

            if (isset($data->tanggal_pembelian)):
                return date('d-M-Y', strtotime($data->tanggal_pembelian));
            endif;
        })
        ->addColumn('harga_perolehan', function ($data) {

            if (isset($data->harga_perolehan)):
                $harga_perolehan = nominalTitik($data->harga_perolehan);
                return $harga_perolehan;
            else:
                return 0;
            endif;
        })
        ->addColumn('penyusutan_berjalan', function ($data) {

            if (isset($data->penyusutan_berjalan)):
                $penyusutan_berjalan = nominalTitik($data->penyusutan_berjalan);
                return $penyusutan_berjalan;
            else:
                return 0;
            endif;
        })
        ->addColumn('nilai_residu', function ($data) {

            if (isset($data->nilai_residu)):
                $nilai_residu = nominalTitik($data->nilai_residu);
                return $nilai_residu;
            else:
                return 0;
            endif;
        })
        ->addColumn('nilai_buku', function ($data) {
            if (isset($data->nilai_buku)):
                $nilai_buku = nominalTitik($data->nilai_buku);
                return $nilai_buku;
            else:
                return 0;
            endif;
        })
        ->addColumn('action', function ($data) {

            $edit=url("aktiva-tetap/".$data->pk())."/edit";
            $delete=url("aktiva-tetap/".$data->pk());
            $content = '';
            $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
            data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
            $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
            data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";
            $content .= "<a href='aktiva-tetap/detail/".$data->pk()."' class='btn btn-outline-primary btn-sm'>Detail</a>";

            return $content;
        })->make(true);
    }
}
