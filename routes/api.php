<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CategoryController,
    TagController,
    AuthController,
    PostController,
};

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
//API route for login user
Route::post('/login', [AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    
    //API route for refresh token
    Route::get('refresh', [AuthController::class, 'refresh']);

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::apiResource('categories', CategoryController::class);
// Route::post('/category', [CategoryController::class, 'store']);
Route::apiResource('tags', TagController::class);
Route::get('posts/search', [PostController::class, 'search']);
Route::get('posts/tag', [PostController::class, 'searchByTag']);
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');
Route::get('posts/category/{category}', [PostController::class, 'getPostByCategory']);
