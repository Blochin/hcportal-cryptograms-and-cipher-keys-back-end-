<?php

use App\Http\Controllers\Api\ArchivesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CipherKeysController;
use App\Http\Controllers\Api\CipherTypesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\KeyTypesController;
use App\Http\Controllers\Api\LanguagesController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\PersonsController;
use App\Http\Controllers\Api\SolutionsController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TagsController;

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


//-------------------------------------------------Cipher keys--------------------------------------------
Route::get('/cipher-keys', [CipherKeysController::class, 'approved']);

//-------------------------------------------------Statistics--------------------------------------------
Route::get('/statistics', [StatisticsController::class, 'index']);


Route::middleware(['auth:sanctum'])->namespace('App\Http\Controllers\Api')->group(static function () {
    Route::get('/logout', [LoginController::class, 'logout']);

    //-------------------------------------------------Locations--------------------------------------------
    Route::get('/locations', [LocationsController::class, 'index']);

    //-------------------------------------------------Tags--------------------------------------------
    Route::get('/tags', [TagsController::class, 'index']);

    //-------------------------------------------------Persons--------------------------------------------
    Route::get('/persons', [PersonsController::class, 'index']);

    //-------------------------------------------------Languages--------------------------------------------
    Route::get('/languages', [LanguagesController::class, 'index']);

    //-------------------------------------------------Archives--------------------------------------------
    Route::get('/archives', [ArchivesController::class, 'index']);

    //-------------------------------------------------Categories--------------------------------------------
    Route::get('/categories', [CategoriesController::class, 'index']);

    //-------------------------------------------------Solutions--------------------------------------------
    Route::get('/solutions', [SolutionsController::class, 'index']);

    //-------------------------------------------------Cipher keys--------------------------------------------
    Route::post('/cipher-keys', [CipherKeysController::class, 'create']);
    Route::get('/cipher-keys/my', [CipherKeysController::class, 'myKeys']);
    Route::get('/cipher-keys/{cipherKey}', [CipherKeysController::class, 'show']);
    Route::post('/cipher-keys/{cipherKey}', [CipherKeysController::class, 'update']);

    Route::get('/key-types', [KeyTypesController::class, 'index']);
    Route::get('/cipher-types', [CipherTypesController::class, 'index']);
});
