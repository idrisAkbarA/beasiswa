<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:petugas')->get('/user/petugas', 'AuthAPIController@retrieveUserPetugas');
Route::middleware('auth:sanctum')->get('/user', 'AuthAPIController@retrieveUserMhs');

Route::get('/check','AuthAPIController@check');

Route::post('/authenticate', 'AuthAPIController@login');
Route::post('/authenticate/petugas', 'AuthAPIController@loginPetugas');
Route::get('/logout', 'AuthAPIController@logout');

Route::post('/instansi','InstansiController@store');
Route::put('/instansi/{id}','InstansiController@edit');
Route::get('/instansi','InstansiController@getAll');
Route::get('/instansi/{id}','InstansiController@get');
Route::delete('/instansi/{id}','InstansiController@delete');

Route::post('/petugas','PetugasController@store');
Route::put('/petugas/{id}','PetugasController@edit');
Route::get('/petugas','PetugasController@getAll');
Route::get('/petugas/{id}','PetugasController@get');
Route::delete('/petugas/{id}','PetugasController@delete'); 

Route::post('/beasiswa','BeasiswaController@store');
Route::put('/beasiswa/{id}','BeasiswaController@edit');
Route::get('/beasiswa','BeasiswaController@getAll');
Route::get('/beasiswa/{id}','BeasiswaController@get');
Route::delete('/beasiswa/{id}','BeasiswaController@delete'); 

