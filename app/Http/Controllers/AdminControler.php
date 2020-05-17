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
}
