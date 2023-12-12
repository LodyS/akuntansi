<?php
namespace App\Http\Controllers;

use App\Models\SettingEmail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SettingEmailController extends Controller
{
    public $viewDir = "setting_email";
    public $breadcrumbs = array(
         'permissions'=>array('title'=>'Setting-email','link'=>"#",'active'=>false,'display'=>true),
       );

       public function __construct()
       {
           $this->middleware('permission:read-setting-email');
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
           return $this->view("form",['settingEmail' => new SettingEmail]);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
           $this->validate($request, SettingEmail::validationRules());

           $act=SettingEmail::create($request->all());
           message($act,'Data Setting Email berhasil ditambahkan','Data Setting Email gagal ditambahkan');
           return redirect('setting-email');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
           $settingEmail=SettingEmail::find($kode);
           return $this->view("show",['settingEmail' => $settingEmail]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
           $settingEmail=SettingEmail::find($kode);
           return $this->view( "form", ['settingEmail' => $settingEmail] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
           $settingEmail=SettingEmail::find($kode);
           if( $request->isXmlHttpRequest() )
           {
               $data = [$request->name  => $request->value];
               $validator = \Validator::make( $data, SettingEmail::validationRules( $request->name ) );
               if($validator->fails())
                   return response($validator->errors()->first( $request->name),403);
               $settingEmail->update($data);
               return "Record updated";
           }
           $this->validate($request, SettingEmail::validationRules());

           $act=$settingEmail->update($request->all());
           message($act,'Data Setting Email berhasil diupdate','Data Setting Email gagal diupdate');

           return redirect('/setting-email');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
           $settingEmail=SettingEmail::find($kode);
           $act=false;
           try {
               $act=$settingEmail->forceDelete();
           } catch (\Exception $e) {
               $settingEmail=SettingEmail::find($settingEmail->pk());
               $act=$settingEmail->delete();
           }
       }

       protected function view($view, $data = [])
       {
           return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
           $GLOBALS['nomor']=\Request::input('start',1)+1;
           $dataList = SettingEmail::select('*');
           if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
           }
           return Datatables::of($dataList)
               ->addColumn('nomor',function($kategori){
                   return $GLOBALS['nomor']++;
               })
               ->addColumn('action', function ($data) {
                   $edit=url("setting-email/".$data->pk())."/edit";
                   $delete=url("setting-email/".$data->pk());
                 $content = '';
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                  $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                   return $content;
               })
               ->make(true);
       }
         }
