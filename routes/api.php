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

    Route::get('files/sent', 'FileController@indexSent')->name('files.index.sent');
    Route::get('files/received', 'FileController@indexReceived')->name('files.index.received');
    Route::put('files/{file:[0-9]+}', 'FileController@update')->name('files.update');
    Route::get('files/{file:[0-9]+}', 'FileController@show')->name('files.show');
    Route::delete('files/{file:[0-9]+}', 'FileController@destroy')->name('files.destroy');
    Route::put('files/{file:[0-9]+}/cipher', 'FileController@cipher')->name('files.cipher');
    Route::get('files/fetch', 'FileController@fetch')->name('files.fetch');
    Route::get('files/check', 'FileController@check')->name('files.check');
});

Route::post('/register', 'Auth\\ApiRegisterController@register');
Route::post('/login', 'Auth\\ApiLoginController@login');
