<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
	return response()->json(
    		[
                'code'      =>  200,
                'message'   =>  'SHammer API v1.0'
            ], 200);
});

Route::middleware(['bindings'])->group(function () {
    Route::resource('product', 'ProductController');
});

Route::get('searchByTags', 'TagController@search');