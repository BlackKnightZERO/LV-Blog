<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    public function get(){
        return Role::latest()->get();
    }
    public function add(Request $request){
        $this->validate($request,[
            'roleName' => 'required|unique:roles',
            // 'permissionAdd' => 'sometimes|required|bool',
            // 'permissionEdit' => 'sometimes|required|bool',
            // 'permissionDelete' => 'sometimes|required|bool',
            // 'permissionView' => 'sometimes|required|bool',
            'isPermitted' => 'required|bool',
        ]);
        
        // $permission = [
        //     'add' => isset($request->permissionAdd) ? $request->permissionAdd : '',
        //     'edit' => isset($request->permissionEdit) ? $request->permissionEdit : '',
        //     'delete' => isset($request->permissionDelete) ? $request->permissionDelete : '',
        //     'view' => isset($request->permissionView) ? $request->permissionView : '',
        // ];
        // $permission = json_encode($permission);
        // $isPermitted = ($request->accessSwitch==true) ? 1 : 0;
        // return json_encode($permission);    
        // return $isPermitted;    
        
        return Role::create([
            'roleName' => $request->roleName,
            // 'permission' => json_encode($permission),
            'isPermitted' => ($request->isPermitted==true) ? 1 : 0,
        ]);
    }
    public function update(Request $request){
        $validateData = $this->validate($request,[
            'id' => 'required',
            'roleName' => "required|unique:roles,roleName,$request->id",
            'isPermitted' => "required|bool",
        ]);
        $updateData =[
            'roleName' => $request->roleName,
            'isPermitted' => ($request->isPermitted==true) ? 1 : 0,
        ];
        return Role::where('id', $request->id)->update($updateData);
    }
    public function delete(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->delete();
    }
}
