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

Route::get('/home', 'HomeController@index')->middleware('auth')->name('home');
Route::get('/', function(){
	return redirect()->route('home');
});

Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
	Route::post('store', 'HomeController@store')->name('create');
	Route::post('update', 'HomeController@update')->name('update');
	Route::get('edit/{id}', 'HomeController@edit')->name('edit');
	Route::get('delete/{id}', 'HomeController@destroy')->name('delete');
});


Auth::routes();

