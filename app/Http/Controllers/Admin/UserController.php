<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function get(){
        // return User::OrderBy('id','desc')->get();
        return User::where('userType', '!=', 'User')->latest()->get();
    }

    public function add(Request $request){
        $this->validate($request,[
            'fullName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'userType' => 'required',
        ]);
        $type = '';
        $hashedPassword = bcrypt($request->password);
        if($request->userType==2){
            $type = 'Editor';
        } else if ($request->userType==1) {
            $type = 'Admin';
        } else {
            $type = 'Unknown';
        }
        return User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $hashedPassword,
            'userType' => $type,
        ]);
    }
}
