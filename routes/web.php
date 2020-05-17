<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('app/get_tags', 'AdminControler@getTags');
Route::post('app/create_tag', 'AdminControler@addTag');
Route::post('app/update_tag', 'AdminControler@updateTag');
Route::post('app/delete_tag', 'AdminControler@deleteTag');

Route::get('/', function () {
    return view('welcome');
});


//unmatced-route
Route::any('{slug}',function(){
	return view('welcome');
});


