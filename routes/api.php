<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['namespace' => 'Api', 'as' => 'api.'], function (){

    Route::post('/auth/login', 'AuthController@login')->name('login');
    Route::group(['middleware' => ['apiProtected']], function(){
        Route::post('/auth/logout', 'AuthController@logout')->name('logout');
        Route::post('/auth/user', 'AuthController@user')->name('user');
        Route::apiResource('/companys', 'CompanyController');
    });

});


