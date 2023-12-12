<?php
namespace App\Http\Controllers;

use App\Models\SurplusDefisitDetail;
use Illuminate\Http\Request;
use App\Models\SurplusDefisit;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SurplusDefisitDetailController extends Controller
{
    public $viewDir = "surplus_defisit_detail";
    public $breadcrumbs = array(
         'permissions'=>array('title'=>'Surplus-defisit-detail','link'=>"#",'active'=>false,'display'=>true),
       );

       public function __construct()
       {
           $this->middleware('permission:read-surplus-defisit-detail');
       }

       public function index()
       {
           return $this->view( "index");
       }

       /**
        * Show the form for creating a new resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function create()
       {
           $surplusDefisitDetail = new SurplusDefisitDetail;
           $surplusDefisit = SurplusDefisit::get(['id', 'nama']);

           return $this->view("form", compact('surplusDefisitDetail', 'surplusDefisit'));
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */

        public function cek ($nama)
        {
            $data = SurplusDefisitDetail::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')->where('nama', $nama)->first();

            echo json_encode($data);
            exit;
        }

        public function store( Request $request )
        {
            $this->validate($request, SurplusDefisitDetail::validationRules());

            $data = SurplusDefisitDetail::selectRaw('CASE WHEN COUNT(id) >= 1 THEN "Ada" ELSE "Tidak Ada" END AS status')
            ->where('nama', $request->nama)
            ->first();

            if ($data->status == 'Tidak Ada')
            {
                SurplusDefisitDetail::where('urutan', '>=', $request->urutan)->increment('urutan',1);

                $act=SurplusDefisitDetail::create($request->all());
                message($act,'Data Surplus Defisit Detail berhasil ditambahkan','Data Surplus Defisit Detail gagal ditambahkan');
                return redirect('surplus-defisit-detail');
            }

            if ($data->status == 'Ada')
            {
                message(false, '','Gagal simpan karena Surplus Defisit sudah ada');
                return redirect('surplus-defisit')->with('danger', 'Gagal simpan karena Surplus Defisit Detail sudah ada');
            } else {
                return redirect('surplus-defisit-detail')->with('success', 'Surplus Defisit Detail berhasil simpan');
            }
        }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
           $surplusDefisitDetail=SurplusDefisitDetail::find($kode);
           return $this->view("show",['surplusDefisitDetail' => $surplusDefisitDetail]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
           $surplusDefisitDetail=SurplusDefisitDetail::find($kode);
           $surplusDefisit = SurplusDefisit::select('id', 'nama')->get();

           return $this->view( "form", compact('surplusDefisitDetail', 'surplusDefisit'));
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
           $surplusDefisitDetail=SurplusDefisitDetail::find($kode);
           if( $request->isXmlHttpRequest() )
           {
               $data = [$request->name  => $request->value];
               $validator = \Validator::make( $data, SurplusDefisitDetail::validationRules( $request->name ) );
               if($validator->fails())
                   return response($validator->errors()->first( $request->name),403);
               $surplusDefisitDetail->update($data);
               return "Record updated";
           }
           $this->validate($request, SurplusDefisitDetail::validationRules());
           SurplusDefisitDetail::where('urutan', '>=', $request->urutan)->increment('urutan',1);
           $act=$surplusDefisitDetail->update($request->all());
           message($act,'Data Surplus Defisit Detail berhasil diupdate','Data Surplus Defisit Detail gagal diupdate');

           return redirect('/surplus-defisit-detail');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
           $surplusDefisitDetail=SurplusDefisitDetail::find($kode);

           $urutan = SurplusDefisitDetail::select('urutan')->where('id', $kode)->first();
           $act=false;
           try {
                SurplusDefisitDetail::where('urutan', '>=', $urutan->urutan)->decrement('urutan',1);
               $act=$surplusDefisitDetail->forceDelete();
           } catch (\Exception $e) {
                SurplusDefisit::where('urutan', '>=', $urutan->urutan)->decrement('urutan',1);
               $surplusDefisitDetail=SurplusDefisitDetail::find($surplusDefisitDetail->pk());
               $act=$surplusDefisitDetail->delete();
           }
       }

       protected function view($view, $data = [])
       {
           return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
           $GLOBALS['nomor']=\Request::input('start',1)+1;
           $dataList = SurplusDefisitDetail::selectRaw("surplus_defisit_detail.id, surplus_defisit_detail.nama,
           case
           when surplus_defisit_detail.type=1 then 'Penambah'
           when surplus_defisit_detail.type=-1 then 'Pengurang'
           end as type,
           surplus_defisit_detail.urutan,
           surplus_defisit.nama as surplus_defisit,
           list_code_rekening, list_code_unit")
           ->leftJoin('surplus_defisit', 'surplus_defisit.id', 'surplus_defisit_detail.id_surplus_defisit');
           if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
           }
           return Datatables::of($dataList)
               ->addColumn('nomor',function($kategori){
                   return $GLOBALS['nomor']++;
               })
               ->addColumn('action', function ($data) {
                   $edit=url("surplus-defisit-detail/".$data->pk())."/edit";
                   $delete=url("surplus-defisit-detail/".$data->pk());
                 $content = '';
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                  $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                   return $content;
               })
               ->make(true);
       }
         }
