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


// Route::get('app/get_tags', 'AdminControler@getTags');
// Route::post('app/create_tag', 'AdminControler@addTag');
// Route::post('app/update_tag', 'AdminControler@updateTag');
// Route::post('app/delete_tag', 'AdminControler@deleteTag');
// Route::post('/app/category/image_upload', 'AdminControler@categoryImageUpload');
// Route::post('/app/category/remove_image', 'AdminControler@categoryImageRemove');
// Route::post('/app/category/create_category', 'AdminControler@categoryAdd');


//ADMIN ROUTES
Route::prefix('admin')->namespace('Admin')->group(function () {
    //tags
    Route::get('/tag/get_tags', 'TagController@get');
    Route::post('/tag/create_tag', 'TagController@add');
    Route::post('/tag/update_tag', 'TagController@update');
    Route::post('/tag/delete_tag', 'TagController@delete');

    //category
    Route::get('/category/get_category','CategoryController@get');
    Route::post('/category/image_upload', 'CategoryController@imageUpload');
    Route::post('/category/remove_image', 'CategoryController@imageRemove');
    Route::post('/category/create_category', 'CategoryController@add');
    Route::post('/category/update_category', 'CategoryController@update');
    Route::post('/category/delete_category', 'CategoryController@delete');

    //users
    Route::get('/users/get_users','UserController@get');
    Route::post('/users/create_user','UserController@add');
    Route::post('/user/update_user','UserController@update');
    Route::post('/user/delete_user','UserController@delete');
    
});



Route::get('/', function () {
    return view('welcome');
});




//unmatced-route
Route::any('{slug}',function(){
	return view('welcome');
});


