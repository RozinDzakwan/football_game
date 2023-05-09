<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FootballController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

//game
Route::get('/', [FootballController::class, 'index'])->name('login');
Route::post('/playgame', [FootballController::class, 'playGame'])->name('playgame');
Route::get('/playgame', [FootballController::class, 'playGamefails']);
Route::get('/playgame/result/{score}', [FootballController::class, 'storeScore']);
Route::get('/result/{unique}', [FootballController::class, 'getResult'])->name('result');
Route::get('/reedem', [FootballController::class, 'reedemCode'])->name('reedemcode');
Route::get('/resultplayer', [FootballController::class, 'resultPlayer'])->name('resultplayer');

//admin
Route::get('/dbimport/{reset?}', [InstallController::class, 'createDB']);
Route::get('/admin', [LoginController::class, 'index']);
Route::post('/admin/login', [LoginController::class, 'loginAdmin'])->name('loginAdmin');
Route::get('/admin/index/', [AdminController::class, 'index'])->name('admin');
Route::get('/admin/index/edit/{id}', [AdminController::class, 'show']);
Route::post('/admin/index/update', [AdminController::class, 'update'])->name('update');
Route::get('/admin/index/delete/{id}', [AdminController::class, 'delete']);
Route::get('/admin/export', [AdminController::class, 'printPDF'])->name('printPDF');
