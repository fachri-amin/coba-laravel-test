<?php

use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\{
    RegisterUserController,
    HomeController,
    UploadImageController,
};
// use \App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterUserController::class, 'create']);
Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/home', [HomeController::class, 'index'])->middleware('auth');

Route::get('/login', fn () => 'Login')->name('login');

Route::post('/image-upload', [UploadImageController::class, 'store']);
