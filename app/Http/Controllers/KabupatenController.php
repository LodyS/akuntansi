<?php
namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KabupatenController extends Controller
{
  public $viewDir = "kabupaten";
  public $breadcrumbs = array(
   'permissions'=>array('title'=>'Kabupaten','link'=>"#",'active'=>false,'display'=>true),
 );

  public function __construct()
  {
    $this->middleware('auth');
   $this->middleware('permission:read-kabupaten');
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
          'kabupaten' => new Kabupaten,
          'provinsi'=>new Provinsi,
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
         $this->validate($request, Kabupaten::validationRules());

         $act=Kabupaten::create($request->all());
         message($act,'Data Kabupaten berhasil ditambahkan','Data Kabupaten gagal ditambahkan');
         return redirect('kabupaten');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
         $kabupaten=Kabupaten::find($kode);
         return $this->view("show",['kabupaten' => $kabupaten]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
         $kabupaten=Kabupaten::find($kode);
         $provinsi=Provinsi::find($kabupaten->id_provinsi);
        //    dd($provinsi);
         $data=array(
           'kabupaten'=>$kabupaten,
           'provinsi'=>$provinsi,
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
         $kabupaten=Kabupaten::find($kode);
         if( $request->isXmlHttpRequest() )
         {
           $data = [$request->name  => $request->value];
           $validator = \Validator::make( $data, Kabupaten::validationRules( $request->name ) );
           if($validator->fails())
             return response($validator->errors()->first( $request->name),403);
           $kabupaten->update($data);
           return "Record updated";
         }
         $this->validate($request, Kabupaten::validationRules());

         $act=$kabupaten->update($request->all());
         message($act,'Data Kabupaten berhasil diupdate','Data Kabupaten gagal diupdate');

         return redirect('/kabupaten');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
         $kabupaten=Kabupaten::find($kode);
         $act=false;
         try {
           $act=$kabupaten->forceDelete();
         } catch (\Exception $e) {
           $kabupaten=Kabupaten::find($kabupaten->pk());
           $act=$kabupaten->delete();
         }
       }

       protected function view($view, $data = [])
       {
         return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
         $GLOBALS['nomor']=\Request::input('start',1)+1;
         $dataList = Kabupaten::select('*');
         if (request()->get('status') == 'trash') {
           $dataList->onlyTrashed();
         }
         return Datatables::of($dataList)
         ->addColumn('nomor',function($kategori){
           return $GLOBALS['nomor']++;
         })

         ->addColumn('provinsi',function($data){
           if(isset($data->provinsi->provinsi)){

             return $data->provinsi->provinsi;
           }else {
             return null;
           }
        })
         ->addColumn('action', function ($data) {
           $edit=url("kabupaten/".$data->pk())."/edit";
           $delete=url("kabupaten/".$data->pk());
           $content = '';
           $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
           $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

           return $content;
         })
         ->make(true);
       }
       
    }
