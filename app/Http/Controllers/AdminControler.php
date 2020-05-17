<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

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
}
