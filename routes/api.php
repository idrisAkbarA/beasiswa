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

Route::middleware('auth:petugas')->post('/instansi','InstansiController@store');
Route::middleware('auth:petugas')->put('/instansi/{id}','InstansiController@edit');
Route::middleware('auth:petugas')->get('/instansi','InstansiController@getAll');
Route::middleware('auth:petugas')->get('/instansi/{id}','InstansiController@get');
Route::middleware('auth:petugas')->delete('/instansi/{id}','InstansiController@delete');

Route::middleware('auth:petugas')->post('/petugas','PetugasController@store');
Route::middleware('auth:petugas')->put('/petugas/{id}','PetugasController@edit');
Route::middleware('auth:petugas')->get('/petugas','PetugasController@getAll');
Route::middleware('auth:petugas')->get('/petugas/{id}','PetugasController@get');
Route::middleware('auth:petugas')->delete('/petugas/{id}','PetugasController@delete'); 

Route::middleware('auth:petugas')->post('/beasiswa','BeasiswaController@store');
Route::middleware('auth:petugas')->put('/beasiswa/{id}','BeasiswaController@edit');
Route::middleware('auth:petugas,sanctum')->get('/beasiswa','BeasiswaController@getAll');
Route::middleware('auth:petugas,sanctum')->get('/beasiswa/{id}','BeasiswaController@get');
Route::middleware('auth:petugas')->delete('/beasiswa/{id}','BeasiswaController@delete'); 

Route::post('/pemohon/file', 'PemohonBeasiswaController@storeFile');
Route::post('/pemohon', 'PemohonBeasiswaController@store');