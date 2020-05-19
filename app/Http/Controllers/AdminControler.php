<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Category;

class AdminControler extends Controller
{
    //
    public function addTag(Request $request){
        $this->validate($request,[
            'tagName' => 'required',
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function getTags(){
        return Tag::OrderBy('id','desc')->get();
    }
    public function updateTag(Request $request){
        $this->validate($request,[
            'tagName' => 'required',
            'id'=>'required',
        ]);
        return Tag::where('id', $request->id)->update(['tagName' => $request->tagName]);
    }
    public function deleteTag(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->delete();
    }
    public function categoryImageUpload(Request $request){ //419 if not csrf token found
        $this->validate($request,[
            'file' => 'required|file|mimes:jpg,jpeg,png',
        ]);
        $imageName = time().'-'.rand(1,999).'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('/Admin/category'), $imageName);
        return $imageName;

    }
    public function categoryImageRemove(Request $request){

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
    public function categoryAdd(Request $request){
        $this->validate($request,[
            'categoryName' => 'required|unique:categories',
            'iconImage' => 'required',
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
}
