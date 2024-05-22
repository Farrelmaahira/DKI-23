<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(CategoryController::class)->group(function(){
    Route::get('/categories', 'index');
    Route::post('/category', 'store');
    Route::delete('/category/{slug}', 'destroy');
});

Route::controller(AuthController::class)->prefix('auth')->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});
