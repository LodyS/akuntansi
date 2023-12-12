<?php
namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class ProvinsiController extends Controller
{
  public $viewDir = "provinsi";
  public $breadcrumbs = array(
   'permissions'=>array('title'=>'Provinsi','link'=>"#",'active'=>false,'display'=>true),
 );

  public function __construct()
  {
    $this->middleware('auth');
   $this->middleware('permission:read-provinsi');
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
         return $this->view("form",['provinsi' => new Provinsi]);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
         $this->validate($request, Provinsi::validationRules());

         $act=Provinsi::create($request->all());
         message($act,'Data Provinsi berhasil ditambahkan','Data Provinsi gagal ditambahkan');
         return redirect('provinsi');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
         $provinsi=Provinsi::find($kode);
         return $this->view("show",['provinsi' => $provinsi]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
         $provinsi=Provinsi::find($kode);
         return $this->view( "form", ['provinsi' => $provinsi] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
         $provinsi=Provinsi::find($kode);
         if( $request->isXmlHttpRequest() )
         {
           $data = [$request->name  => $request->value];
           $validator = \Validator::make( $data, Provinsi::validationRules( $request->name ) );
           if($validator->fails())
             return response($validator->errors()->first( $request->name),403);
           $provinsi->update($data);
           return "Record updated";
         }
         $this->validate($request, Provinsi::validationRules());

         $act=$provinsi->update($request->all());
         message($act,'Data Provinsi berhasil diupdate','Data Provinsi gagal diupdate');

         return redirect('/provinsi');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
         $provinsi=Provinsi::find($kode);
         $act=false;
         try {
           $act=$provinsi->forceDelete();
         } catch (\Exception $e) {
           $provinsi=Provinsi::find($provinsi->pk());
           $act=$provinsi->delete();
         }
       }

       protected function view($view, $data = [])
       {
         return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
         $GLOBALS['nomor']=\Request::input('start',1)+1;
         $dataList = Provinsi::select('*');
         if (request()->get('status') == 'trash') {
           $dataList->onlyTrashed();
         }
         return Datatables::of($dataList)
         ->addColumn('nomor',function($kategori){
           return $GLOBALS['nomor']++;
         })
         ->addColumn('action', function ($data) {
           $edit=url("provinsi/".$data->pk())."/edit";
           $delete=url("provinsi/".$data->pk());
           $content = '';
           $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
           $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

           return $content;
         })
         ->make(true);
       }
      
    }
