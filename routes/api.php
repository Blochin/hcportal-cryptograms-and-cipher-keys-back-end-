<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;

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


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);
Route::get('/token/validation', [LoginController::class, 'tokenCheck']);


Route::middleware(['auth:sanctum'])->namespace('App\Http\Controllers\Api')->group(static function () {
    Route::get('/logout', [LoginController::class, 'logout']);
});
