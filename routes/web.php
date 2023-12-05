<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PDFController;
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
Route::get('/home/inbox', [HomeController::class, 'inbox'])->middleware('auth');
Route::post('/home/inbox/{algo}/{id}', [HomeController::class, 'store_inbox'])->middleware('auth');

Route::get('/home/data/fullname/{id}', [DataController::class, 'fullname'])->middleware('auth');
Route::get('/home/data/id_card/{id}', [DataController::class, 'idcard'])->middleware('auth');
Route::get('/home/data/document/{id}', [DataController::class, 'document'])->middleware('auth');
Route::get('/home/data/video/{id}', [DataController::class, 'video'])->middleware('auth');
Route::post('/home/data/fullname/{id}', [DataController::class, 'decrypt_asym'])->middleware('auth');
Route::post('/home/data/id_card/{id}', [DataController::class, 'decrypt_asym'])->middleware('auth');
Route::post('/home/data/document/{id}', [DataController::class, 'decrypt_asym'])->middleware('auth');
Route::post('/home/data/video/{id}', [DataController::class, 'decrypt_asym'])->middleware('auth');

Route::get('/login', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/register', [UserController::class, 'register']);
Route::post('/register', [UserController::class, 'create']);

Route::get('/download/{algo}/{type}/{id}/{akey}', [HomeController::class, 'download'])->middleware('auth');

Route::post('/mail/fullname/{main_key}/{client_key}', [MailController::class, 'encrypt_fullname'])->name('mail.fullname');
Route::post('/mail/idcard/{main_key}/{client_key}', [MailController::class, 'encrypt_idcard'])->name('mail.idcard');
Route::post('/mail/document/{main_key}/{client_key}', [MailController::class, 'encrypt_document'])->name('mail.document');
Route::post('/mail/video/{main_key}/{client_key}', [MailController::class, 'encrypt_video'])->name('mail.video');

Route::get('/sign/{userId}', [PDFController::class, 'sign'])->middleware('auth');
Route::post('/verify/{userId}', [PDFController::class, 'verify'])->middleware('auth');