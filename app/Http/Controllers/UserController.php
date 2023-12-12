<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\Pegawai;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;
use App\Traits\ActivityTraits;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ActivityTraits;

    public $viewDir = "user";
    public $breadcrumbs = array(
         'permissions'=>array('title'=>'Users','link'=>"#",'active'=>false,'display'=>true),
       );

       public function __construct()
       {
        $this->middleware('auth');
           $this->middleware('permission:read-users');
       }

       public function index()
       {
          $this->menuAccess(\Auth::user(),'ACL Users');
           return $this->view( "index");
       }

       /**
        * Show the form for creating a new resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function create()
       {
          $role=\App\Role::select(\DB::raw("*"))->get();
          // dd($role);
           return $this->view("form",['user' => new User,'role'=>$role]);
       }

       /**
        * Store a newly created resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */
       public function store( Request $request )
       {
           $this->validate($request, User::validationRules());
           $all_data = $request->all();
           DB::beginTransaction();
           try {
         


             $user  = array(
               'name' =>$all_data['nama'] ,
               'username' =>$all_data['username'] ,
               'email' =>$all_data['email'] ,
               'password' =>bcrypt($all_data['password']) ,
               // 'jenis_kelamin' =>isset($all_data['jenis_kelamin'])?$all_data['jenis_kelamin']:'' ,
               //'jenis_kelamin' =>'L' ,
               'verified'=>true,
               //'id_pegawai'=>$insert_pegawai->id,
             );

             $this->logCreatedActivity(Auth::user(),$user,'ACL Users','users');
             $user=User::create($user);
             

              $this->logCreatedActivity(Auth::user(),[
               'role_id'=>$all_data['role_id'],
               'user_id'=>$user->id,
               'user_type'=>'App\User'
             ],'ACL Users','role_user');

             $roleUser = RoleUser::firstOrCreate([
               'role_id'=>$all_data['role_id'],
               'user_id'=>$user->id,
               'user_type'=>'App\User'
             ]);


           } catch (Exception $e) {
              echo 'Message' .$e->getMessage();
            DB::rollback();
          }
          DB::commit();


           // message($roleUser,'Data Users berhasil ditambahkan','Data Users gagal ditambahkan');
           // return redirect('user');
          if($user==true)
          {
            //$data=array('status'=>true,'message'=>'Data Users berhasil ditambahkan');
            message(true,'Data Users berhasil ditambahkan','Data Users gagal ditambahkan');
            return redirect ('user');
          }
          else
          {
            $data=array('status'=>true,'message'=>'Data Users gagal ditambahkan');
          }
          echo json_encode($data);
       }

       /**
        * Display the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function show(Request $request, $kode)
       {
           $user=User::find($kode);
           return $this->view("show",['user' => $user]);
       }

       public function activate(Request $request, $kode)
       {
        // dd($kode);
        $user=User::find($kode);
        $data=array(
          'status_aktif'=>'Y',
        );
        $this->logUpdatedActivity(Auth::user(),$user->getAttributes(),$data,'ACL Users','users');
        $status=$user->update($data);
        message($status,'User Berhasil Diaktifkan Kembali','User Gagal Diaktifkan Kembali');
        return redirect('user');
       }

        public function deactivate(Request $request, $kode)
       {
       $user=User::find($kode);
        $data=array(
          'status_aktif'=>'N',
        );
        $this->logUpdatedActivity(Auth::user(),$user->getAttributes(),$data,'ACL Users','users');
        $status=$user->update($data);
        message($status,'User Berhasil Dinonaktifkan','User Gagal Dinonaktifkan');
        return redirect('user');
       }

       /**
        * Show the form for editing the specified resource.
        *
        * @return  \Illuminate\Http\Response
        */
       public function edit(Request $request, $kode)
       {
           $user=User::select(\DB::raw("users.*, roles.display_name,role_user.role_id"))
            ->leftjoin('role_user','role_user.user_id','=','users.id')
            ->leftjoin('roles','roles.id','role_user.role_id')
            ->where('users.id',$kode)
            ->first();

            $role=\App\Role::select(\DB::raw("*"))->get();
            // dd($user);
           return $this->view( "form", ['user' => $user,'role'=>$role] );
       }

       /**
        * Update the specified resource in storage.
        *
        * @param    \Illuminate\Http\Request  $request
        * @return  \Illuminate\Http\Response
        */

        public function checkUsername()
        {
            $username = \Request::input('username', null);
            $status = \Request::input('status', null);
            $id_user = \Request::input('id_user', null);
            // dd(strpos(Auth::user()->username,$username));
            if ($status == 'store') {
                $cek = User::where('username', $username)->exists();
            } else {
                $cek = User::where('username', $username)->where('id', '<>', $id_user)->exists();
            }
    
            if ($cek) {
                $data = array(
                    'status' => true,
                    'text' => 'Username Sudah Dipakai',
                );
            } else {
                $data = array(
                    'status' => false,
                );
            }
    
            // dd($cek);
            // dd($cek);
            // \Response::json($data);
            echo json_encode($data);
        }

        public function checkEmail()
      {
        $email = \Request::input('email', null);
        $status = \Request::input('status', null);
        $id_user = \Request::input('id_user', null);
        // dd($email);
        if ($status == 'store') {
            $cek = User::where('email', '=', $email)->exists();
        } else {
            $cek = User::where('email', '=', $email)->where('id', '<>', $id_user)->exists();
        }

        // dd($cek);
        if ($cek) {
            $data = array(
                'status' => true,
            );
        } else {
            $data = array(
                'status' => false,
            );
        }
        echo json_encode($data);
    }

       public function update(Request $request, $kode)
       {
        // dd($kode);
           $user=User::find($kode);
           // $roleUser=RoleUser::where('user_id',$kode)->get();
           // dd($roleUser);
           // $data = [$request->name  => $request->value];
           // dd($data);
           // if( $request->isXmlHttpRequest() )
           // {
           //    //belum disesuaikan karena belum ada contoh nya.
           //     $data = [$request->nama  => $request->value];
           //     $validator = \Validator::make( $data, User::validationRules( $request->nama ) );
           //     if($validator->fails())
           //         return response($validator->errors()->first( $request->nama),403);
           //     $user->update($data);
           //     return "Record updated";
           // }
           // $this->validate($request, User::validationRules());
           $all_data = $request->all();
           // dd($all_data);
           DB::beginTransaction();
           try {
            if(!empty($all_data['password']))
            {
              $dataUser = array(
              'name' =>$all_data['nama'] ,
              'username' =>$all_data['username'] ,
              'email' =>$all_data['email'] ,
              'password' =>!empty($all_data['password'])?bcrypt($all_data['password']):'' ,
              // 'jenis_kelamin' =>isset($all_data['jenis_kelamin'])?$all_data['jenis_kelamin']:'' ,
              //'jenis_kelamin' =>'L' ,
            );
            }
            else
            {
              $dataUser = array(
              'name' =>$all_data['nama'] ,
              'username' =>$all_data['username'] ,
              'email' =>$all_data['email'] ,
              // 'jenis_kelamin' =>isset($all_data['jenis_kelamin'])?$all_data['jenis_kelamin']:'' ,
              //'jenis_kelamin' =>'L' ,
            );
            }
            // dd($user);
            $act=$user->update($dataUser);
            /*$this->logUpdatedActivity(Auth::user(),$user->getAttributes(),$dataUser,'ACL Users','users');

          

            $this->logDeletedActivity(RoleUser::where('user_id',$kode)->first(),'Hapus Role User user_id='.$kode.'','ACL Users','role_user');
            $delRoleUser=RoleUser::where('user_id',$kode)->forceDelete();

            $this->logCreatedActivity(Auth::user(),[
               'role_id'=>$all_data['role_id'],
               'user_id'=>$user->id,
               'user_type'=>'App\User'
             ],'ACL Users','role_user');
            
            $roleUser = RoleUser::firstOrCreate([
              'role_id'=>$all_data['role_id'],
              'user_id'=>$user->id,
              'user_type'=>'App\User'
            ]); */
             

          } catch (Exception $e) {
             echo 'Message' .$e->getMessage();
           DB::rollback();
         }
         DB::commit();
          // message($roleUser,'Data Users berhasil diupdate','Data Users gagal diupdate');

          // return redirect('/user');
         if($act==true)
          {
            $data=array('status'=>true,'message'=>'Data Users berhasil diupdate');
          }
          else
          {
            $data=array('status'=>true,'message'=>'Data Users gagal diupdate');
          }
          //echo json_encode($data);
          message (true, 'Berhasil update user', '');
          return redirect('user');
       }

       /**
        * Remove the specified resource from storage.
        *
        * @return  \Illuminate\Http\Response
        */
       public function destroy(Request $request, $kode)
       {
           $user=User::find($kode);
           $act=false;
           try {
               $this->logDeletedActivity($user,'Delete data id='.$kode.' di menu ACL Users','ACL Users','users');
               $act=$user->forceDelete();
               //$delRoleUser=RoleUser::where('user_id',$kode)->forceDelete();
           } catch (\Exception $e) {
               $this->logDeletedActivity($user,'Delete data id='.$kode.' di menu ACL Users','ACL Users','users');
               $user=User::find($user->pk());
               $act=$user->delete();
               //$delRoleUser=RoleUser::where('user_id',$kode)->delete();
           }
       }

        public function reset(Request $request, $kode)
       {
        // dd($kode);
           $user=User::find($kode);
           $dat=array(
            'password'=>bcrypt('12345678'),
           );
           $this->logUpdatedActivity(Auth::user(),$user->getAttributes(),$dat,'ACL Users','users');
           $reset=$user->update($dat);
           if($reset==true)
           {
            $data=array('status'=>true);
           }
           else
           {
            $data=array('status'=>false);
           }
           echo json_encode($data);
       }



       protected function view($view, $data = [])
       {
           return view($this->viewDir.".".$view, $data);
       }
       public function loadData()
       {
          
           $GLOBALS['nomor']=\Request::input('start',1)+1;
           $dataList = User::select('*');
           if (request()->get('status') == 'trash') {
               $dataList->onlyTrashed();
           }
		   
           return Datatables::of($dataList)
               ->addColumn('nomor',function($kategori){
                   return $GLOBALS['nomor']++;
               })
               

               ->addColumn('status', function ($data) {
                   if(isset($data->status_aktif))
                   {
                    return array('id'=>$data->pk(),'status_aktif'=>$data->status_aktif);
                   }else
                   {
                    return null;
                   }
                   
               })

               ->addColumn('action', function ($data)  {
                   $edit=url("user/".$data->pk())."/edit";
                   $delete=url("user/".$data->pk());
                   $reset=url("user/".$data->pk())."/reset";
                 $content = '';
              
                  $content .= "<a onclick='show_modal(\"$edit\")' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row ' data-toggle='tooltip' data-original-title='Edit' title='Edit'><i class='icon md-edit' aria-hidden='true'></i></a>";
                  //$content .= " <a onclick='hapus(\"$delete\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Remove' title='Remove'><i class='icon md-delete' aria-hidden='true'></i></a>";
                  $content .= " <a onclick='reset(\"$reset\")' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row' data-toggle='tooltip' data-original-title='Reset Password' title='Reset Password'><i class='icon md-refresh' aria-hidden='true'></i></a>";
                

                   return $content;
               })
               ->make(true);
       }
         }
