<?php
namespace App\Http\Controllers;

use App\Models\SettingPusher;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class SettingPusherController extends Controller
{
    public $viewDir = "setting_pusher";
    public $breadcrumbs = array(
         'permissions'=>array('title'=>'Setting-pusher','link'=>"#",'active'=>false,'display'=>true),
       );

       public function __construct()
       {
           $this->middleware('permission:read-setting-pusher');
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
           return $this->view("form",['settingPusher' => new SettingPusher]);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
           $this->validate($request, SettingPusher::validationRules());

           $act=SettingPusher::create($request->all());
           message($act,'Data Setting Pusher berhasil ditambahkan','Data Setting Pusher gagal ditambahkan');
           return redirect('setting-pusher');
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
           $settingPusher=SettingPusher::find($kode);
           return $this->view("show",['settingPusher' => $settingPusher]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
           $settingPusher=SettingPusher::find($kode);
           return $this->view( "form", ['settingPusher' => $settingPusher] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
           $settingPusher=SettingPusher::find($kode);
           if( $request->isXmlHttpRequest() )
           {
               $data = [$request->name  => $request->value];
               $validator = \Validator::make( $data, SettingPusher::validationRules( $request->name ) );
               if($validator->fails())
                   return response($validator->errors()->first( $request->name),403);
               $settingPusher->update($data);
               return "Record updated";
           }
           $this->validate($request, SettingPusher::validationRules());

           $act=$settingPusher->update($request->all());
           message($act,'Data Setting Pusher berhasil diupdate','Data Setting Pusher gagal diupdate');

           return redirect('/setting-pusher');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
           $settingPusher=SettingPusher::find($kode);
           $act=false;
           try {
               $act=$settingPusher->forceDelete();
           } catch (\Exception $e) {
               $settingPusher=SettingPusher::find($settingPusher->pk());
               $act=$settingPusher->delete();
           }
       }

       protected function view($view, $data = [])
       {
           return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
           $GLOBALS['nomor']=\Request::input('start',1)+1;
           $dataList = SettingPusher::select('*');
           if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
           }
           return Datatables::of($dataList)
               ->addColumn('nomor',function($kategori){
                   return $GLOBALS['nomor']++;
               })
               ->addColumn('action', function ($data) {
                   $edit=url("setting-pusher/".$data->pk())."/edit";
                   $delete=url("setting-pusher/".$data->pk());
                 $content = '';
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                  $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                   return $content;
               })
               ->make(true);
       }
         }
