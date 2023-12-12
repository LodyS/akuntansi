<?php
namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kabupaten;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class KecamatanController extends Controller
{
  public $viewDir = "kecamatan";
  public $breadcrumbs = array(
   'permissions'=>array('title'=>'Kecamatan','link'=>"#",'active'=>false,'display'=>true),
 );

  public function __construct()
  {
    $this->middleware('auth');
   $this->middleware('permission:read-kecamatan');
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
           'kecamatan'=>new Kecamatan,
           'kabupaten'=>new Kabupaten,
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
         $this->validate($request, Kecamatan::validationRules());

         $act=Kecamatan::create($request->all());
         message($act,'Data Kecamatan berhasil ditambahkan','Data Kecamatan gagal ditambahkan');
         return redirect('kecamatan');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
         $kecamatan=Kecamatan::find($kode);
         return $this->view("show",['kecamatan' => $kecamatan]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
         $kecamatan=Kecamatan::find($kode);
         $kabupaten=Kabupaten::find($kecamatan->id_kabupaten);
        //    dd($kabupaten);
         $data=array(
           'kecamatan'=>$kecamatan,
           'kabupaten'=>$kabupaten,
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
         $kecamatan=Kecamatan::find($kode);
         if( $request->isXmlHttpRequest() )
         {
           $data = [$request->name  => $request->value];
           $validator = \Validator::make( $data, Kecamatan::validationRules( $request->name ) );
           if($validator->fails())
             return response($validator->errors()->first( $request->name),403);
           $kecamatan->update($data);
           return "Record updated";
         }
         $this->validate($request, Kecamatan::validationRules());

         $act=$kecamatan->update($request->all());
         message($act,'Data Kecamatan berhasil diupdate','Data Kecamatan gagal diupdate');

         return redirect('/kecamatan');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
         $kecamatan=Kecamatan::find($kode);
         $act=false;
         try {
           $act=$kecamatan->forceDelete();
         } catch (\Exception $e) {
           $kecamatan=Kecamatan::find($kecamatan->pk());
           $act=$kecamatan->delete();
         }
       }

       protected function view($view, $data = [])
       {
         return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
         $GLOBALS['nomor']=\Request::input('start',1)+1;
         $dataList = Kecamatan::select('*');
         if (request()->get('status') == 'trash') {
           $dataList->onlyTrashed();
         }
         return Datatables::of($dataList)
         ->addColumn('nomor',function($kategori){
           return $GLOBALS['nomor']++;
         })
         ->addColumn('kabupaten',function($data){
          if(isset($data->kabupaten->kabupaten)){

            return $data->kabupaten->kabupaten;
          }else {
            return null;
          }
       })

       ->addColumn('provinsi',function($data){
        if(isset($data->kabupaten->provinsi->provinsi)){

          return $data->kabupaten->provinsi->provinsi;
        }else {
          return null;
        }
     })
         ->addColumn('action', function ($data) {
           $edit=url("kecamatan/".$data->pk())."/edit";
           $delete=url("kecamatan/".$data->pk());
           $content = '';
           $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
           $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

           return $content;
         })
         ->make(true);
       }
       
    }
