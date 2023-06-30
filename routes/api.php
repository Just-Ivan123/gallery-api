<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('/check-authentication', 'checkAuthentication');
});

Route::controller(GalleryController::class)->group(function () {
    Route::get('/galleries', 'index');
    Route::get('/{id}', 'show');
    Route::get('/galleries/author/{user_id}', 'userGalleries');
    Route::get('/my-galleries', 'userGalleries');
    Route::post('/galleries', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});