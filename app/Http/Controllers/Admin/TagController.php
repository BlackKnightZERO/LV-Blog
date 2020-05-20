<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Tag;

class TagController extends Controller
{
    public function add(Request $request){
        $this->validate($request,[
            'tagName' => 'required',
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function get(){
        return Tag::OrderBy('id','desc')->get();
    }
    public function update(Request $request){
        $this->validate($request,[
            'tagName' => 'required',
            'id'=>'required',
        ]);
        return Tag::where('id', $request->id)->update(['tagName' => $request->tagName]);
    }
    public function delete(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->delete();
    }
}
