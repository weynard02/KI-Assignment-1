<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

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
// Route::get('/', function () {
//     return view('session/welcome');
// });
Route::get('/', [UserController::class, 'welcome']);

Route::get('/home', [HomeController::class, 'index'])->middleware('auth');
Route::get('/home/create', [HomeController::class, 'create'])->middleware('auth');
Route::post('/home', [HomeController::class, 'store'])->middleware('auth');
Route::get('/home/edit', [HomeController::class, 'edit'])->middleware('auth');
Route::put('/home', [HomeController::class, 'update'])->middleware('auth');
Route::get('/home/users', [HomeController::class, 'seeUsers'])->middleware('auth');

Route::get('/login', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/register', [UserController::class, 'register']);
Route::post('/register', [UserController::class, 'create']);

Route::get('/download/{algo}/{type}/{id}', [HomeController::class, 'download']);