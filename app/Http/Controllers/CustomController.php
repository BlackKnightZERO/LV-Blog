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
        if($user->role->isPermitted == 0){
            $this->logout();
        }
        if(Auth::check() && $request->path() == 'login'){
            return redirect('/dashboard');
        }
        return view('welcome');
        //return $request->path();
    }
    // return $this->checkPagePermission($user, $request);
    // }

    // public function checkPagePermission($user, $request){
    //     $permissions = json_decode($user->role->permission);
    //     $hasPermission = false;
    //     foreach($permissions as $p){
    //         if($p->name==$request->path()){
    //             if($p->view){
    //                 $hasPermission = true;
    //             }
    //         }
    //     }
    //     // echo $hasPermission;
    //     // exit();
    //     if($hasPermission) return view('welcome');
    //     return abort(404);
    // }
    
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
                if($user->role->isPermitted == 0){
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
