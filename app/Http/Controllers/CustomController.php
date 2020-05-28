<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class CustomController extends Controller
{
    public function index(Request $request){
        //return serialize(Auth::check());
        // if(!Auth::check() && $request->path() != 'login'){
        //     return redirect('/login');
        // }
        // if(!Auth::check() && $request->path() == 'login'){
        //     return view('welcome');
        // }
        if(!Auth::check()){
            if($request->path() != 'login'){
                return redirect('/login');
            } else if ($request->path() == 'login'){
                return view('welcome');
            } else {

            }
        }
        $user = Auth::user();
        if($user->userType=='User'){
            $this->logout();
        }
        if(Auth::check() && $request->path() == 'login'){
            return redirect('/dashboard');
        }
        return view('welcome');
        //return $request->path();
    }
    public function login(Request $request){
        $vd = $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $loginCredentials = [
            'email' => $request->email,
            'password'=> $request->password
        ];
        if(Auth::attempt($loginCredentials)){
                $user = Auth::user();
                if($user->userType == 'User'){
                    Auth::logout();
                    return response()->json([
                        'msg' => 'Permission Denied'
                    ], 401);
                } else {
                    return response()->json([
                        'msg' => 'Login successful',
                        'user' => $user,
                    ]);
                } 
        } else {
            return response()->json([
                'msg' => 'Invalid Credentials'
            ], 401);
        }
    }
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
