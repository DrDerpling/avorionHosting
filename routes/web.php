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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('dashboard', 'DashboardController@index');
    Route::get('console/get/{lines?}', 'ConsoleController@getConsoleOutput');

    Route::get('server/status', 'ServerController@serverStatus');
    Route::post('server/start', 'ServerController@startServer');
    Route::post('server/command', 'ServerController@sendCommandToServer');
    Route::delete('server/kill', 'ServerController@forceKilServer');
    Route::delete('server/stop', 'ServerController@stopServer');
});
