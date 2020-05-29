<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;

class PermissionController extends Controller
{
    public function update(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'permission'=>'required',
        ]);
        return Role::where('id', $request->id)->update([
            'permission' => $request->permission
        ]);
    }
}
