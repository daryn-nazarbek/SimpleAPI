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


Route::get('clients', 'ClientController@index');
Route::get('clients/{client}', 'ClientController@show');
Route::post('clients', 'ClientController@create');
Route::delete('clients/{client}', 'ClientController@delete');

Route::get('projects', 'ProjectController@index');
Route::get('projects/{project}', 'ProjectController@show');
Route::post('projects', 'ProjectController@create');
Route::put('projects/{project}', 'ProjectController@update');
Route::delete('projects/{project}', 'ProjectController@delete');