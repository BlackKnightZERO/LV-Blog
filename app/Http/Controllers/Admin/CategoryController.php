<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Category;

class CategoryController extends Controller
{
    public function get(){
        return Category::latest()->get();
    }
    public function imageUpload(Request $request){ //419 if not csrf token found
        $this->validate($request,[
            'file' => 'required|file|mimes:jpg,jpeg,png',
        ]);
        $imageName = time().'-'.rand(1,999).'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('/Admin/category'), $imageName);
        return $imageName;

    }
    public function imageRemove(Request $request){

       // return public_path('/Admin/category/'.$request->iconImage);
        if(file_exists(public_path('/Admin/category/'.$request->iconImage))){
            unlink(public_path('/Admin/category/'.$request->iconImage));
            return response()->json([
                'message' => 'removed successfully'
            ], 200);
          }else{
            return response()->json([
                'message' => 'Could not find image',
            ], 201);
          }
    }
    public function add(Request $request){
        $this->validate($request,[
            'categoryName' => 'required|unique:categories',
            'iconImage' => 'required',
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
    public function update(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id'=>'required',
        ]);
        return Category::where('id', $request->id)->update(['categoryName' => $request->categoryName,
                                                            'iconImage'=>$request->iconImage]);
    }
}
