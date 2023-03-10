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

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web', 'as' => 'web.'], function (){
    Route::get('/', 'WebController@home')->name('home');
    Route::get('/quero-alugar', 'WebController@rent')->name('rent');
    Route::get('/quero-alugar/{slug}', 'WebController@rentProperty')->name('rentProperty');
    Route::get('/quero-comprar', 'WebController@buy')->name('buy');
    Route::get('/quero-comprar/{slug}', 'WebController@buyProperty')->name('buyProperty');
    Route::get('/filtro', 'WebController@filter')->name('filter');
    Route::get('/contato', 'WebController@contact')->name('contact');

});


Route::group(['prefix' => 'component', 'namespace' => 'Web', 'as' => 'component.'], function (){
    Route::post('main-filter/search', 'FilterController@search')->name('main-filter.search');
    Route::post('main-filter/category', 'FilterController@category')->name('main-filter.category');
    Route::post('main-filter/type', 'FilterController@type')->name('main-filter.type');
});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function (){

    /* Formulario de login */
    Route::get('/', 'AuthController@showLoginForm')->name('login');
    Route::post('login', 'AuthController@login')->name('login.do');

    /* Rotas Protegidas */
    Route::group(['middleware' => ['auth']],function (){

        /* Dashboard Home*/
        Route::get('home', 'AuthController@home')->name('home');

        /* Usuarios */
        Route::get('users/team', 'UserController@team')->name('users.team');
        Route::resource('users', 'UserController');

        /* Empresas */
        Route::resource('companies', 'CompanyController');

        /* Imoveis Imagens*/
        Route::post('properties/setImageCover', 'PropertyController@setImageCover')->name('properties.setImageCover');
        Route::delete('properties/imageRemove', 'PropertyController@imageRemove')->name('properties.imageRemove');
        Route::resource('properties', 'PropertyController');

        /* Contratos*/
        Route::post('contracts/get-data-owner', 'ContractController@getDataOwner')->name('contracts.getDataOwner');
        Route::post('contracts/get-data-acquirer', 'ContractController@getDataAcquirer')->name('contracts.getDataAcquirer');
        Route::post('contracts/get-data-property', 'ContractController@getDataProperty')->name('contracts.getDataProperty');
        Route::resource('contracts', 'ContractController');

    });


    /* Logout */
    Route::get('logout', 'AuthController@logout')->name('logout');
});
