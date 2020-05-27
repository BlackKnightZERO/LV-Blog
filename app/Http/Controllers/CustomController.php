<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class CustomController extends Controller
{
    //
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
    }
}
