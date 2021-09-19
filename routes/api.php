<?php

use App\Http\Controllers\LPJController;
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
Route::middleware('auth:mahasiswa')->get('/user/mahasiswa', 'AuthAPIController@retrieveUserMhs');

Route::get('/check', 'AuthAPIController@check');

Route::post('login-server', 'AuthAPIController@loginServer');
Route::post('login-without-iraise', 'AuthAPIController@login');

Route::post('authenticate', 'AuthAPIController@login');
Route::post('/authenticate/petugas', 'AuthAPIController@loginPetugas');
Route::get('/logout', 'AuthAPIController@logout');
Route::get('/logout-petugas', 'AuthAPIController@logoutPetugas');

Route::middleware('auth:petugas')->post('/instansi', 'InstansiController@store');
Route::middleware('auth:petugas')->put('/instansi/{id}', 'InstansiController@edit');
Route::middleware('auth:petugas')->get('/instansi', 'InstansiController@getAll');
Route::middleware('auth:petugas')->get('/instansi/{id}', 'InstansiController@get');
Route::middleware('auth:petugas')->delete('/instansi/{id}', 'InstansiController@delete');

Route::middleware('auth:petugas')->post('/petugas', 'PetugasController@store');
Route::middleware('auth:petugas')->put('/petugas/{id}', 'PetugasController@edit');
Route::middleware('auth:petugas')->get('/petugas', 'PetugasController@getAll');
Route::middleware('auth:petugas')->get('/petugas/{id}', 'PetugasController@get');
Route::middleware('auth:petugas')->delete('/petugas/{id}', 'PetugasController@delete');

Route::get('/beasiswa/count-beasiswa', 'BeasiswaController@countBeasiswa');
Route::middleware('auth:petugas')->put('/beasiswa/settings', 'BeasiswaController@setAppSettings');
Route::get('/beasiswa/settings', 'BeasiswaController@getAppSettings');
Route::get('/beasiswa/backup-list', 'BeasiswaController@getBackupList');
Route::get('/beasiswa/get-backup', 'BeasiswaController@getBackup');
Route::get('/beasiswa/get-full-backup', 'BeasiswaController@getFullBackup');
Route::get('/beasiswa/start-queue-daemon', 'BeasiswaController@startQueueDaemon');
Route::get('/beasiswa/pull', 'BeasiswaController@pull');
Route::get('/beasiswa/no-auth', 'BeasiswaController@getAll');
Route::get('/beasiswa/download-report', 'BeasiswaController@downloadReport');
Route::get('/beasiswa/report', 'BeasiswaController@report');
Route::middleware('auth:petugas')->post('/beasiswa', 'BeasiswaController@store');
Route::middleware('auth:petugas')->put('/beasiswa/{id}', 'BeasiswaController@edit');
Route::get('/beasiswa/selection', 'BeasiswaController@selection');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa', 'BeasiswaController@getAll');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa/get-active', 'BeasiswaController@getActive');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa/with-permohonan', 'BeasiswaController@getAllWithPermohonan');
Route::get('/beasiswa/progress', 'BeasiswaController@progress');
Route::get('/beasiswa/selesai', 'BeasiswaController@selesai');
Route::middleware('auth:petugas')->get('/beasiswa/{id}/permohonan', 'BeasiswaController@getPermohonan');
Route::middleware('auth:petugas,mahasiswa')->get('/beasiswa/{id}', 'BeasiswaController@get');
Route::middleware('auth:petugas')->delete('/beasiswa/selesai/{id}', 'BeasiswaController@delete');
Route::middleware('auth:petugas')->delete('/beasiswa/{id}', 'BeasiswaController@destroy');


Route::middleware('auth:petugas')->post('/user', 'UserController@store');
Route::middleware('auth:petugas,mahasiswa')->put('/user/{id}', 'UserController@edit');
Route::middleware('auth:petugas')->get('/user', 'UserController@getAll');
Route::middleware('auth:petugas')->get('/user/export', 'UserController@export');
Route::middleware('auth:petugas,mahasiswa')->get('/user/{id}', 'UserController@get');
Route::middleware('auth:petugas')->delete('/user/{id}', 'UserController@delete');
Route::middleware('auth:petugas')->post('/user/import', 'UserController@import');

Route::get('/fakultas', 'FakultasController@getAll');

Route::middleware('auth:petugas')->get('/permohonan/my-history', 'PemohonBeasiswaController@myHistory');
Route::middleware('auth:petugas')->post('/permohonan/import/{id}', 'PemohonBeasiswaController@import');
Route::middleware('auth:mahasiswa')->get('/permohonan/{id}', 'PemohonBeasiswaController@get');
Route::middleware('auth:petugas')->get('/permohonan/{petugas}/history', 'PemohonBeasiswaController@history');
Route::get('/pemohon/search', 'PemohonBeasiswaController@search');
Route::get('/pemohon', 'PemohonBeasiswaController@getAll');
Route::middleware('auth:petugas,mahasiswa')->post('/pemohon/file', 'PemohonBeasiswaController@storeFile');
Route::middleware('auth:petugas,mahasiswa')->post('/pemohon', 'PemohonBeasiswaController@store');
Route::get('/pemohon/cek-berkas', 'PemohonBeasiswaController@cekBerkas');
Route::middleware('auth:petugas,mahasiswa')->get('/pemohon/cek-isHas', 'PemohonBeasiswaController@isHasBeasiswa');
Route::middleware('auth:petugas')->get('/pemohon/cek-isHas-admin', 'PemohonBeasiswaController@isHasBeasiswaAdmin');


Route::put('/pemohon/set-berkas', 'PemohonBeasiswaController@setBerkas');
Route::get('/pemohon/cek-interview', 'PemohonBeasiswaController@cekinterview');
Route::get('/pemohon/cek-selection', 'PemohonBeasiswaController@cekSelection');
Route::get('/pemohon/cek-survey', 'PemohonBeasiswaController@cekSurvey');
Route::middleware('auth:petugas')->put('/pemohon/set-berkas', 'PemohonBeasiswaController@setBerkas');
Route::middleware('auth:petugas')->put('/pemohon/set-interview', 'PemohonBeasiswaController@setInterview');
Route::middleware('auth:petugas')->put('/pemohon/set-survey', 'PemohonBeasiswaController@setSurvey');
Route::middleware('auth:petugas')->put('/pemohon/set-selection', 'PemohonBeasiswaController@setselection');
Route::get('/pemohon/count-submit', 'PemohonBeasiswaController@countSubmit');
Route::get('/pemohon/count-berkas', 'PemohonBeasiswaController@countBerkas');
Route::get('/pemohon/count-interview', 'PemohonBeasiswaController@countInterview');
Route::get('/pemohon/count-lulus', 'PemohonBeasiswaController@countLulus');

Route::prefix('/jurusan')->group(function () {
    Route::get('/', 'JurusanController@getAll');
    Route::get('/{fakultas_id}', 'JurusanController@getJurusanByFakultas');
});

Route::prefix('/lpj')->group(function () {
    Route::get('/', 'LPJController@index');
    Route::get('/active', 'LPJController@indexMahasiswa');
    Route::get('/daftar/{lpj}', 'LPJController@daftar');
    Route::put('/lulus/{lpj}', 'LPJController@lulusAll');
    Route::get('/{lpj}', 'LPJController@show');
    Route::post('/', 'LPJController@store');
    Route::get('/report', 'LPJController@report');
    Route::put('/{lpj}', 'LPJController@update');
    Route::delete('/{lpj}', 'LPJController@destroy');
});

Route::prefix('/permohonan-lpj')->group(function () {
    Route::post('/', 'PermohonanLPJController@store');
    Route::put('/{permohonan}', 'PermohonanLPJController@update')->middleware('auth:petugas');
    Route::post('/file', 'PermohonanLPJController@storeFile');
    Route::get('/history', 'PermohonanLPJController@history');
    Route::get('/my-history', 'PermohonanLPJController@myHistory');
});
Route::prefix('/kelulusan-editor')->group(function () {
    Route::post('/{beasiswaID}', 'KelulusanEditorController@index');
});
