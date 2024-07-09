<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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
Route::get('/',[RegisterController::class,'index']);
Route::get('/dashboard',[DashboardController::class,'index']);
Route::post('/registered',[RegisterController::class,'store'])->name('store');

Route::get('/login',[LoginController::class,'index']);
Route::post('/login-done',[LoginController::class,'login'])->name('login');
Route::post('/logout',[LoginController::class,'logout'])->name('logout');
Route::post('/post-done',[DashboardController::class,'post'])->name('post');
Route::get('/edit/{email}', [DashboardController::class, 'edit'])->name('editUser');
Route::post('/update-user', [DashboardController::class, 'update'])->name('updateUser');
Route::get('/delete/{email}',[DashboardController::class,'delete'])->name('delete');
Route::get('/user-list',[UserController::class,'index'])->name('user-list');

Route::get('/create-admin',[UserController::class,'createAdmin'])->name('createAdmin');
// Route::get('/', function () {
//     return view('welcome');
// });
