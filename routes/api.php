<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ForgotPasswordController;

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
Route::post('/password/reset/code', [ForgotPasswordController::class, 'changePassword']);
Route::post('/password/reset', [ForgotPasswordController::class, 'sendCode']);


Route::middleware(['auth:sanctum'])->namespace('App\Http\Controllers\Api')->group(static function () {
    Route::get('/logout', [LoginController::class, 'logout']);
});
