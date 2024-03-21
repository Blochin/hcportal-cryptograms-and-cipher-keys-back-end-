<?php

use App\Http\Controllers\Api\ArchivesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CipherKeyMigrationController;
use App\Http\Controllers\Api\CipherKeysController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\CryptogramsController;
use App\Http\Controllers\Api\CryptogramsMigrationController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\KeyTypesController;
use App\Http\Controllers\Api\LanguagesController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PersonsController;
use App\Http\Controllers\Api\SolutionsController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TagsController;
use Illuminate\Support\Facades\Route;

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


Route::middleware(['auth:sanctum'])->namespace('App\Http\Controllers\Api')->group(static function () {
    Route::get('/logout', [LoginController::class, 'logout']);

    //-------------------------------------------------Locations--------------------------------------------
    Route::get('/locations', [LocationsController::class, 'index']);
    Route::get('/locations/continents', [LocationsController::class, 'continents']);

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

    Route::get('/configuration',[ConfigurationController::class, 'index']);

    //-------------------------------------------------Cipher keys--------------------------------------------
    Route::post('/cipher-keys', [CipherKeysController::class, 'create']);
    Route::post('/cipher-keys/{cipherKey}', [CipherKeysController::class, 'update']);
    Route::get('/cipher-keys/my', [CipherKeysController::class, 'myKeys']);
    Route::get('/cipher-keys/{cipherKey}', [CipherKeysController::class, 'show']);

    Route::get('/key-types', [KeyTypesController::class, 'index']);

    //-------------------------------------------------Cryptograms--------------------------------------------
    Route::get('/cryptograms/my', [CryptogramsController::class, 'myCryptograms']);
    Route::post('/cryptograms', [CryptogramsController::class, 'create']);
    Route::post('/cryptograms/{cryptogram}', [CryptogramsController::class, 'update']);


    Route::post('/configuration/exec-worker',[ConfigurationController::class, 'execWorker']);
    Route::post('/configuration/kill-worker',[ConfigurationController::class, 'killWorker']);
});


//-------------------------------------Auth--------------------------------------------------------
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);
Route::get('/token/validation', [LoginController::class, 'tokenCheck']);
Route::post('/password/reset/code', [ForgotPasswordController::class, 'changePassword']);
Route::post('/password/reset', [ForgotPasswordController::class, 'sendCode']);


//-------------------------------------------------Statistics--------------------------------------------
Route::get('/statistics', [StatisticsController::class, 'index']);

//-------------------------------------------------Cipher keys--------------------------------------------
Route::get('/cipher-keys', [CipherKeysController::class, 'approved']);
Route::get('/cipher-keys/{cipherKey}', [CipherKeysController::class, 'show']);
Route::get('/cipher-keys/export/{cipherKey}', [CipherKeysController::class, 'exportCipherKey']);

//-------------------------------------------------Cryptograms--------------------------------------------
Route::get('/cryptograms', [CryptogramsController::class, 'approved']);
Route::get('/cryptograms/{cryptogram}', [CryptogramsController::class, 'show']);
Route::get('/cryptograms/export/{cryptogram}', [CryptogramsController::class, 'exportCryptogram']);

//-------------------------------------------------Tags--------------------------------------------
Route::get('/tags', [TagsController::class, 'index']);
