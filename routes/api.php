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

    Route::put('users/password', 'UserController@updatePassword')->name('users.update_password');
    Route::resource('users', 'UserController', ['only' => ['index', 'update', 'show']]);

    Route::get('files/sent', 'FileController@indexSent')->name('files.index.sent');
    Route::get('files/received', 'FileController@indexReceived')->name('files.index.received');
    Route::get('files/fetch', 'FileController@fetch')->name('files.fetch');
    Route::get('files/check', 'FileController@check')->name('files.check');
    Route::post('files/', 'FileController@store')->name('files.store');
    //Route::put('files/{file}', 'FileController@update')->name('files.update');
    Route::get('files/{file}', 'FileController@show')->name('files.show');
    Route::get('files/{file}/paymentstate', 'FileController@paymentState')->name('files.paymentstate');
    Route::delete('files/{file}', 'FileController@destroy')->name('files.destroy');
    Route::put('files/{file}/cipher', 'FileController@cipher')->name('files.cipher');
});

Route::post('/register', 'Auth\\ApiRegisterController@register');
Route::post('/login', 'Auth\\ApiLoginController@login');
