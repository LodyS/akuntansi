<?php
namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KelurahanController extends Controller
{
  public $viewDir = "kelurahan";
  public $breadcrumbs = array(
   'permissions'=>array('title'=>'Kelurahan','link'=>"#",'active'=>false,'display'=>true),
 );

  public function __construct()
  {
    $this->middleware('auth');
   $this->middleware('permission:read-kelurahan');
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
         $data=array(
           'kelurahan'=>new Kelurahan,
           'kecamatan'=>new Kecamatan,
         );
         return $this->view("form",$data);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
         $this->validate($request, Kelurahan::validationRules());

         $act=Kelurahan::create($request->all());
         message($act,'Data Kelurahan berhasil ditambahkan','Data Kelurahan gagal ditambahkan');
         return redirect('kelurahan');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
         $kelurahan=Kelurahan::find($kode);
         return $this->view("show",['kelurahan' => $kelurahan]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
         $kelurahan=Kelurahan::find($kode);
         $kecamatan=Kecamatan::find($kelurahan->id_kecamatan);
         $data=array(
           'kelurahan'=>$kelurahan,
           'kecamatan'=>$kecamatan,
         );
         return $this->view( "form", $data );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
         $kelurahan=Kelurahan::find($kode);
         if( $request->isXmlHttpRequest() )
         {
           $data = [$request->name  => $request->value];
           $validator = \Validator::make( $data, Kelurahan::validationRules( $request->name ) );
           if($validator->fails())
             return response($validator->errors()->first( $request->name),403);
           $kelurahan->update($data);
           return "Record updated";
         }
         $this->validate($request, Kelurahan::validationRules());

         $act=$kelurahan->update($request->all());
         message($act,'Data Kelurahan berhasil diupdate','Data Kelurahan gagal diupdate');

         return redirect('/kelurahan');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
         $kelurahan=Kelurahan::find($kode);
         $act=false;
         try {
           $act=$kelurahan->forceDelete();
         } catch (\Exception $e) {
           $kelurahan=Kelurahan::find($kelurahan->pk());
           $act=$kelurahan->delete();
         }
       }

       protected function view($view, $data = [])
       {
         return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
         $GLOBALS['nomor']=\Request::input('start',1)+1;
         $dataList = Kelurahan::select('*');
         if (request()->get('status') == 'trash') {
           $dataList->onlyTrashed();
         }
         return Datatables::of($dataList)
         ->addColumn('nomor',function($kategori){
           return $GLOBALS['nomor']++;
         })

         ->addColumn('kecamatan',function($data){
          if(isset($data->kecamatan->kecamatan)){

            return $data->kecamatan->kecamatan;
          }else {
            return null;
          }
       })
         ->addColumn('kabupaten',function($data){
          if(isset($data->kecamatan->kabupaten->kabupaten)){

            return $data->kecamatan->kabupaten->kabupaten;
          }else {
            return null;
          }
       })

       ->addColumn('provinsi',function($data){
        if(isset($data->kecamatan->kabupaten->provinsi->provinsi)){

          return $data->kecamatan->kabupaten->provinsi->provinsi;
        }else {
          return null;
        }
     })
         ->addColumn('action', function ($data) {
           $edit=url("kelurahan/".$data->pk())."/edit";
           $delete=url("kelurahan/".$data->pk());
           $content = '';
           $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
           $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

           return $content;
         })
         ->make(true);  
       }

       
    }
