<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\PerencanaanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('access.login');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('access')->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', [AccessController::class, 'login'])->name('access.login');
        Route::post('act', [AccessController::class, 'login_act'])->name('access.login.act');
    });
    Route::group(['prefix' => 'profile', 'middleware' => 'userauth'], function () {
        Route::get('/', [AccessController::class, 'profile'])->name('access.profile');
        Route::post('act', [AccessController::class, 'profile_act'])->name('access.profile.act');
    });
    Route::get('logout', [AccessController::class, 'logout'])->name('access.logout');
});

Route::group(['prefix' => 'config', 'middleware' => 'userauth'], function () {
    Route::group(['prefix' => 'anggaran', 'middleware' => 'userauth'], function () {
        Route::get('/', [ConfigController::class, 'anggaran'])->name('config.anggaran');
        Route::post('list', [ConfigController::class, 'anggaran_list'])->name('config.anggaran.list');
        Route::post('act', [ConfigController::class, 'anggaran_act'])->name('config.anggaran.act');
        Route::get('delete/{id}', [ConfigController::class, 'anggaran_delete'])->name('config.anggaran.delete');
    });
    Route::group(['prefix' => 'unit_kerja', 'middleware' => 'userauth'], function () {
        Route::get('/', [ConfigController::class, 'unit_kerja'])->name('config.unit_kerja');
        Route::post('list', [ConfigController::class, 'unit_kerja_list'])->name('config.unit_kerja.list');
        Route::post('act', [ConfigController::class, 'unit_kerja_act'])->name('config.unit_kerja.act');
        Route::get('delete/{id}', [ConfigController::class, 'unit_kerja_delete'])->name('config.unit_kerja.delete');
    });
});

Route::group(['prefix' => 'master_data', 'middleware' => 'userauth'], function () {
    Route::group(['prefix' => 'user_grup', 'middleware' => 'userauth'], function () {
        Route::get('/', [MasterDataController::class, 'user_grup'])->name('master_data.user_grup');
        Route::post('list', [MasterDataController::class, 'user_grup_list'])->name('master_data.user_grup.list');
        Route::post('act', [MasterDataController::class, 'user_grup_act'])->name('master_data.user_grup.act');
        Route::get('delete/{id}', [MasterDataController::class, 'user_grup_delete'])->name('master_data.user_grup.delete');
    });
    Route::group(['prefix' => 'user', 'middleware' => 'userauth'], function () {
        Route::get('/', [MasterDataController::class, 'user'])->name('master_data.user');
        Route::post('list', [MasterDataController::class, 'user_list'])->name('master_data.user.list');
        Route::get('add', [MasterDataController::class, 'user_form'])->name('master_data.user.add');
        Route::get('edit/{id}', [MasterDataController::class, 'user_form'])->name('master_data.user.edit');
        Route::post('act', [MasterDataController::class, 'user_act'])->name('master_data.user.act');
        Route::get('delete/{id}', [MasterDataController::class, 'user_delete'])->name('master_data.user.delete');
    });
    Route::group(['prefix' => 'kategori', 'middleware' => 'userauth'], function () {
        Route::get('/', [MasterDataController::class, 'kategori'])->name('master_data.kategori');
        Route::post('list', [MasterDataController::class, 'kategori_list'])->name('master_data.kategori.list');
        Route::post('act', [MasterDataController::class, 'kategori_act'])->name('master_data.kategori.act');
        Route::get('delete/{id}', [MasterDataController::class, 'kategori_delete'])->name('master_data.kategori.delete');
    });
});

Route::group(['prefix' => 'perencanaan', 'middleware' => 'userauth'], function(){
    Route::get('/', [PerencanaanController::class, 'index'])->name('perencanaan');
    Route::post('list', [PerencanaanController::class, 'list'])->name('perencanaan.list');
    Route::group(['prefix' => 'add'], function(){
        Route::get('/', [PerencanaanController::class, 'form'])->name('perencanaan.add');
        Route::post('act', [PerencanaanController::class, 'act'])->name('perencanaan.add.act');
    });
    Route::group(['prefix' => 'edit'], function(){
        Route::get('/{id}', [PerencanaanController::class, 'form'])->name('perencanaan.edit');
        Route::post('act', [PerencanaanController::class, 'act'])->name('perencanaan.edit.act');
    });
    Route::get('detail/{id}', [PerencanaanController::class, 'detail'])->name('perencanaan.detail');
    Route::get('delete/{id}', [PerencanaanController::class, 'delete'])->name('perencanaan.delete');
    Route::post('diajukan', [PerencanaanController::class, 'finish'])->name('perencanaan.finish');
    Route::get('batal/{id}', [PerencanaanController::class, 'batal'])->name('perencanaan.batal');
});

Route::group(['prefix' => 'pengajuan', 'middleware' => 'userauth'], function(){
    Route::get('/', [PengajuanController::class, 'index'])->name('pengajuan');
    Route::post('list', [PengajuanController::class, 'list'])->name('pengajuan.list');
    Route::get('detail/{id}', [PengajuanController::class, 'detail'])->name('pengajuan.detail');
    Route::post('diajukan', [PengajuanController::class, 'finish'])->name('pengajuan.finish');
    Route::get('batal/{id}', [PengajuanController::class, 'batal'])->name('pengajuan.batal');
});