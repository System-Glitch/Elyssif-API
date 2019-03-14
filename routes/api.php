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

Route::middleware('auth:api')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::delete('/logout', 'Auth\\ApiLoginController@logout');

    Route::resource('users', 'UserController', ['only' => ['index', 'update', 'show']]);
    Route::resource('files', 'FileController', ['except' => ['create', 'edit']]);
    Route::put('files/{file}/cipher', 'FileController@cipher')->name('files.cipher');
    Route::get('files/fetch', 'FileController@fetch')->name('files.fetch');
    Route::get('files/check', 'FileController@check')->name('files.check');
});

Route::post('/register', 'Auth\\ApiRegisterController@register');
Route::post('/login', 'Auth\\ApiLoginController@login');
