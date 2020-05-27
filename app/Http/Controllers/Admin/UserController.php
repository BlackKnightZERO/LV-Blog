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
        $hashedPassword = bcrypt($request->password);
        //$type = '';
        // if($request->userType==2){
        //     $type = 'Editor';
        // } else if ($request->userType==1) {
        //     $type = 'Admin';
        // } else {
        //     $type = 'Unknown';
        // }
        return User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $hashedPassword,
            'userType' => $request->userType,
        ]);
    }

    public function update(Request $request){
        $validateData = $this->validate($request,[
            'id' => 'required',
            'fullName' => 'required',
            'email' => "required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required',
        ]);
        $updateData =[
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType,
        ];
        if($request->password){
            $hashedPassword = bcrypt($request->password);
            $updateData['password'] = $hashedPassword;
        }
        return User::where('id', $request->id)->update($updateData);
    }

    public function delete(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->delete();
    }
}
