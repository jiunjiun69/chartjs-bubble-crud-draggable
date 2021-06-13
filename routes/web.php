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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chart', 'ChartController@chart')->name('chart');
Route::get('/chart1', 'ChartController@chart1')->name('chart1');
Route::get('/bubble', 'ChartController@bubble')->name('bubble');
// Route::get('/save', 'ChartController@save')->name('save');
Route::post('/bubble', 'ChartController@save')->name('save');