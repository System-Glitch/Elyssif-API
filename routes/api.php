<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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
    Route::get('files/{file}/key', 'FileController@key')->name('files.key');
    Route::get('files/{file}/paymentstate', 'FileController@paymentState')->name('files.paymentstate');
    Route::delete('files/{file}', 'FileController@destroy')->name('files.destroy');
    Route::put('files/{file}/cipher', 'FileController@cipher')->name('files.cipher');
});

Route::post('/register', 'Auth\\ApiRegisterController@register');
Route::post('/login', 'Auth\\ApiLoginController@login');
