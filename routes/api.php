<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
Route::middleware('auth:mahasiswa')->get('/user', 'AuthAPIController@retrieveUserMhs');

Route::get('/check','AuthAPIController@check');

Route::post('/authenticate', 'AuthAPIController@login');
Route::post('/authenticate/petugas', 'AuthAPIController@loginPetugas');
Route::get('/logout', 'AuthAPIController@logout');
Route::get('/logout-petugas', 'AuthAPIController@logoutPetugas');

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
Route::get('/beasiswa/no-auth','BeasiswaController@getAll');

Route::middleware('auth:petugas')->post('/beasiswa','BeasiswaController@store');
Route::middleware('auth:petugas')->put('/beasiswa/{id}','BeasiswaController@edit');
Route::get('/beasiswa/selection','BeasiswaController@selection');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa','BeasiswaController@getAll');
Route::get('/beasiswa/get-active','BeasiswaController@getActive');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa/with-permohonan','BeasiswaController@getAllWithPermohonan');
Route::get('/beasiswa/selesai','BeasiswaController@selesai');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa/{id}','BeasiswaController@get');
Route::middleware('auth:petugas')->delete('/beasiswa/{id}','BeasiswaController@delete');


Route::middleware('auth:petugas')->post('/user','UserController@store');
Route::middleware('auth:petugas,mahasiswa')->put('/user/{id}','UserController@edit');
Route::middleware('auth:petugas')->get('/user','UserController@getAll');
Route::middleware('auth:petugas')->get('/user/export', 'UserController@export');
Route::middleware('auth:petugas,mahasiswa')->get('/user/{id}','UserController@get');
Route::middleware('auth:petugas')->delete('/user/{id}','UserController@delete');
Route::middleware('auth:petugas')->post('/user/import','UserController@import');

Route::get('/fakultas', 'FakultasController@getAll');

Route::get('/pemohon/count-beasiswa', 'BeasiswaController@countBeasiswa');
Route::get('/pemohon', 'PemohonBeasiswaController@getAll');
Route::post('/pemohon/file', 'PemohonBeasiswaController@storeFile');
Route::post('/pemohon', 'PemohonBeasiswaController@store');
Route::get('/pemohon/cek-berkas', 'PemohonBeasiswaController@cekBerkas');
Route::get('/pemohon/cek-isHas', 'PemohonBeasiswaController@isHasBeasiswa');
Route::put('/pemohon/set-berkas', 'PemohonBeasiswaController@setBerkas');
Route::get('/pemohon/cek-interview', 'PemohonBeasiswaController@cekinterview');
Route::get('/pemohon/cek-selection', 'PemohonBeasiswaController@cekSelection');
Route::get('/pemohon/cek-survey', 'PemohonBeasiswaController@cekSurvey');
Route::put('/pemohon/set-berkas', 'PemohonBeasiswaController@setBerkas');
Route::put('/pemohon/set-interview', 'PemohonBeasiswaController@setInterview');
Route::put('/pemohon/set-survey', 'PemohonBeasiswaController@setSurvey');
Route::put('/pemohon/set-selection', 'PemohonBeasiswaController@setselection');
Route::get('/pemohon/count-berkas', 'PemohonBeasiswaController@countBerkas');
Route::get('/pemohon/count-interview', 'PemohonBeasiswaController@countInterview');
Route::get('/pemohon/count-lulus', 'PemohonBeasiswaController@countLulus');
