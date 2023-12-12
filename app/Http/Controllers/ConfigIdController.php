<?php
namespace App\Http\Controllers;

use App\Models\ConfigId;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;
use DB;
use Illuminate\Support\Facades\File;
use App\Traits\ActivityTraits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Response;
date_default_timezone_set("Asia/Jakarta");

class ConfigIdController extends Controller
{
    use ActivityTraits;
    public $viewDir = "config_id";
    public $breadcrumbs = array(
         'permissions'=>array('title'=>'Config-ids','link'=>"#",'active'=>false,'display'=>true),
       );

       public function __construct()
       {
           $this->middleware('permission:read-config-ids');
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
           return $this->view("form",['configId' => new ConfigId]);
       }

       public function settings()
       {
          $setting=Setting::orderby('id','desc')->first();
          $user=User::find(Auth::user()->id);
          // $foto=Pegawai::find(Auth::user()->id_pegawai);
          // $pegawai=Pegawai::find(Auth::user()->id_pegawai);
          // if(isset($foto->foto) && $foto->foto!==null)
          // {
          //   $foto_pegawai=$foto->foto;
          // }
          // else
          // {
          //   $foto_pegawai=null;
          // }

          $this->menuAccess(\Auth::user(),'Settings');
          return $this->view( "settings",compact('setting','user'));
      }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
           // $this->validate($request, ConfigId::validationRules());
        $all_data=$request->all();
// dd($all_data);
         DB::beginTransaction();
    try {

        if(isset($all_data['mode']) && $all_data['mode']=='config')
        {
          $data=array(
            'config_name'=>$all_data['config_name'],
            'table_source'=>$all_data['table_source'],
            'config_value'=>$all_data['config_value'],
            'description'=>$all_data['description'],
          );
          // dd($data);
          $this->logCreatedActivity(Auth::user(),$data,'Settings (Config ID)','config_id');
           $act=ConfigId::create($data);
        }
        elseif(isset($all_data['mode']) && $all_data['mode']=='perusahaan')
        {
          $cek_setting_exists=Setting::orderby('id','desc')->first();

          if(isset($cek_setting_exists) && !empty($cek_setting_exists))
          {
            $logo=null;
            if(isset($all_data['file_logo']))
            {
              $logo=$all_data['file_logo'];
            }

            if($request->hasFile('logo'))
            {
              if(isset($cek_setting_exists) && $cek_setting_exists->logo!==null)
              {
                if(file_exists( public_path().'/images/logo_setting/'.$cek_setting_exists->logo ))
                {
                  $path =  public_path().'/images/logo_setting/'.$cek_setting_exists->logo;
                  $new_dir=public_path().'/images/recycle_bin/'.$cek_setting_exists->logo;
                  $move = File::move($path, $new_dir);             
                }         
              }

              $extension = $request->file('logo')->getClientOriginalExtension();
              $dir = 'images/logo_setting/';
              $logo = uniqid() . '_' . time() . '.' . $extension;
              $request->file('logo')->move($dir, $logo);
            }


            $data=array(
              'nama_aplikasi'=>$all_data['nama_aplikasi'],
              'alamat'=>$all_data['alamat'],
              'website'=>$all_data['website1'],
              'fax'=>$all_data['fax'],
              'telepon'=>$all_data['telepon'],
              'email'=>$all_data['email'].''.$all_data['at'].''.$all_data['email1'],
              'logo'=>$logo,
            
              'status_shift'=>$all_data['status_shift'],
            );

            $this->logUpdatedActivity(Auth::user(),$cek_setting_exists->getAttributes(),$data,'Settings ( Aplikasi )','setting');
            $act=$cek_setting_exists->update($data);
          }
          else
          {
            $logo=null;
            if($request->hasFile('logo'))
            {
              $extension = $request->file('logo')->getClientOriginalExtension();
              $dir = 'images/logo_setting/';
              $logo = uniqid() . '_' . time() . '.' . $extension;
              $request->file('logo')->move($dir, $logo);
            }

            $data=array(
              'nama_aplikasi'=>$all_data['nama_aplikasi'],
              'alamat'=>$all_data['alamat'],
              'website'=>$all_data['website1'],
              'fax'=>$all_data['fax'],
              'telepon'=>$all_data['telepon'],
              'email'=>$all_data['email'].''.$all_data['at'].''.$all_data['email1'],
              'logo'=>$logo,
            );

            $this->logCreatedActivity(Auth::user(),$data,'Settings (Aplikasi)','setting');
            $act=Setting::create($data);
          }
          
        }
        else
        {
            $user=Auth::user();
            if($user->username!=$all_data['username'])
            {
               $data_username=array(
                'username'=>$all_data['username'],
              );
               $this->logUpdatedActivity(Auth::user(),User::find(Auth::User()->id)->getAttributes(),$data_username,'Pengaturan (Username Update)','users');
               $user1=User::find(Auth::User()->id)->update($data_username);
           }

           if(isset($all_data['old_password']) && isset($all_data['new_password']))
           {
              if (Hash::check($all_data['old_password'], $user->password)) {
      // The old password matches the hash in the database
                  $data_pass=array(
                    'password'=>bcrypt($all_data['new_password']),
                  );
                  $this->logUpdatedActivity(Auth::user(),User::find(Auth::User()->id)->getAttributes(),$data_pass,'Profile Edit (Password Update)','users');
                  $pass=User::find(Auth::User()->id)->update($data_pass);
              }

          }

            $data=array(
              'name'=>$all_data['nama'],
              'username'=>$all_data['username'],
              'email'=>$all_data['email_username'],
            );
            $this->logUpdatedActivity(Auth::user(),User::find(Auth::User()->id)->getAttributes(),$data,'Pengaturan (Update)','users');
            $act=$user->update($data);

         

        }

      } catch (Exception $e) {
      // echo 'Message' .$e->getMessage();
       $status=array(
        'status' => false,
        'msg' => $e->getMessage()
      );
       DB::rollback();
     }
     DB::commit();
           // $this->validate($request, ConfigId::validationRules());
          
           message($act,'Data berhasil ditambahkan','Data gagal ditambahkan');
           return redirect('/pengaturan');
       }

       public function deleteLogo()
       {
        $delete_foto=Setting::orderby('id','desc')->first();

        if(isset($delete_foto) && $delete_foto->logo!==null)
        {
          if(file_exists( public_path().'/images/logo_setting/'.$delete_foto->logo ))
          {
            $path =  public_path().'/images/logo_setting/'.$delete_foto->logo;
            $new_dir=public_path().'/images/recycle_bin/'.$delete_foto->logo;
            $move = File::move($path, $new_dir);             
          }         
        }

        $this->logUpdatedActivity(Auth::user(),$delete_foto->getAttributes(),['logo'=>null],'Settings (Logo Aplikasi)','setting');
        $delete=$delete_foto->update(['logo'=>null]);

        if($delete==true)
        {
          $data=array(
            'status'=>true,
            'msg'=>'Logo Berhasil Dihapus'
          );
        }
        else
        {
          $data=array(
            'status'=>false,
            'errors'=>'Logo Gagal Dihapus',
          );
        }
        return \Response::json($data);
      }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
           $configId=ConfigId::find($kode);
           return $this->view("show",['configId' => $configId]);
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
           $configId=ConfigId::find($kode);
           return $this->view( "form", ['configId' => $configId] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function update(Request $request, $kode)
       {
           $configId=ConfigId::find($kode);
           if( $request->isXmlHttpRequest() )
           {
               $data = [$request->name  => $request->value];
               $validator = \Validator::make( $data, ConfigId::validationRules( $request->name ) );
               if($validator->fails())
                   return response($validator->errors()->first( $request->name),403);
               $configId->update($data);
               return "Record updated";
           }
           // $this->validate($request, ConfigId::validationRules());

          $all_data=$request->all();
           $data=array(
            'config_name'=>$all_data['config_name'],
            'table_source'=>$all_data['table_source'],
            'config_value'=>$all_data['config_value'],
            'description'=>$all_data['description'],
          );

            $this->logUpdatedActivity(Auth::user(),$configId->getAttributes(),$data,'Settings (Config ID)','config_id');

           $act=$configId->update($data);
           message($act,'Data Config Ids berhasil diupdate','Data Config Ids gagal diupdate');

           return redirect('/pengaturan');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
           $configId=ConfigId::find($kode);
           $act=false;
             $this->logDeletedActivity($configId,'Delete data id='.$kode.' di menu Settings (Config ID)','Settings (Config ID)','config_id');
           try {
               $act=$configId->forceDelete();
           } catch (\Exception $e) {
               $configId=ConfigId::find($configId->pk());
               $act=$configId->delete();
           }
       }

       protected function view($view, $data = [])
       {
           return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
           $GLOBALS['nomor']=\Request::input('start',1)+1;
           $dataList = ConfigId::select('*');
           if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
           }
           return Datatables::of($dataList)
               ->addColumn('nomor',function($kategori){
                   return $GLOBALS['nomor']++;
               })
               ->addColumn('action', function ($data) {
                   $edit=url("config-id/".$data->pk())."/edit";
                   $delete=url("config-id/".$data->pk());
                 $content = '';
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                  $content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";

                   return $content;
               })
               ->make(true);
       }


  //      public function uploadFoto(Request $request)
  //      {

  //       if ($request->isMethod('get'))
  //         return $this->view( "settings");
  //       else {
  //         $validation = Validator::make($request->all(), [
  //           'select_file' => 'required|image|mimes:jpeg,png,jpg|max:2048'
  //         ]);
  //         if (!$validation->passes())
  //          $data=array(
  //           'fail' => true,
  //           'errors' => $validation->errors()->all()
  //         );
  //        $extension = $request->file('select_file')->getClientOriginalExtension();
  //        $dir = 'images/profil/';
  //        $filename = uniqid() . '_' . time() . '.' . $extension;
  //        $request->file('select_file')->move($dir, $filename);
  //         // return $filename;
  //        $user_check=User::find(Auth::user()->id);

  //        $foto_exists=Pegawai::find(Auth::user()->id_penduduk);

  //        $insert_data=array(
  //         'foto'=>$filename,
  //       );

  //        if(isset($user_check) && $user_check->id_pegawai!==null)
  //        {
  //         if(isset($foto_exists) && $foto_exists->foto!==null)
  //         {
  //           if(file_exists( public_path().'/images/profil/'.Auth::user()->pegawai->foto ))
  //           {
  //             $path =  public_path().'/images/profil/'.$foto_exists->foto;
  //             $new_dir=public_path().'/images/recycle_bin/'.$foto_exists->foto;
  //             $move = File::move($path, $new_dir);             
  //           }         
  //        }

  //         $insert_to_pegawai=Pegawai::where('id',$user_check->id_pegawai)->update($insert_data);
  //       }
  //       else
  //       {
  //         $insert_to_pegawai=Pegawai::create($insert_data);
  //         $data1=array(
  //           'id_pegawai'=>$insert_to_pegawai->id
  //         );
  //         $user_id=$user_check->update($data1);
  //       }

  //       if($insert_to_pegawai==true)
  //       {
  //        $data=array(
  //         'fail'=>false,
  //         'filename'=>$filename,
  //         'msg'=>'Upload Completed',
  //       );
  //      }
  //      else
  //      {
  //       $data=array(
  //         'fail'=>true,
  //         'errors'=>'Foto Profil Gagal Ditambahkan',
  //       );
  //     }

  //     return \Response::json($data);
  //   }
  // }

  // public function deleteFoto()
  // {
  //   // dd('aaaa');
  //   $delete_foto=Pegawai::find(Auth::user()->id_pegawai);
  //   $path =  public_path().'/images/profil/'.$delete_foto->foto;
  //   $new_dir=public_path().'/images/recycle_bin/'.$delete_foto->foto;
  //   $move = File::move($path, $new_dir);

  //   $delete=$delete_foto->update(['foto'=>null]);

  //   if($delete==true)
  //   {
  //     $data=array(
  //       'status'=>true,
  //       'msg'=>'Foto Berhasil Dihapus'
  //     );
  //   }
  //   else
  //   {
  //     $data=array(
  //       'status'=>false,
  //       'errors'=>'Foto Gagal Dihapus',
  //     );
  //   }
  //   return \Response::json($data);
  // }

  public function checkUsername(Request $request)
  {
    $all_data = $request->all();
    $cek=User::where('username',$all_data['username'])->where('id','<>',Auth::user()->id)->exists();
     if($cek==true) {
        return Response::json(array('msg' => 'true'));
      }
     return Response::json(array('msg' => 'false'));  
  }

    public function checkEmail(Request $request)
  {
    $all_data = $request->all();
    $cek=User::where('email',$all_data['email_username'])->where('id','<>',Auth::user()->id)->exists();
     if($cek==true) {
        return Response::json(array('msg' => 'true'));
      }
     return Response::json(array('msg' => 'false'));  
  }

     public function checkPassword(Request $request)
  {
    $all_data = $request->all();
    $user=User::find(Auth::user()->id);
     if (Hash::check($all_data['password'], $user->password)) {
       return Response::json(array('msg' => 'false'));  
      }
      else
      {
         return Response::json(array('msg' => 'true'));
      }  
  }

}
