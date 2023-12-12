<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\VerifyUser;
use App\Models\Penduduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\VerifyMail;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
date_default_timezone_set("Asia/Jakarta");

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/beranda';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        \DB::beginTransaction();
        try {


            $user= User::create([
                'name' => $data['name'],
                'username'=>$data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            
            ]);
            $data_role=array(
                'role_id'=>3, //role pegawai
                'user_id'=>$user->id,
                'user_type'=>'App\User',
            );
            
            $insert_role=\DB::table('role_user')->insert($data_role);

            


            $verifyUser=VerifyUser::create([
                'user_id'=>$user->id,
                'token'=>str_random(40)
            ]);

    
     
    } catch (Exception $e) {
        echo 'Message' .$e->getMessage();
      DB::rollback();
      message(false,'','Mohon Maaf Terdapat kesalahan, silakan registrasi kembali');

      return redirect('/register');
    }
    DB::commit();
    Mail::to($user->email)->send(new VerifyMail($user));
    return $user;
    }

    public function verifyUser($token)
    {
        $verifyUser=VerifyUser::where('token',$token)->first();
        if(isset($verifyUser))
        {
            $user=$verifyUser->user;
            if(!$user->verified)
            {
                $verifyUser->user->verified=1;
                $verifyUser->user->save();
                $status="Email anda sudah diverifikasi. Anda sudah bisa login.";
                message(true,$status,'');
            }
            else
            {
                $status="Email anda sudah terverifikasi. Anda sudah bisa login";
                message(true,$status,'');
            }
        }
        else
        {
            message(false,null,'Silahkan registrasi akun terlebih dahulu');
            return redirect('/register');
        }

        return redirect('/login')->with('status',$status);
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
        message(true,'Link aktivasi sudah terkirim. Silahkan cek kembali Email anda.','');
        // return redirect('/register/resend');
        // return redirect("/register/createResend");
        return Redirect::route('register.create_resend', array($user));
    }

    public function createResend(Request $request, $id)
    {
        // dd($id);
        $data['id']=$id;
        return view('auth.resend-email',$data);
    }

    public function resend(Request $request)
    {
        // dd($request->all());
        $all_data=$request->all();
        $user=User::find($all_data['id']);

        Mail::to($user->email)->send(new VerifyMail($user));
        
        message(true,'Link aktivasi sudah terkirim. Silahkan cek kembali Email anda.','');
        return Redirect::route('register.create_resend', array($all_data['id']));
    }

}
