<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;
use Carbon\Carbon;

class MenuController extends Controller
{
  public $viewDir = "menu";
  public $breadcrumbs = array(
   'permissions'=>array('title'=>'Menus','link'=>"#",'active'=>false,'display'=>true),
 );

  public function __construct()
  {
    $this->middleware('auth');
   $this->middleware('permission:read-menus');
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
         return $this->view("form",['menu' => new Menu]);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
         $this->validate($request, Menu::validationRules());

         $act=Menu::create($request->all());
         message($act,'Data Menus berhasil ditambahkan','Data Menus gagal ditambahkan');
         return redirect('menu');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
         $menu=Menu::find($kode);
         return $this->view("show",['menu' => $menu]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
         $menu=Menu::find($kode);
         return $this->view( "form", ['menu' => $menu] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
         $menu=Menu::find($kode);
         if( $request->isXmlHttpRequest() )
         {
           $data = [$request->name  => $request->value];
           $validator = \Validator::make( $data, Menu::validationRules( $request->name ) );
           if($validator->fails())
             return response($validator->errors()->first( $request->name),403);
           $menu->update($data);
           return "Record updated";
         }
         $this->validate($request, Menu::validationRules());

         $act=$menu->update($request->all());
         message($act,'Data Menus berhasil diupdate','Data Menus gagal diupdate');

         return redirect('/menu');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
         $menu=Menu::find($kode);
         $act=false;
         try {
           $act=$menu->forceDelete();
         } catch (\Exception $e) {
           $menu=Menu::find($menu->pk());
           $act=$menu->delete();
         }
       }

       protected function view($view, $data = [])
       {
         return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
         $GLOBALS['nomor']=\Request::input('start',1)+1;
         $dataList = Menu::select('*');
         if (request()->get('status') == 'trash') {
           $dataList->onlyTrashed();
         }
         return Datatables::of($dataList)
         ->addColumn('nomor',function($kategori){
           return $GLOBALS['nomor']++;
         })
         ->addColumn('created_at',function($data){
          if(isset($data->created_at)){
            $tanggal=Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at);
        // dd($tanggal->format('d-m-Y'));
            return $tanggal->format('d-m-Y');
                //   return $data->tanggal_lahir;
          }else{
            return null;
          }
        })
         ->addColumn('parent_status',function($data){
          if(isset($data->parent_status)){
            return ($data->parent_status=='Y')?'Ya':'Tidak';
          }else{
            return null;
          }
        })
         ->addColumn('action', function ($data) {
           $edit=url("menu/".$data->pk())."/edit";
           $delete=url("menu/".$data->pk());
           $content = '';
           $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
           $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

           return $content;
         })
         ->make(true);
       }
     }
