<?php
namespace App\Http\Controllers;

use App\Models\PermissionRole;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class PermissionRoleController extends Controller
{
    public $viewDir = "permission_role";
    public $breadcrumbs = array(
         'permissions'=>array('title'=>'Permission-role','link'=>"#",'active'=>false,'display'=>true),
       );

       public function __construct()
       {
        $this->middleware('auth');
           $this->middleware('permission:read-permission-role');
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
           return $this->view("form",['permissionRole' => new PermissionRole]);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
           $this->validate($request, PermissionRole::validationRules());

           $act=PermissionRole::create($request->all());
           message($act,'Data Permission Role berhasil ditambahkan','Data Permission Role gagal ditambahkan');
           return redirect('permission-role');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
           $permissionRole=PermissionRole::find($kode);
           return $this->view("show",['permissionRole' => $permissionRole]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
           $permissionRole=PermissionRole::find($kode);
           return $this->view( "form", ['permissionRole' => $permissionRole] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
           $permissionRole=PermissionRole::find($kode);
           if( $request->isXmlHttpRequest() )
           {
               $data = [$request->name  => $request->value];
               $validator = \Validator::make( $data, PermissionRole::validationRules( $request->name ) );
               if($validator->fails())
                   return response($validator->errors()->first( $request->name),403);
               $permissionRole->update($data);
               return "Record updated";
           }
           $this->validate($request, PermissionRole::validationRules());

           $act=$permissionRole->update($request->all());
           message($act,'Data Permission Role berhasil diupdate','Data Permission Role gagal diupdate');

           return redirect('/permission-role');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
           $permissionRole=PermissionRole::find($kode);
           $act=false;
           try {
               $act=$permissionRole->forceDelete();
           } catch (\Exception $e) {
               $permissionRole=PermissionRole::find($permissionRole->pk());
               $act=$permissionRole->delete();
           }
       }

       protected function view($view, $data = [])
       {
           return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
           $GLOBALS['nomor']=\Request::input('start',1)+1;
           $dataList = PermissionRole::select('*');
           if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
           }
           return Datatables::of($dataList)
               ->addColumn('nomor',function($kategori){
                   return $GLOBALS['nomor']++;
               })
               ->addColumn('action', function ($data) {
                   $edit=url("permission-role/".$data->pk())."/edit";
                   $delete=url("permission-role/".$data->pk());
                 $content = '';
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                  $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                   return $content;
               })
               ->make(true);
       }
         }
